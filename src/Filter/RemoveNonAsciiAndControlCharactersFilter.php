<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\Common\RegexExceptionTrait;
use Squirrel\Strings\StringFilterInterface;

/**
 * Remove any non-ASCII characters and any control characters, only allowing:
 *
 * - Space         (code 32)
 * - !"#$'()*+,-./ (code 33 to 47)
 * - 0-9           (code 48 to 57)
 * - :;<=>?@       (code 58 to 64)
 * - A-Z           (code 65 to 90)
 * - [\]^_`        (code 91 to 96)
 * - a-z           (code 97 to 122)
 * - {|}~          (code 123 to 126)
 *
 * Extended ASCII codes are all removed, as are the codes 0 to 31 and 127. All
 * characters are treated as bytes, so any unicode characters are removed too,
 * as they never use the ASCII range of 0-127, they always use byte ranges of
 * above 127 (to be backwards compatible with ASCII codes)
 */
class RemoveNonAsciiAndControlCharactersFilter implements StringFilterInterface
{
    use RegexExceptionTrait;

    public function filter(string $string): string
    {
        $string = \preg_replace('/[^\x20-\x7E]/', '', $string);

        // @codeCoverageIgnoreStart
        if ($string === null) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        return $string;
    }
}
