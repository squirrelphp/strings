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
            // Get annotations for a propery
            $annotationProperty = new \ReflectionProperty($class, $property->getName());

            // Make it possible to change private properties
            $annotationProperty->setAccessible(true);

            // Find StringFilter annotation on the property
            $stringFilters = $this->annotationReader->getPropertyAnnotation(
                $annotationProperty,
                StringFilter::class
            );

            // A StringFilters annotation was not found
            if (!($stringFilters instanceof StringFilter)) {
                continue;
            }

            // Get the property value via reflection
            $propertyValue = $annotationProperty->getValue($class);

            // If the value is null we skip it
            if ($propertyValue === null) {
                continue;
            }

            $propertyValue = $this->filterScalarOrArray($propertyValue, $stringFilters->names);

            $annotationProperty->setValue($class, $propertyValue);
        }
    }

    /**
     * @param mixed $propertyValue
     * @param string|array $stringFilters
     * @return string|array
     */
    private function filterScalarOrArray($propertyValue, $stringFilters)
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

    /**
     * @param mixed $stringFilter
     */
    private function filterValue($stringFilter, string $string): string
    {
        // StringFilters just has one filter as a string - call it
        if (\is_string($stringFilter)) {
            return $this->stringFilterSelector->getFilter($stringFilter)->filter($string);
        } elseif (\is_array($stringFilter)) {
            foreach ($stringFilter as $name) {
                $string = $this->stringFilterSelector->getFilter($name)->filter($string);
            }
            return $string;
        }

        throw $this->generateInvalidValueException('String filter annotation filters have to be specified as a string or an array');
    }
}
