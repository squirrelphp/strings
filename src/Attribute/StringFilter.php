<?php

namespace Squirrel\Strings\Attribute;

use Squirrel\Debug\Debug;
use Squirrel\Strings\Common\InvalidValueExceptionTrait;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class StringFilter
{
    use InvalidValueExceptionTrait;

    /**
     * @var string[] Filter names which should be executed
     */
    public array $names = [];

    public function __construct(mixed $names = null)
    {
        $arguments = \func_get_args();

        if (\count($arguments) === 0) {
            throw $this->generateInvalidValueException('No arguments provided - either string or one array is mandatory');
        }

        // This is the format by Doctrine annotation reader
        if (
            \count($arguments) === 1
            && \is_array($arguments[0])
        ) {
            $this->names = $arguments[0];
        } else {
            $this->names = $arguments;
        }

        foreach ($this->names as $key => $name) {
            if (!\is_string($name)) {
                throw $this->generateInvalidValueException('Non-string filter provided with index ' . $key . ': ' . Debug::sanitizeData($arguments));
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
