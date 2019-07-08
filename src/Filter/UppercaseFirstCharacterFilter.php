<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;

class UppercaseFirstCharacterFilter implements StringFilterInterface
{
    public function filter(string $string): string
    {
        return \ucfirst($string);
    }
}
