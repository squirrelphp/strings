<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;

/**
 * Convert HTML entities to unicode characters
 */
class DecodeAllHTMLEntitiesFilter implements StringFilterInterface
{
    public function filter(string $string): string
    {
        return \html_entity_decode($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
