<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;

/**
 * Remove spaces which are zero width or somehow connect/separate words in a zero width way
 */
class RemoveZeroWidthSpacesFilter implements StringFilterInterface
{
    public function filter(string $string): string
    {
        // Zero width space
        $string = \str_replace("\u{200B}", '', $string);

        // Word joiner, new form of zero width non-breaking space
        $string = \str_replace("\u{2060}", '', $string);

        // Zero width non-breaking space (deprecated in unicode, replaced by 0x2060)
        $string = \str_replace("\u{FEFF}", '', $string);

        return $string;
    }
}
