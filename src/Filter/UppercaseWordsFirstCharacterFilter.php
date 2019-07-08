<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;

class UppercaseWordsFirstCharacterFilter implements StringFilterInterface
{
    public function filter(string $string): string
    {
        return \ucwords($string);
    }
}
