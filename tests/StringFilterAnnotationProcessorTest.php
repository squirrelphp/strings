<?php

namespace Squirrel\Strings\Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use Squirrel\Strings\Annotation\StringFilter;
use Squirrel\Strings\Annotation\StringFilterProcessor;
use Squirrel\Strings\Exception\InvalidValueException;
use Squirrel\Strings\Filter\LowercaseFilter;
use Squirrel\Strings\Filter\TrimFilter;
use Squirrel\Strings\StringFilterManager;
use Squirrel\Strings\Tests\TestClasses\ClassWithInvalidAnnotations;
use Squirrel\Strings\Tests\TestClasses\ClassWithPrivateProperties;
use Squirrel\Strings\Tests\TestClasses\ClassWithPublicProperties;

class StringFilterAnnotationProcessorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var StringFilterProcessor
     */
    private $processor;

    protected function setUp(): void
    {
        parent::setUp();

        // Load annotation class, as it is not loaded automatically
        \class_exists(StringFilter::class);

        // Annotation reader, needed to find annotations
        $reader = new AnnotationReader();

        // String filters available
        $manager = new StringFilterManager([
            'Lowercase' => new LowercaseFilter(),
            'Trim' => new TrimFilter(),
        ]);

        // Processor of StringFilter annotations
        $this->processor = new StringFilterProcessor($reader, $manager);
    }

    public function testPublicProperties()
    {
        $testClass = new ClassWithPublicProperties();
        $testClass->title = '  HaHa ' . "\n";
        $testClass->text = '  HiHiiii Ho ' . "\n";

        $this->processor->process($testClass);

        $this->assertEquals('haha', $testClass->title);
        $this->assertEquals('HiHiiii Ho', $testClass->text);
    }

    public function testPublicPropertiesArray()
    {
        $testClass = new ClassWithPublicProperties();
        $testClass->title = ['  HaHa ' . "\n", '   hOOOOO '];

        $this->processor->process($testClass);

        $this->assertEquals(['haha', 'hooooo'], $testClass->title);
        $this->assertEquals('', $testClass->text);
    }

    public function testPrivateProperties()
    {
        $testClass = new ClassWithPrivateProperties();
        $testClass->setTitle('  HaHa ' . " \n \t ");

        $this->processor->process($testClass);

        $this->assertEquals('haha', $testClass->getTitle());
        $this->assertEquals('', $testClass->getText());
    }

    public function testInvalidValue()
    {
        $this->expectException(InvalidValueException::class);

        $testClass = new ClassWithPublicProperties();
        $testClass->title = new \stdClass();

        $this->processor->process($testClass);
    }

    public function testInvalidAnnotation()
    {
        $this->expectException(InvalidValueException::class);

        $testClass = new ClassWithInvalidAnnotations();

        $this->processor->process($testClass);
    }
}
