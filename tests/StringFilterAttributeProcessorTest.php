<?php

namespace Squirrel\Strings\Tests;

use Squirrel\Strings\Attribute\StringFilter;
use Squirrel\Strings\Attribute\StringFilterProcessor;
use Squirrel\Strings\Exception\InvalidValueException;
use Squirrel\Strings\Filter\LowercaseFilter;
use Squirrel\Strings\Filter\TrimFilter;
use Squirrel\Strings\StringFilterSelector;
use Squirrel\Strings\Tests\TestClasses\ClassWithInvalidAttribute;
use Squirrel\Strings\Tests\TestClasses\ClassWithInvalidProperty;
use Squirrel\Strings\Tests\TestClasses\ClassWithPrivateProperties;
use Squirrel\Strings\Tests\TestClasses\ClassWithPublicProperties;

class StringFilterAttributeProcessorTest extends \PHPUnit\Framework\TestCase
{
    private StringFilterProcessor $processor;

    protected function setUp(): void
    {
        parent::setUp();

        // String filters available
        $manager = new StringFilterSelector([
            'Lowercase' => new LowercaseFilter(),
            'Trim' => new TrimFilter(),
        ]);

        // Processor of StringFilter annotations
        $this->processor = new StringFilterProcessor($manager);
    }

    public function testPublicProperties(): void
    {
        $testClass = new ClassWithPublicProperties();
        $testClass->title = '  HaHa ' . "\n";
        $testClass->text = '  HiHiiii Ho ' . "\n";

        $this->processor->process($testClass);

        $this->assertEquals('haha', $testClass->title);
        $this->assertEquals('HiHiiii Ho', $testClass->text);
    }

    public function testPublicPropertiesArray(): void
    {
        $testClass = new ClassWithPublicProperties();
        $testClass->title = ['  HaHa ' . "\n", '   hOOOOO '];

        $this->processor->process($testClass);

        $this->assertEquals(['haha', 'hooooo'], $testClass->title);
        $this->assertEquals('', $testClass->text);
    }

    public function testPrivateProperties(): void
    {
        $testClass = new ClassWithPrivateProperties();
        $testClass->setTitle('  HaHa ' . " \n \t ");

        $this->processor->process($testClass);

        $this->assertEquals('haha', $testClass->getTitle());
        $this->assertEquals('', $testClass->getText());
    }

    public function testInvalidValue(): void
    {
        $this->expectException(InvalidValueException::class);

        $testClass = new ClassWithPublicProperties();
        $testClass->title = new \stdClass(); // object values are not allowed

        $this->processor->process($testClass);
    }

    public function testInvalidArrayValue(): void
    {
        $this->expectException(InvalidValueException::class);

        $testClass = new ClassWithPublicProperties();
        $testClass->title = [false]; // boolean values are not allowed

        $this->processor->process($testClass);
    }

    public function testInvalidAnnotation(): void
    {
        $this->expectException(InvalidValueException::class);

        $testClass = new ClassWithInvalidAttribute();

        $this->processor->process($testClass);
    }

    public function testInvalidProperty(): void
    {
        $this->expectException(InvalidValueException::class);

        $testClass = new ClassWithInvalidProperty();

        $this->processor->process($testClass);
    }

    public function testStringFilterWithStrings(): void
    {
        $stringFilter = new StringFilter('Trim', 'Lowercase', 'Dada');

        $this->assertEquals(['Trim', 'Lowercase', 'Dada'], $stringFilter->getNames());
    }

    public function testInvalidStringFilter(): void
    {
        $this->expectException(InvalidValueException::class);

        new StringFilter();
    }
}
