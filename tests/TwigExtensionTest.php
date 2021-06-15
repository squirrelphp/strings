<?php

namespace Squirrel\Strings\Tests;

use Squirrel\Strings\Exception\InvalidValueException;
use Squirrel\Strings\Filter\LowercaseFilter;
use Squirrel\Strings\Filter\TrimFilter;
use Squirrel\Strings\Random\GeneratorAscii;
use Squirrel\Strings\RandomStringGeneratorSelector;
use Squirrel\Strings\StringFilterSelector;
use Squirrel\Strings\Twig\StringExtension;

class TwigExtensionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var StringExtension
     */
    private $stringExtension;

    protected function setUp(): void
    {
        $filterManager = new StringFilterSelector([
            'Lowercase' => new LowercaseFilter(),
            'Trim' => new TrimFilter(),
        ]);

        $randomManager = new RandomStringGeneratorSelector([
            'one' => new GeneratorAscii('346789'),
            'eighteen' => new GeneratorAscii('18'),
        ]);

        $this->stringExtension = new StringExtension($filterManager, $randomManager);
    }

    public function testFilters(): void
    {
        $this->assertEquals('string_filter', ($this->stringExtension->getFilters()[0]->getName()));
        $this->assertEquals('haha', ($this->stringExtension->getFilters()[0]->getCallable())('HAHA', 'Lowercase'));
    }

    public function testFunctionsFirst(): void
    {
        $this->assertEquals('string_filter', ($this->stringExtension->getFunctions()[0]->getName()));
        $this->assertEquals('haha', ($this->stringExtension->getFunctions()[0]->getCallable())('  HAHA ' . "\n\t", ['Lowercase','Trim']));
    }

    public function testFunctionsSecond(): void
    {
        $this->assertEquals('string_random', ($this->stringExtension->getFunctions()[1]->getName()));
        $this->assertEquals(0, \preg_match('/[^18]/', ($this->stringExtension->getFunctions()[1]->getCallable())('eighteen', 900)));
    }

    public function testInvalidFilters(): void
    {
        $this->expectException(InvalidValueException::class);

        ($this->stringExtension->getFunctions()[0]->getCallable())('  HAHA ' . "\n\t", 5);
    }
}
