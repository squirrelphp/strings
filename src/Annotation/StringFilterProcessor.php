<?php

namespace Squirrel\Strings\Annotation;

use Doctrine\Common\Annotations\Reader;
use Squirrel\Strings\Common\InvalidValueExceptionTrait;
use Squirrel\Strings\StringFilterSelectInterface;

/**
 * Process a class according to any StringFilter annotations defined on properties
 */
class StringFilterProcessor
{
    use InvalidValueExceptionTrait;

    private Reader $annotationReader;
    private StringFilterSelectInterface $stringFilterSelector;

    public function __construct(Reader $annotationReader, StringFilterSelectInterface $stringFilterSelector)
    {
        $this->annotationReader = $annotationReader;
        $this->stringFilterSelector = $stringFilterSelector;
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
            // @codeCoverageIgnoreStart
            if (PHP_VERSION_ID >= 80000) {
                $stringFilters = $this->getFromAttribute($property);
            } else {
                $stringFilters = $this->getFromAnnotation($property);
            }
            // @codeCoverageIgnoreEnd

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

    // @codeCoverageIgnoreStart
    private function getFromAttribute(\ReflectionProperty $property): ?StringFilter
    {
        $attributes = $property->getAttributes(StringFilter::class);

        if (\count($attributes) === 0) {
            return $this->getFromAnnotation($property);
        }

        return $attributes[0]->newInstance();
    }
    // @codeCoverageIgnoreEnd

    private function getFromAnnotation(\ReflectionProperty $property): ?StringFilter
    {
        // Find StringFilter annotation on the property
        $stringFilters = $this->annotationReader->getPropertyAnnotation(
            $property,
            StringFilter::class,
        );

        // A StringFilters annotation was not found
        if (!($stringFilters instanceof StringFilter)) {
            return null;
        }

        return $stringFilters;
    }

    /**
     * @param mixed $propertyValue
     * @return string|array
     */
    private function filterScalarOrArray($propertyValue, array $stringFilters)
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
