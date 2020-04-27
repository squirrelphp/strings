<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;
use Squirrel\Strings\StringFilterRunner;

/**
 * Normalize a string to only ASCII letters and numbers, does the following to a string:
 *
 * - Normalizes all letters to ascii, so letters like "é" are converted to "e", "ö" to "o" etc.
 * - Removes any non-ASCII letters and numbers from the string
 *
 * So a string like "l'école de la rue" will be converted to "lecoledelarue"
 */
class NormalizeToAlphanumericFilter implements StringFilterInterface
{
    private StringFilterRunner $filterRunner;

    public function __construct()
    {
        $this->filterRunner = new StringFilterRunner(
            new NormalizeLettersToAsciiFilter(),
            new RemoveNonAlphanumericFilter()
        );
    }

    public function filter(string $string): string
    {
        return $this->filterRunner->filter($string);
    }
}
