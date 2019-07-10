<?php

namespace Squirrel\Strings\Tests;

use Squirrel\Strings\Exception\InvalidValueException;
use Squirrel\Strings\Random\GeneratorAscii;
use Squirrel\Strings\Random\GeneratorUnicode;
use Squirrel\Strings\RandomStringGeneratorSelector;

class RandomStringGeneratorManagerTest extends \PHPUnit\Framework\TestCase
{
    public function testRegular()
    {
        $generators = [
            'one' => new GeneratorAscii('346789'),
            'two' => new GeneratorUnicode('346789'),
        ];

        $manager = new RandomStringGeneratorSelector($generators);

        $this->assertSame($generators['one'], $manager->getGenerator('one'));
        $this->assertSame($generators['two'], $manager->getGenerator('two'));
    }

    public function testNotFound()
    {
        $this->expectException(InvalidValueException::class);

        $manager = new RandomStringGeneratorSelector([]);
        $manager->getGenerator('dada');
    }
}
