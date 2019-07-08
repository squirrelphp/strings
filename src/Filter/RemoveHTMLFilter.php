<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;

/**
 * Remove HTML tags
 */
class RemoveHTMLFilter implements StringFilterInterface
{
    public function filter(string $string): string
    {
        return \strip_tags($string);
    }
}
