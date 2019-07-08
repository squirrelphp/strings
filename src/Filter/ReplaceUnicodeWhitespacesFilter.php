<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\Common\RegexExceptionTrait;
use Squirrel\Strings\StringFilterInterface;

/**
 * Replace unicode whitespaces with regular spaces
 */
class ReplaceUnicodeWhitespacesFilter implements StringFilterInterface
{
    use RegexExceptionTrait;

    public function filter(string $string): string
    {
        // Replace non-breaking unicode whitespaces
        // (\xC2\xA0 or \x{00A0} as a single character) with regular whitespaces
        $string = \preg_replace("/\x{00A0}/u", ' ', $string);

        // @codeCoverageIgnoreStart
        if ($string === null) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        return $string;
    }
}
