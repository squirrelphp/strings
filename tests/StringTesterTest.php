<?php

namespace Squirrel\Strings\Tests;

use Squirrel\Strings\Tester\ValidDateTimeTester;
use Squirrel\Strings\Tester\ValidUTF8Tester;

class StringTesterTest extends \PHPUnit\Framework\TestCase
{
    public function testValidDateTime(): void
    {
        $format = 'Y-m-d';

        $tester = new ValidDateTimeTester('Y-m-d');

        $testCases = [
            '2017-01-01' => true,
            '2017-1-1' => false,
            '17-01-01' => false,
            '2017/01/01' => false,
            '2017-02-30' => false,
            'illegal' => false,
            '' => false,
            0 => false,
            '2016-02-29' => true,
            '2015-02-29' => false,
            '2016-2-29' => false,
        ];

        foreach ($testCases as $string => $result) {
            $this->assertEquals($result, $tester->test($string));
        }
    }

    public function testValidUTF8(): void
    {
        $tester = new ValidUTF8Tester();

        $testCases = [
            'höflich' => true,
            '9879837423' => true,
            '' => true,
            \mb_convert_encoding('hören', 'ISO-8859-1', 'UTF-8') => false,
            \mb_convert_encoding('hallo', 'ISO-8859-1', 'UTF-8') => true,
        ];

        foreach ($testCases as $string => $result) {
            $this->assertEquals($result, $tester->test($string));
        }
    }
}
