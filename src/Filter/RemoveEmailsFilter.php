<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\Common\RegexExceptionTrait;
use Squirrel\Strings\StringFilterInterface;

/**
 * Remove emails from a string
 *
 * Originally added to be able to analyze texts and detect the language, where email addresses would only
 * confuse a language detection algorithm, so removing anything that clearly looks like an email from
 * a string should lead to "just text" or at least more analyzable text
 *
 * It removes anything with non-space characters before and after an @ symbol, like:
 *
 * - example@example.com
 * - localhost@localhost
 * - some.name@some.complicated.domain.name
 * - name@host.com? (it is greedy and matches until a space is found)
 * - a@a
 * - 1@1
 * - ..@!!
 *
 * It does not remove "partial" emails like:
 *
 * - @example.com
 * - localhost@
 *
 * As the intention of the @ symbol in these cases are unclear, it might be a hash tag or some other use
 */
class RemoveEmailsFilter implements StringFilterInterface
{
    use RegexExceptionTrait;

    public function filter(string $string): string
    {
        $string = \preg_replace('/[^@\s]+@[^@\s]+/si', '', $string);

        // @codeCoverageIgnoreStart
        if ($string === null) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        return $string;
    }
}
