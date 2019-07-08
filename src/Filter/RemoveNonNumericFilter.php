<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\Common\RegexExceptionTrait;
use Squirrel\Strings\StringFilterInterface;

/**
 * Remove any characters which are not numeric (0-9)
 */
class RemoveNonNumericFilter implements StringFilterInterface
{
    use RegexExceptionTrait;

    public function filter(string $string): string
    {
        $string = \preg_replace("/[^0-9]/", '', $string);

        // @codeCoverageIgnoreStart
        if ($string === null) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        return $string;
    }
}
