<?php

namespace Squirrel\Strings\Condense;

/**
 * Converts a number to a condensed string - this way we can reduce the number
 * of characters used and still keep it readable. Ideal for links, codes etc.
 * which are actually integers but are sent as strings to save space.
 *
 * Ascii only! We use str_split which can only handle single byte characters
 */
class ConverterAscii extends ConverterUnicode
{
    /**
     * Use PHP str_split for ascii
     *
     * @param string $string
     * @param int $split_length
     * @return array
     */
    protected function mbStringSplit($string, $split_length = 1)
    {
        return \str_split($string);
    }
}
