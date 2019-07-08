<?php

namespace Squirrel\Strings\Tests;

use Squirrel\Strings\Exception\InvalidValueException;
use Squirrel\Strings\Random\GeneratorAscii;
use Squirrel\Strings\Random\GeneratorUnicode;

class RandomStringTest extends \PHPUnit\Framework\TestCase
{
    public function testNoCharactersAscii()
    {
        $this->expectException(InvalidValueException::class);

        new GeneratorAscii('');
    }

    public function testNoCharactersUnicode()
    {
        $this->expectException(InvalidValueException::class);

        new GeneratorUnicode('');
    }

    public function testNoLengthAscii()
    {
        $this->expectException(InvalidValueException::class);

        $generator = new GeneratorAscii('12');
        $generator->generate(0);
    }

    public function testNoLengthUnicode()
    {
        $this->expectException(InvalidValueException::class);

        $generator = new GeneratorUnicode('üä');
        $generator->generate(0);
    }

    public function testAsciiCharacters()
    {
        $generator = new GeneratorAscii('18');

        $this->assertSame(0, \preg_match('/[^18]/', $generator->generate(900)));
    }

    public function testUnicodeCharacters()
    {
        $generator = new GeneratorUnicode('éç');

        $this->assertSame(0, \preg_match('/[^éç]/u', $generator->generate(900)));
    }
}
