<?php

namespace Squirrel\Strings\Attribute;

use Squirrel\Strings\Common\InvalidValueExceptionTrait;
use Squirrel\Strings\StringFilterSelectInterface;

/**
 * Process a class according to any StringFilter annotations defined on properties
 */
class StringFilterProcessor
{
    use InvalidValueExceptionTrait;

    public function __construct(private StringFilterSelectInterface $stringFilterSelector)
    {
    }

    /**
     * Processes a class according to its StringFilter annotations
     */
    public function process(object $class): void
    {
        // Get class reflection data
        $annotationClass = new \ReflectionClass($class);

        // Go through all public values of the class
        foreach ($annotationClass->getProperties() as $property) {
            $stringFilters = $this->getFromAttribute($property);

            if ($stringFilters === null) {
                continue;
            }

            // Make it possible to change private properties
            $property->setAccessible(true);

            // Get the property value via reflection
            $propertyValue = $property->getValue($class);

            // If the value is null we skip it
            if ($propertyValue === null) {
                continue;
            }

            $propertyValue = $this->filterScalarOrArray($propertyValue, $stringFilters->getNames());

            $property->setValue($class, $propertyValue);
        }
    }

    private function getFromAttribute(\ReflectionProperty $property): ?StringFilter
    {
        $attributes = $property->getAttributes(StringFilter::class);

        if (\count($attributes) === 0) {
            return null;
        }

        return $attributes[0]->newInstance();
    }

    private function filterScalarOrArray(mixed $propertyValue, array $stringFilters): string|array
    {
        if (
            (!\is_array($propertyValue) && !\is_scalar($propertyValue))
            || \is_bool($propertyValue)
        ) {
            throw $this->generateInvalidValueException('String filter annotation values can only be a non-boolean scalar or an array');
        }

        if (\is_scalar($propertyValue)) { // One scalar value
            return $this->filterValue($stringFilters, \strval($propertyValue));
        }

        foreach ($propertyValue as $key => $value) {
            if (!\is_scalar($value) || \is_bool($value)) {
                throw $this->generateInvalidValueException('String filter annotation array values have to all be scalar and non-boolean');
            }

            $propertyValue[$key] = $this->filterValue($stringFilters, \strval($value));
        }

        return $propertyValue;
    }

    private function filterValue(array $stringFilter, string $string): string
    {
        foreach ($stringFilter as $name) {
            $string = $this->stringFilterSelector->getFilter($name)->filter($string);
        }

        return $string;
    }
}
