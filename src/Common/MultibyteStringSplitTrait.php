<?php

namespace Squirrel\Strings\Common;

/**
 * Add multibyte str_split functionality interally for a class
 */
trait MultibyteStringSplitTrait
{
    use RegexExceptionTrait;

    /**
     * Unicode version of str_split, from http://php.net/manual/en/function.str-split.php comments
     *
     * @param string $string
     * @return array
     */
    protected function mbStringSplit($string)
    {
        $string = \preg_split("//u", $string, -1, PREG_SPLIT_NO_EMPTY);

        // @codeCoverageIgnoreStart
        if ($string === false) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        return $string;
    }
}
