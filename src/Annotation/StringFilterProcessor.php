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

    /**
     * @var Reader Annotation reader from doctrine
     */
    private $annotationReader;

    /**
     * @var StringFilterSelectInterface
     */
    private $stringFilterSelector;

    public function __construct(Reader $annotationReader, StringFilterSelectInterface $stringFilterSelector)
    {
        $this->annotationReader = $annotationReader;
        $this->stringFilterSelector = $stringFilterSelector;
    }

    /**
     * Processes a class according to its StringFilter annotations
     *
     * @param object $class
     * @return string[]
     */
    public function process(object $class): array
    {
        $processedProperties = [];

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

            // A StringFilters annotation was found
            if ($stringFilters instanceof StringFilter) {
                $processedProperties[] = $property->getName();

                // Get the property value via reflection
                $propertyValue = $annotationProperty->getValue($class);

                // Convert to string if it currently is not a string
                if (!isset($propertyValue)) {
                    $propertyValue = '';
                }

                // Array of strings as property value
                if (\is_array($propertyValue)) {
                    foreach ($propertyValue as $key => $value) {
                        $propertyValue[$key] = $this->filterValue($stringFilters->names, $value);
                    }
                } elseif (\is_scalar($propertyValue)) { // Regular string as property value
                    $propertyValue = $this->filterValue($stringFilters->names, \strval($propertyValue));
                } else {
                    throw $this->generateInvalidValueException('String filter annotation values can only be a string or an array');
                }

                $annotationProperty->setValue($class, $propertyValue);
            }
        }

        return $processedProperties;
    }

    /**
     * @param mixed $stringFilter
     * @param string $string
     * @return string
     */
    private function filterValue($stringFilter, string $string)
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
