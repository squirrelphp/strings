<?php

namespace Squirrel\Strings;

/**
 * Wrapper to combine multiple filters so we can run a list of filters
 */
class StringFilterRunner implements StringFilterInterface
{
    /**
     * @var StringFilterInterface[]
     */
    private array $stringFilters = [];

    public function __construct(StringFilterInterface ...$stringFilters)
    {
        $this->stringFilters = $stringFilters;
    }

    public function filter(string $string): string
    {
        // Go through all our filters, use them
        foreach ($this->stringFilters as $stringFilter) {
            $string = $stringFilter->filter($string);
        }

        // Return modified string
        return $string;
    }
}
