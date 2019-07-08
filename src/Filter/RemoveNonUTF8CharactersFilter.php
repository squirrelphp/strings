<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\Common\RegexExceptionTrait;
use Squirrel\Strings\StringFilterInterface;

/**
 * Makes sure a string only contains valid UTF8 characters - everything else is removed
 */
class RemoveNonUTF8CharactersFilter implements StringFilterInterface
{
    use RegexExceptionTrait;

    public function filter(string $string): string
    {
        // If the text is not completely valid UTF8, we remove all non-UTF8-parts for security reasons
        if (!\mb_check_encoding($string, 'UTF-8')) {
            // Regex to remove all non-UTF8 characters by capturing all valid UTF8 sequences in group one
            // and removing all other found bytes
            $regex = <<<'END'
/
  (
    (?: [\x00-\x7F]                 # single-byte sequences   0xxxxxxx
    |   [\xC0-\xDF][\x80-\xBF]      # double-byte sequences   110xxxxx 10xxxxxx
    |   [\xE0-\xEF][\x80-\xBF]{2}   # triple-byte sequences   1110xxxx 10xxxxxx * 2
    |   [\xF0-\xF7][\x80-\xBF]{3}   # quadruple-byte sequence 11110xxx 10xxxxxx * 3
    ){1,100}                        # ...one or more times
  )
| .                                 # anything else
/x
END;
            $string = \preg_replace($regex, '$1', $string);

            // @codeCoverageIgnoreStart
            if ($string === null) {
                throw $this->generateRegexException();
            }
            // @codeCoverageIgnoreEnd
        }

        return $string;
    }
}
