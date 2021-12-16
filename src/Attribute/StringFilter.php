<?php

namespace Squirrel\Strings\Attribute;

use Squirrel\Debug\Debug;
use Squirrel\Strings\Common\InvalidValueExceptionTrait;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class StringFilter
{
    use InvalidValueExceptionTrait;

    /** @var string[] Filter names which should be executed */
    private array $names;

    /** @param non-empty-string|non-empty-string[] $names */
    public function __construct(
        string|array $names,
        string ...$moreNames,
    ) {
        if (\is_string($names)) {
            $names = [$names];
        }

        $this->names = \array_values($names);

        if (\count($moreNames) > 0) {
            $this->names = \array_merge($this->names, \array_values($moreNames));
        }

        foreach ($this->names as $key => $name) {
            if (!\is_string($name)) {
                throw $this->generateInvalidValueException('Non-string filter provided, names: ' . Debug::sanitizeData($names) . ', moreNames: ' . Debug::sanitizeData($moreNames));
            } elseif (\strlen($name) === 0) {
                throw $this->generateInvalidValueException('Zero-length filter provided, names: ' . Debug::sanitizeData($names) . ', moreNames: ' . Debug::sanitizeData($moreNames));
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
