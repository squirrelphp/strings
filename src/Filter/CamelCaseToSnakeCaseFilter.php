<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\Common\RegexExceptionTrait;
use Squirrel\Strings\StringFilterInterface;

/**
 * Convert camel case string (like myNiceVariable) to snake case (my_nice_variable)
 */
class CamelCaseToSnakeCaseFilter implements StringFilterInterface
{
    use RegexExceptionTrait;

    public function filter(string $string): string
    {
        $string = \preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $string);

        // @codeCoverageIgnoreStart
        if ($string === null) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        return \mb_strtolower($string);
    }
}
