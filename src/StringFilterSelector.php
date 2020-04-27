<?php

namespace Squirrel\Strings;

use Squirrel\Strings\Common\InvalidValueExceptionTrait;

/**
 * Handles all string filters
 */
class StringFilterSelector implements StringFilterSelectInterface
{
    use InvalidValueExceptionTrait;

    /**
     * List of filters
     *
     * @var array<string, StringFilterInterface>
     */
    private array $filters = [];

    /**
     * @param array<string, StringFilterInterface> $filters
     */
    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     * Get a specific filter
     */
    public function getFilter(string $name): StringFilterInterface
    {
        if (!isset($this->filters[$name])) {
            throw $this->generateInvalidValueException('Filter with the name "' . $name . '" not found');
        }

        return $this->filters[$name];
    }
}
