<?php

namespace Squirrel\Strings\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Squirrel\Debug\Debug;
use Squirrel\Strings\Common\InvalidValueExceptionTrait;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class StringFilter
{
    use InvalidValueExceptionTrait;

    /**
     * @var string[] Filter names which should be executed
     */
    private array $names = [];

    /**
     * @param mixed $names
     */
    public function __construct($names = null)
    {
        $arguments = \func_get_args();

        if (\count($arguments) === 0) {
            throw $this->generateInvalidValueException('No arguments provided - either string or one array is mandatory');
        }

        if (\count($arguments) === 1 && \is_array($arguments[0])) {
            $this->names = $arguments[0];
        } else {
            $this->names = $arguments;
        }

        foreach ($this->names as $key => $name) {
            if (!\is_string($name)) {
                throw $this->generateInvalidValueException('Non-string filter provided with index ' . $key . ': ' . Debug::sanitizeData($name));
            }
        }
    }

    /**
     * @return string[]
     */
    public function getNames(): array
    {
        return $this->names;
    }
}
