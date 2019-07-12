<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;

class UppercaseFirstCharacterFilter implements StringFilterInterface
{
    public function filter(string $string): string
    {
        $firstCharacter = \mb_strtoupper(\mb_substr($string, 0, 1, 'UTF-8'), 'UTF-8');
        return $firstCharacter . \mb_substr($string, 1, null, 'UTF-8');
    }
}
