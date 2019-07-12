<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;

/**
 * Convert most basic HTML entities to unicode characters
 */
class DecodeBasicHTMLEntitiesFilter implements StringFilterInterface
{
    public function filter(string $string): string
    {
        // apostrophe
        $string = \str_replace('&apos;', '\'', $string);
        $string = \str_replace('&#39;', '\'', $string);

        // quotation mark
        $string = \str_replace('&quot;', '"', $string);
        $string = \str_replace('&#34;', '"', $string);

        // greater-than
        $string = \str_replace('&gt;', '>', $string);
        $string = \str_replace('&#62;', '>', $string);

        // less-than
        $string = \str_replace('&lt;', '<', $string);
        $string = \str_replace('&#60;', '<', $string);

        // ampersand
        $string = \str_replace('&amp;', '&', $string);
        $string = \str_replace('&#38;', '&', $string);

        return $string;
    }
}
