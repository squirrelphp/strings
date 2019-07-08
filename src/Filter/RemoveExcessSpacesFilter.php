<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\Common\RegexExceptionTrait;
use Squirrel\Strings\StringFilterInterface;

/**
 * Remove unnecessary whitespaces:
 *
 * - Remove all spaces around newlines
 * - Reduce multiple spaces to just one space
 * - Remove spaces at the begining and end of the string (trim)
 */
class RemoveExcessSpacesFilter implements StringFilterInterface
{
    use RegexExceptionTrait;

    public function filter(string $string): string
    {
        // Remove spaces before and after newlines
        $string = \preg_replace("/[ ]*([\n]+)[ ]*/", "\\1", $string);

        // @codeCoverageIgnoreStart
        if ($string === null) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        // Reduce multiple spaces in sequence to just one space
        $string = \preg_replace('/[ ]+/si', ' ', $string);

        // @codeCoverageIgnoreStart
        if ($string === null) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        // Remove spaces at the beginning and the end of the string
        return \trim($string);
    }
}
