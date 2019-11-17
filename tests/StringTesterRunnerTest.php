<?php

namespace Squirrel\Strings\Tests;

use Squirrel\Strings\StringTesterRunner;
use Squirrel\Strings\Tests\TestTesters\ReturnsFalseTester;
use Squirrel\Strings\Tests\TestTesters\ReturnsTrueTester;

class StringTesterRunnerTest extends \PHPUnit\Framework\TestCase
{
    public function testRunner()
    {
        $runner = new StringTesterRunner(
            new ReturnsFalseTester(),
            new ReturnsTrueTester()
        );

        $this->assertEquals(false, $runner->test('somestring'));

        $runner = new StringTesterRunner(
            new ReturnsTrueTester(),
            new ReturnsTrueTester(),
            new ReturnsTrueTester(),
            new ReturnsTrueTester()
        );

        $this->assertEquals(true, $runner->test('somestring'));

        $runner = new StringTesterRunner(
            new ReturnsTrueTester(),
            new ReturnsTrueTester(),
            new ReturnsTrueTester(),
            new ReturnsTrueTester(),
            new ReturnsFalseTester()
        );

        $this->assertEquals(false, $runner->test('somestring'));
    }
}
