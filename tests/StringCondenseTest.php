<?php

namespace Squirrel\Strings\Tests;

use Squirrel\Strings\Condense\ConverterAscii;
use Squirrel\Strings\Condense\ConverterUnicode;
use Squirrel\Strings\Exception\InvalidValueException;

/**
 * Test our two condenser classes - one for ASCII, one for Unicode
 */
class StringCondenseTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test ASCII converter/condenser
     */
    public function testAscii(): void
    {
        // Charsets can be anything - just use some random examples
        $charsets = [
            'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',
            'abcdefghijklmnopqrstuvwxyz0123456789',
            'ghijklmnopqrstuvwxyz01234',
            '0123456789',
        ];

        // Test numbers
        $numbers = [
            0,
            34,
            8349534934,
            1337,
            30000,
            777,
        ];

        // Go through test-charsets
        foreach ($charsets as $charset) {
            $converter = new ConverterAscii($charset);

            foreach ($numbers as $number) {
                // Convert number to string and back and check that we still have the same value
                $this->assertEquals($number, $converter->toNumber($converter->toString($number)));
            }
        }
    }

    /**
     * Test Unicode converter/condenser
     */
    public function testUnicode(): void
    {
        // Charsets can be anything - just use some random examples
        $charsets = [
            'äöüçâêŷ⏲',
            'abcdefghijklmnopqrstuvwxyz0123456789öäⓕ',
            'ghijklmnopqrstuvwxyz01234❤',
            '0123456789☢',
        ];

        // Test numbers
        $numbers = [
            0,
            34,
            8349534934,
            1337,
            30000,
            777,
        ];

        // Go through test-charsets
        foreach ($charsets as $charset) {
            $converter = new ConverterUnicode($charset);

            foreach ($numbers as $number) {
                // Convert number to string and back and check that we still have the same value
                $this->assertEquals($number, $converter->toNumber($converter->toString($number)));
            }
        }
    }

    public function testNoCharset(): void
    {
        $this->expectException(InvalidValueException::class);

        new ConverterAscii('');
    }

    /**
     * One character cannot appear multiple times: A appears twice in this test
     */
    public function testInvalidCharset(): void
    {
        $this->expectException(InvalidValueException::class);

        new ConverterAscii('ABCDEFGA');
    }

    /**
     * Too long a string
     */
    public function testUnicodeTooLong(): void
    {
        $this->expectException(InvalidValueException::class);

        $converter = new ConverterUnicode();
        $converter->toNumber('12345678901234567');
    }
}
