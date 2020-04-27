<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;
use Squirrel\Strings\StringFilterRunner;

/**
 * Normalize a string to only lowercase ASCII letters and numbers, does the following to a string:
 *
 * - Normalizes all letters to ascii, so letters like "é" are converted to "e", "ö" to "o" etc.
 * - Removes any non-ASCII letters and numbers from the string
 * - Converts all characters to lowercase
 *
 * So a string like "l'école de la Rue" will be converted to "lecoledelarue"
 */
class NormalizeToAlphanumericLowercaseFilter implements StringFilterInterface
{
    private StringFilterRunner $filterRunner;

    public function __construct()
    {
        $this->filterRunner = new StringFilterRunner(
            new NormalizeToAlphanumericFilter(),
            new LowercaseFilter()
        );
    }

    public function filter(string $string): string
    {
        return $this->filterRunner->filter($string);
    }
}
