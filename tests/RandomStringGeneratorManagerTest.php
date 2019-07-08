<?php

namespace Squirrel\Strings\Tests;

use Squirrel\Strings\Exception\InvalidValueException;
use Squirrel\Strings\Random\GeneratorAscii;
use Squirrel\Strings\Random\GeneratorUnicode;
use Squirrel\Strings\RandomStringGeneratorManager;

class RandomStringGeneratorManagerTest extends \PHPUnit\Framework\TestCase
{
    public function testRegular()
    {
        $generators = [
            'one' => new GeneratorAscii('346789'),
            'two' => new GeneratorUnicode('346789'),
        ];

        $manager = new RandomStringGeneratorManager($generators);

        $this->assertSame($generators['one'], $manager->getGenerator('one'));
        $this->assertSame($generators['two'], $manager->getGenerator('two'));
    }

    public function testNotFound()
    {
        $this->expectException(InvalidValueException::class);

        $manager = new RandomStringGeneratorManager([]);
        $manager->getGenerator('dada');
    }
}
