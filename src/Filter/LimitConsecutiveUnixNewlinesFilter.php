<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\Common\RegexExceptionTrait;
use Squirrel\Strings\StringFilterInterface;

/**
 * Make sure there aren't more than a given number of consecutive newlines
 */
class LimitConsecutiveUnixNewlinesFilter implements StringFilterInterface
{
    use RegexExceptionTrait;

    /**
     * @var int Maximum number of consecutive newlines
     */
    private int $maxConsecutiveNewlines;

    public function __construct(int $maxConsecutiveNewlines = 2)
    {
        $this->maxConsecutiveNewlines = $maxConsecutiveNewlines;
    }

    public function filter(string $string): string
    {
        $string = \preg_replace("/(\n{" . $this->maxConsecutiveNewlines . "})\n+/", '$1', $string);

        // @codeCoverageIgnoreStart
        if ($string === null) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        return $string;
    }
}
