<?php

namespace Squirrel\Strings\Tests;

use Squirrel\Strings\Debug;
use Squirrel\Strings\Exception\StringException;
use Squirrel\Strings\Tests\ExceptionTestClasses\NoExceptionClass;
use Squirrel\Strings\Tests\ExceptionTestClasses\SomeClass;

class DebugTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateException()
    {
        $someRepository = new SomeClass();

        try {
            $someRepository->someFunction();

            $this->assertFalse(true);
        } catch (StringException $e) {
            $this->assertEquals('Something went wrong!', $e->getMessage());
            $this->assertEquals(__FILE__, $e->getOriginFile());
            $this->assertEquals(__LINE__-6, $e->getOriginLine());
            $this->assertEquals('SomeClass->someFunction()', $e->getOriginCall());
        }
    }

    public function testInvalidExceptionClass()
    {
        $exception = Debug::createException(NoExceptionClass::class, [], 'Something went wrong!');

        $this->assertEquals(\Exception::class, \get_class($exception));
    }

    public function testBaseExceptionClass()
    {
        $exception = Debug::createException(StringException::class, [], 'Something went wrong!');

        $this->assertEquals(StringException::class, \get_class($exception));
    }

    public function testBinaryData()
    {
        $sanitizedData = Debug::sanitizeData(\md5('dada', true));

        $this->assertEquals('0x' . \bin2hex(\md5('dada', true)), $sanitizedData);
    }

    public function testBoolDataTrue()
    {
        $sanitizedData = Debug::sanitizeData(true);

        $this->assertEquals('true', $sanitizedData);
    }

    public function testBoolDataFalse()
    {
        $sanitizedData = Debug::sanitizeData(false);

        $this->assertEquals('false', $sanitizedData);
    }

    public function testObjectData()
    {
        $sanitizedData = Debug::sanitizeData(new SomeClass());

        $this->assertEquals('object(' . SomeClass::class . ')', $sanitizedData);
    }

    public function testArrayData()
    {
        $sanitizedData = Debug::sanitizeData([
            'dada',
            'mumu' => 'haha',
            5444,
            [
                'ohno' => 'yes',
                'maybe',
            ],
        ]);

        $this->assertEquals("[0 => 'dada', 'mumu' => 'haha', 1 => 5444, 2 => ['ohno' => 'yes', 0 => 'maybe']]", $sanitizedData);
    }

    public function testResourceData()
    {
        $sanitizedData = Debug::sanitizeData(\fopen("php://memory", "r"));

        $this->assertEquals("resource(stream)", $sanitizedData);
    }

    public function testSanitizeArguments()
    {
        $sanitizedData = Debug::sanitizeArguments([
            'hello',
            [
                'my',
                'weird' => 'friend',
            ],
            56
        ]);

        $this->assertEquals("'hello', [0 => 'my', 'weird' => 'friend'], 56", $sanitizedData);
    }
}
