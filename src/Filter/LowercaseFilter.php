<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;

class LowercaseFilter implements StringFilterInterface
{
    public function filter(string $string): string
    {
        return \mb_strtolower($string);
    }
}
