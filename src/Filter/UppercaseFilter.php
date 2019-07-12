<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;

class UppercaseFilter implements StringFilterInterface
{
    public function filter(string $string): string
    {
        return \mb_strtoupper($string, 'UTF-8');
    }
}
