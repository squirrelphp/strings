<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;

/**
 * Replace newlines with regular spaces
 */
class ReplaceNewlinesWithSpacesFilter implements StringFilterInterface
{
    private NormalizeNewlinesToUnixStyleFilter $normalizeNewlinesFilter;

    public function __construct()
    {
        $this->normalizeNewlinesFilter = new NormalizeNewlinesToUnixStyleFilter();
    }

    public function filter(string $string): string
    {
        // Normalize all newlines to \n first
        $string = $this->normalizeNewlinesFilter->filter($string);

        // Replace unix newlines (\n) with a space
        $string = \str_replace("\n", ' ', $string);

        return $string;
    }
}
