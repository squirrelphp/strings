<?php

namespace Squirrel\Strings;

/**
 * Basic setup of a filter: filter the string and return the filtered string
 */
interface StringFilterInterface
{
    /**
     * Filter the string and return the filtered string
     */
    public function filter(string $string): string;
}
