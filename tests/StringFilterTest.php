<?php

namespace Squirrel\Strings\Tests;

use Squirrel\Strings\Filter\CamelCaseToSnakeCaseFilter;
use Squirrel\Strings\Filter\DecodeAllHTMLEntitiesFilter;
use Squirrel\Strings\Filter\DecodeBasicHTMLEntitiesFilter;
use Squirrel\Strings\Filter\EncodeBasicHTMLEntitiesFilter;
use Squirrel\Strings\Filter\LimitConsecutiveUnixNewlinesFilter;
use Squirrel\Strings\Filter\LowercaseFilter;
use Squirrel\Strings\Filter\NormalizeLettersToAsciiFilter;
use Squirrel\Strings\Filter\NormalizeToAlphanumericFilter;
use Squirrel\Strings\Filter\RemoveEmailsFilter;
use Squirrel\Strings\Filter\RemoveExcessSpacesFilter;
use Squirrel\Strings\Filter\RemoveHTMLTagsFilter;
use Squirrel\Strings\Filter\RemoveNonAlphanumericFilter;
use Squirrel\Strings\Filter\RemoveNonAsciiAndControlCharactersFilter;
use Squirrel\Strings\Filter\RemoveNonNumericFilter;
use Squirrel\Strings\Filter\RemoveNonUTF8CharactersFilter;
use Squirrel\Strings\Filter\RemoveURLsFilter;
use Squirrel\Strings\Filter\ReplaceNewlinesWithParagraphsAndBreaksFilter;
use Squirrel\Strings\Filter\ReplaceNewlinesWithSpacesFilter;
use Squirrel\Strings\Filter\ReplaceNonAlphanumericFilter;
use Squirrel\Strings\Filter\ReplaceTabsWithSpacesFilter;
use Squirrel\Strings\Filter\ReplaceUnicodeWhitespacesFilter;
use Squirrel\Strings\Filter\SnakeCaseToCamelCaseFilter;
use Squirrel\Strings\Filter\StreamlineInputNoNewlinesFilter;
use Squirrel\Strings\Filter\StreamlineInputWithNewlinesFilter;
use Squirrel\Strings\Filter\TrimFilter;
use Squirrel\Strings\Filter\UppercaseFilter;
use Squirrel\Strings\Filter\UppercaseFirstCharacterFilter;
use Squirrel\Strings\Filter\UppercaseWordsFirstCharacterFilter;
use Squirrel\Strings\Filter\WrapLongWordsNoHTMLFilter;
use Squirrel\Strings\Filter\WrapLongWordsWithHTMLFilter;

/**
 * Test all string filters with a string where all special cases are contained
 */
class StringFilterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * A string with:
     *
     * - HTML Entities (&amp; and &nbsp;)
     * - Excess spaces
     * - HTML tags, valid ones and invalid ones
     * - Many newlines
     * - One tab
     * - One windows newline (\r\n)
     * - A triple newlines
     * - A unicode whitespace
     * - Special characters (üö!)
     *
     * @var string
     */
    private $testString = "  &amp; haha <strong>many</strong> \xc2\xa0  &nbsp;  grüss götter   \r\n\n\n\t  \n  \n  <invalid>\"l'etat\"\\ thing contained!!!&trade; ";

    public function testDecodeAllHTMLEntities()
    {
        $this->assertEquals("  & haha <strong>many</strong> \xc2\xa0  \xc2\xa0  grüss götter   \r\n\n\n\t  \n  \n  <invalid>\"l'etat\"\\ thing contained!!!™ ", (new DecodeAllHTMLEntitiesFilter())->filter($this->testString));
    }

    public function testDecodeBasicHTMLEntities()
    {
        $this->assertEquals("  & haha <strong>many</strong> \xc2\xa0  \xc2\xa0  grüss götter   \r\n\n\n\t  \n  \n  <invalid>\"l'etat\"\\ thing contained!!!&trade; ", (new DecodeBasicHTMLEntitiesFilter())->filter($this->testString));
    }

    public function testRemoveExcessSpaces()
    {
        $this->assertEquals("&amp; haha <strong>many</strong> \xc2\xa0 &nbsp; grüss götter \r\n\n\n\t\n\n<invalid>\"l'etat\"\\ thing contained!!!&trade;", (new RemoveExcessSpacesFilter())->filter($this->testString));
    }

    public function testRemoveHTML()
    {
        $this->assertEquals("  &amp; haha many \xc2\xa0  &nbsp;  grüss götter   \r\n\n\n\t  \n  \n  \"l'etat\"\\ thing contained!!!&trade; ", (new RemoveHTMLTagsFilter())->filter($this->testString));
    }

    public function testReplaceNewlinesWithSpaces()
    {
        $this->assertEquals("  &amp; haha <strong>many</strong> \xc2\xa0  &nbsp;  grüss götter      \t        <invalid>\"l'etat\"\\ thing contained!!!&trade; ", (new ReplaceNewlinesWithSpacesFilter())->filter($this->testString));
    }

    public function testReplaceTabsWithSpaces()
    {
        $this->assertEquals("  &amp; haha <strong>many</strong> \xc2\xa0  &nbsp;  grüss götter   \r\n\n\n   \n  \n  <invalid>\"l'etat\"\\ thing contained!!!&trade; ", (new ReplaceTabsWithSpacesFilter())->filter($this->testString));
    }

    public function testLimitConsecutiveUnixNewlinesFilter()
    {
        $this->assertEquals("  &amp; haha <strong>many</strong> \xc2\xa0  &nbsp;  grüss götter   \r\n\n\t  \n  \n  <invalid>\"l'etat\"\\ thing contained!!!&trade; ", (new LimitConsecutiveUnixNewlinesFilter())->filter($this->testString));
    }

    public function testReplaceUnicodeWhitespaces()
    {
        $this->assertEquals("  &amp; haha <strong>many</strong>    &nbsp;  grüss götter   \r\n\n\n\t  \n  \n  <invalid>\"l'etat\"\\ thing contained!!!&trade; ", (new ReplaceUnicodeWhitespacesFilter())->filter($this->testString));
    }

    public function testLowercase()
    {
        $this->assertEquals(mb_strtolower($this->testString), (new LowercaseFilter())->filter($this->testString));
    }

    public function testUppercase()
    {
        $this->assertEquals(mb_strtoupper($this->testString), (new UppercaseFilter())->filter($this->testString));
    }

    public function testUppercaseWordsFirstCharacter()
    {
        $this->assertEquals(ucwords($this->testString), (new UppercaseWordsFirstCharacterFilter())->filter($this->testString));
    }

    public function testUppercaseFirstCharacter()
    {
        $this->assertEquals(ucfirst($this->testString), (new UppercaseFirstCharacterFilter())->filter($this->testString));
    }

    public function testTrim()
    {
        $this->assertEquals("&amp; haha <strong>many</strong> \xc2\xa0  &nbsp;  grüss götter   \r\n\n\n\t  \n  \n  <invalid>\"l'etat\"\\ thing contained!!!&trade;", (new TrimFilter())->filter($this->testString));
    }

    public function testRemoveNonUTF8Characters()
    {
        $nonUTF8 = utf8_decode($this->testString);

        $this->assertEquals("  &amp; haha <strong>many</strong>   &nbsp;  grss gtter   \r\n\n\n\t  \n  \n  <invalid>\"l'etat\"\\ thing contained!!!&trade; ", (new RemoveNonUTF8CharactersFilter())->filter($nonUTF8));
    }

    public function testWrapLongWordsNoHTML()
    {
        $string = '123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890';

        $this->assertEquals('123456789012345678901234567890 123456789012345678901234567890 123456789012345678901234567890', (new WrapLongWordsNoHTMLFilter(30))->filter($string));

        $this->assertEquals('1234567890123456789012345678901234567890123456789012345678901234567890 12345678901234567890', (new WrapLongWordsNoHTMLFilter(70))->filter($string));
    }

    public function testWrapLongWordsWithHTML()
    {
        $string = '123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890';

        $this->assertEquals('123456789012345678901234567890 123456789012345678901234567890 123456789012345678901234567890', (new WrapLongWordsWithHTMLFilter(30))->filter($string));

        $this->assertEquals('1234567890123456789012345678901234567890123456789012345678901234567890 12345678901234567890', (new WrapLongWordsWithHTMLFilter(70))->filter($string));

        $string = '<strong>1234567890123456789012345678901234567890</strong>';

        /*
         * We separate after 29 numbers because each HTML tag counts as ONE character, so
         * HTML tag + 29 numbers = 30 characters
         */
        $this->assertEquals('<strong>12345678901234567890123456789 01234567890</strong>', (new WrapLongWordsWithHTMLFilter(30))->filter($string));

        $string = '<strong> 1234567890123456789012345678901234567890 </strong>';

        $this->assertEquals('<strong> 123456789012345678901234567890 1234567890 </strong>', (new WrapLongWordsWithHTMLFilter(30))->filter($string));
    }

    public function testRemoveNonAlphanumeric()
    {
        $this->assertEquals('amphahastrongmanystrongnbspgrssgtterinvalidletatthingcontainedtrade', (new RemoveNonAlphanumericFilter())->filter($this->testString));
    }

    public function testRemoveNonNumeric()
    {
        $this->assertEquals('4513', (new RemoveNonNumericFilter())->filter('hallö 45 dadaismus! <huhu> 1/3'));
    }

    public function testReplaceNonAlphanumeric()
    {
        $this->assertEquals('-amp-haha-strong-many-strong-nbsp-gr-ss-g-tter-invalid-l-etat-thing-contained-trade-', (new ReplaceNonAlphanumericFilter())->filter($this->testString));
    }

    public function testUrlFriendly()
    {
        $string = $this->testString;
        $string = (new DecodeBasicHTMLEntitiesFilter())->filter($string);
        $string = (new NormalizeLettersToAsciiFilter())->filter($string);
        $string = (new ReplaceNonAlphanumericFilter())->filter($string);
        $string = (new TrimFilter('-'))->filter($string);

        $this->assertEquals('haha-strong-many-strong-gruss-gotter-invalid-l-etat-thing-contained-trade', $string);
    }

    public function testRemoveNonAsciiAndControlCharacters()
    {
        $this->assertEquals('  &amp; haha <strong>many</strong>   &nbsp;  grss gtter         <invalid>"l\'etat"\\ thing contained!!!&trade; ', (new RemoveNonAsciiAndControlCharactersFilter())->filter($this->testString));
    }

    public function testDecodeBasicHTMLEntitiesAndStreamlineInputWithNewlines()
    {
        $string = $this->testString;
        $string = (new DecodeBasicHTMLEntitiesFilter())->filter($string);
        $string = (new StreamlineInputWithNewlinesFilter())->filter($string);

        $this->assertEquals("& haha <strong>many</strong> grüss götter\n\n<invalid>\"l'etat\"\\ thing contained!!!&trade;", $string);
    }

    public function testStreamlineInputNoNewlines()
    {
        $string = $this->testString;
        $string = (new StreamlineInputNoNewlinesFilter())->filter($string);

        $this->assertEquals("&amp; haha <strong>many</strong> &nbsp; grüss götter <invalid>\"l'etat\"\\ thing contained!!!&trade;", $string);
    }

    public function testReplaceNewlinesWithParagraphsAndBreaks()
    {
        $testString = "Dearest\n\nPlease accept my apology for:\n- Making fun of you\n- Making you sad\n\nBest regards\n\nMichael";

        $string = (new ReplaceNewlinesWithParagraphsAndBreaksFilter())->filter($testString);

        $this->assertEquals("<p>Dearest</p><p>Please accept my apology for:<br/>- Making fun of you<br/>- Making you sad</p><p>Best regards</p><p>Michael</p>", $string);
    }

    public function testEncodeBasicHTMLEntities()
    {
        $string = (new EncodeBasicHTMLEntitiesFilter())->filter($this->testString);

        $this->assertEquals("  &amp;amp; haha &lt;strong&gt;many&lt;/strong&gt; &nbsp;  &amp;nbsp;  grüss götter   \r\n\n\n\t  \n  \n  &lt;invalid&gt;&quot;l&apos;etat&quot;\\ thing contained!!!&amp;trade; ", $string);
    }

    public function testNormalizeLettersToAscii()
    {
        $string = (new NormalizeLettersToAsciiFilter())->filter($this->testString);

        $this->assertEquals("  &amp; haha <strong>many</strong> \xc2\xa0  &nbsp;  gruss gotter   \r\n\n\n\t  \n  \n  <invalid>\"l'etat\"\\ thing contained!!!&trade; ", $string);
    }

    public function testSnakeCaseToCamelCase()
    {
        $tests = array(
            'simple_test' => 'simpleTest',
            'easy' => 'easy',
            'HTML' => 'html',
            'very_long_name_WITH_DIffErEnT_Problems' => 'veryLongNameWithDifferentProblems',
        );

        foreach ($tests as $testString => $expectedResult) {
            $string = (new SnakeCaseToCamelCaseFilter())->filter($testString);

            $this->assertEquals($expectedResult, $string);
        }
    }

    public function testCamelCaseToSnakeCase()
    {
        $tests = array(
            'simpleTest' => 'simple_test',
            'easy' => 'easy',
            'HTML' => 'html',
            'simpleXML' => 'simple_xml',
            'PDFLoad' => 'pdf_load',
            'startMIDDLELast' => 'start_middle_last',
            'AString' => 'a_string',
            'Some4Numbers234' => 'some4_numbers234',
            'TEST123String' => 'test123_string',
        );

        foreach ($tests as $testString => $expectedResult) {
            $string = (new CamelCaseToSnakeCaseFilter())->filter($testString);

            $this->assertEquals($expectedResult, $string);
        }
    }

    public function testRemoveEmails()
    {
        $tests = array(
            'hello my dear, have you written to hans@domain.com?' => 'hello my dear, have you written to ',
            'merry@localhost has sent you a new message!' => ' has sent you a new message!',
            'A new email was received from complicated.domain-name@many.subdomains.in.this.email today at noon!' => 'A new email was received from  today at noon!',
            'Test partial emails with partial@ which should not be removed' => 'Test partial emails with partial@ which should not be removed',
            'Test other partial emails with @example.com which should not be removed' => 'Test other partial emails with @example.com which should not be removed',
        );

        $filter = (new RemoveEmailsFilter());

        foreach ($tests as $testString => $expectedResult) {
            $string = $filter->filter($testString);

            $this->assertEquals($expectedResult, $string);
        }
    }

    public function testRemoveURLs()
    {
        $tests = array(
            'hello my dear, look at this link: https://www.domain.com/a/long/path/34234/more?space=true&more=1 should be interesting!' => 'hello my dear, look at this link:  should be interesting!',
            'ftp://link.to/file.jpg' => '',
            'Some devices have weird links with custom scheme, like weirdapp:http://weirddomain.de' => 'Some devices have weird links with custom scheme, like ',
            'If no :// is contained we do not remove an URL, like domain.com/path/example should remain' => 'If no :// is contained we do not remove an URL, like domain.com/path/example should remain',
            'A scheme like 3048534://domain.com is not valid, yet will still be removed, as is a partial URL like http:// just to remove as much as possible' => 'A scheme like  is not valid, yet will still be removed, as is a partial URL like  just to remove as much as possible',
        );

        $filter = (new RemoveURLsFilter());

        foreach ($tests as $testString => $expectedResult) {
            $string = $filter->filter($testString);

            $this->assertEquals($expectedResult, $string);
        }
    }

    public function testNormalizeToAlphanumeric()
    {
        $tests = array(
            ' sœmoéhaha ööhm ûlty! ' => 'soemoehahaoohmulty',
            ' éßen ĜħŞŽŷť ƇƚƲǊǗ! ' => 'essenGhSZytClVNJU',
        );

        $filter = (new NormalizeToAlphanumericFilter());

        foreach ($tests as $testString => $expectedResult) {
            $string = $filter->filter($testString);

            $this->assertEquals($expectedResult, $string);
        }
    }
}
