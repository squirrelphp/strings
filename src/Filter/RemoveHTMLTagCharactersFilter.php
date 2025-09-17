<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\Common\RegexExceptionTrait;
use Squirrel\Strings\StringFilterInterface;

/**
 * Remove the HTML tag characters: < and > and "
 */
class RemoveHTMLTagCharactersFilter implements StringFilterInterface
{
    use RegexExceptionTrait;

    public function filter(string $string): string
    {
        $string = \str_replace('<', '', $string);
        $string = \str_replace('>', '', $string);
        $string = \str_replace('"', '', $string);

        return $string;
    }
}
