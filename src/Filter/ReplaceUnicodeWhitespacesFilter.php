<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;

/**
 * Replace unicode whitespaces with regular spaces
 */
class ReplaceUnicodeWhitespacesFilter implements StringFilterInterface
{
    public function filter(string $string): string
    {
        // Non-breaking unicode whitespace
        $string = \str_replace("\u{00A0}", ' ', $string);

        // Ogham space mark
        $string = \str_replace("\u{1680}", ' ', $string);

        // en and em quad / spaces and other spaces
        $string = \str_replace("\u{2000}", ' ', $string);
        $string = \str_replace("\u{2001}", ' ', $string);
        $string = \str_replace("\u{2002}", ' ', $string);
        $string = \str_replace("\u{2003}", ' ', $string);
        $string = \str_replace("\u{2004}", ' ', $string);
        $string = \str_replace("\u{2005}", ' ', $string);
        $string = \str_replace("\u{2006}", ' ', $string);
        $string = \str_replace("\u{2007}", ' ', $string);
        $string = \str_replace("\u{2008}", ' ', $string);
        $string = \str_replace("\u{2009}", ' ', $string);
        $string = \str_replace("\u{200A}", ' ', $string);

        // Narrow non-break space
        $string = \str_replace("\u{202F}", ' ', $string);

        // Medium mathematical space
        $string = \str_replace("\u{205F}", ' ', $string);

        // Ideographic space
        $string = \str_replace("\u{3000}", ' ', $string);

        return $string;
    }
}
