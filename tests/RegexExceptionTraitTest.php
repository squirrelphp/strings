<?php

namespace Squirrel\Strings\Tests;

use Squirrel\Strings\Common\RegexExceptionTrait;
use Squirrel\Strings\Exception\RegexException;

class RegexExceptionTraitTest extends \PHPUnit\Framework\TestCase
{
    use RegexExceptionTrait;

    public function testRegexException(): void
    {
        $this->expectException(RegexException::class);

        $result = \preg_replace('/d/u', '', \utf8_decode('öööö'));

        if ($result === null) {
            throw $this->generateRegexException();
        }
    }
}
