<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\Common\RegexExceptionTrait;
use Squirrel\Strings\StringFilterInterface;

/**
 * Normalize newlines to unix style (\n)
 */
class NormalizeNewlinesToUnixStyleFilter implements StringFilterInterface
{
    use RegexExceptionTrait;

    public function filter(string $string): string
    {
        // Convert \r\n , \r and \n all to \n
        $string = \preg_replace("/(\015\012)|(\015)|(\012)/", "\n", $string);

        // @codeCoverageIgnoreStart
        if ($string === null) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        return $string;
    }
}
