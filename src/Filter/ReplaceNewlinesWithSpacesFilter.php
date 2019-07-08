<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\Common\RegexExceptionTrait;
use Squirrel\Strings\StringFilterInterface;

/**
 * Replace newlines with regular spaces
 */
class ReplaceNewlinesWithSpacesFilter implements StringFilterInterface
{
    use RegexExceptionTrait;

    public function filter(string $string): string
    {
        $string = \preg_replace("/(\015\012)|(\015)|(\012)/", ' ', $string);

        // @codeCoverageIgnoreStart
        if ($string === null) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        return $string;
    }
}
