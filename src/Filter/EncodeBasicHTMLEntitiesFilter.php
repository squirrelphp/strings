<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;

/**
 * Encode basic characters to HTML entities so it does not clash with HTML / is a security concern
 */
class EncodeBasicHTMLEntitiesFilter implements StringFilterInterface
{
    public function filter(string $string): string
    {
        // Replace html tags with their entity equivalents
        $string = \str_replace('&', '&amp;', $string);
        $string = \str_replace('"', '&quot;', $string);
        $string = \str_replace('\'', '&apos;', $string);
        $string = \str_replace('<', '&lt;', $string);
        $string = \str_replace('>', '&gt;', $string);

        return $string;
    }
}
