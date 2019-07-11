<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\Common\RegexExceptionTrait;
use Squirrel\Strings\StringFilterInterface;

/**
 * Remove URLs from a string
 *
 * Originally added to be able to analyze texts and detect the language, where URLs would only
 * confuse a language detection algorithm, so removing anything that looks like an URL from
 * a string should lead to "just text" or at least more analyzable text
 *
 * It removes anything with a valid looking scheme, followed by "://", followed by
 * zero or more non-space characters, like:
 *
 * - http://www.domain.com
 * - something://localhost
 * - some:complicated:and:probably:invalid:scheme://whatever/comes/here
 * - a://a
 * - 1://first-character-of-scheme-must-be-an-ASCII-letter-in-RFC-but-we-accept-it-anyway
 * - http://
 *
 * It does not remove "partial" or relative URLs:
 *
 * - ://www.domain.com
 * - //www.domain.com
 * - domain.com/path/more
 *
 * So in general, this filter will probably remove too many strings looking like URLs, but cannot remove mere
 * domains + paths, as they are hard to differentiate from "normal text"
 */
class RemoveURLsFilter implements StringFilterInterface
{
    use RegexExceptionTrait;

    public function filter(string $string): string
    {
        $string = \preg_replace('#[a-zA-Z0-9.+:-]+[:][/][/][^\s]*#', '', $string);

        // @codeCoverageIgnoreStart
        if ($string === null) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        return $string;
    }
}
