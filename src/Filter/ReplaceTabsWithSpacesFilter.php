<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;

/**
 * Remove tabs and replace them with regular spaces
 */
class ReplaceTabsWithSpacesFilter implements StringFilterInterface
{
    public function filter(string $string): string
    {
        return \str_replace("\t", ' ', $string);
    }
}
