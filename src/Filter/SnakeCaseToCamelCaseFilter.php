<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;

/**
 * Convert snake case (my_great_variable) to camel case (myGreatVariable)
 */
class SnakeCaseToCamelCaseFilter implements StringFilterInterface
{
    public function filter(string $string): string
    {
        return \lcfirst(\str_replace('_', '', \ucwords(\strtolower($string), '_')));
    }
}
