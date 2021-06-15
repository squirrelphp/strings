<?php

namespace Squirrel\Strings\Tests;

use Squirrel\Strings\Exception\UrlException;
use Squirrel\Strings\Url;

/**
 * Test our Url class
 */
class UrlTest extends \PHPUnit\Framework\TestCase
{
    public function testEmptyStart(): void
    {
        // Empty URL
        $urlObj = new Url('');

        // We get back an empty URL
        $this->assertEquals('/', $urlObj->getAbsoluteUrl());

        // Add query string parameter
        $urlObj->add('first', 'yeeeah');

        // Check if it was added
        $this->assertEquals('/?first=yeeeah', $urlObj->getAbsoluteUrl());
    }

    public function testRelativeUrl(): void
    {
        // First URL
        $urlObj = new Url('/');

        // Add redirect URL
        $urlObj->add('redirect_url', 'https://www.example.com?real_nice=1');

        // Check compiled URL
        $this->assertEquals('/?redirect_url=' . urlencode('https://www.example.com?real_nice=1'), $urlObj->getAbsoluteUrl());
        $this->assertEquals(true, $urlObj->has('redirect_url'));
        $this->assertEquals('https://www.example.com?real_nice=1', $urlObj->get('redirect_url'));

        // Remove redirect URL
        $urlObj->remove('redirect_url');

        // Make sure it was removed
        $this->assertEquals('/', $urlObj->getAbsoluteUrl());
        $this->assertEquals(false, $urlObj->has('redirect_url'));
        $this->assertEquals(null, $urlObj->get('redirect_url'));
    }

    public function testAbsoluteUrl(): void
    {
        $urlObj = new Url('https://www.example.com/path/to/victory?first=1&second=2&third=3');

        $this->assertEquals('https://www.example.com/path/to/victory?first=1&second=2&third=3', $urlObj->getAbsoluteUrl());

        // Test individual parts of the Url
        $this->assertEquals('https', $urlObj->getScheme());
        $this->assertEquals('www.example.com', $urlObj->getHost());
        $this->assertEquals('/path/to/victory', $urlObj->getPath());
        $this->assertEquals('first=1&second=2&third=3', $urlObj->getQueryString());
        $this->assertEquals(true, $urlObj->has('first'));
        $this->assertEquals(true, $urlObj->has('second'));
        $this->assertEquals(true, $urlObj->has('third'));
        $this->assertEquals(false, $urlObj->has('fourth'));
        $this->assertEquals('1', $urlObj->get('first'));
        $this->assertEquals('2', $urlObj->get('second'));
        $this->assertEquals('3', $urlObj->get('third'));
        $this->assertEquals(null, $urlObj->get('fourth'));

        // Replace first parameter
        $urlObj->add('first', '939393333');

        // Test Url change
        $this->assertEquals('https://www.example.com/path/to/victory?first=939393333&second=2&third=3', $urlObj->getAbsoluteUrl());
        $this->assertEquals('939393333', $urlObj->get('first'));
        $this->assertEquals('first=939393333&second=2&third=3', $urlObj->getQueryString());

        // Check relative result
        $this->assertEquals('/path/to/victory?first=939393333&second=2&third=3', $urlObj->getRelativeUrl());
    }

    public function testReplacePartsInAbsoluteUrl(): void
    {
        $urlObj = new Url('https://www.example.com/path/to/victory?first=1&second=2&third=3');

        $urlObj->setScheme('http');
        $urlObj->setHost('otherexample.de');
        $urlObj->setPath('/different');

        $this->assertEquals('http://otherexample.de/different?first=1&second=2&third=3', $urlObj->getAbsoluteUrl());
        $this->assertEquals('/different?first=1&second=2&third=3', $urlObj->getRelativeUrl());

        // Test individual parts of the Url
        $this->assertEquals('http', $urlObj->getScheme());
        $this->assertEquals('otherexample.de', $urlObj->getHost());
        $this->assertEquals('/different', $urlObj->getPath());
        $this->assertEquals('first=1&second=2&third=3', $urlObj->getQueryString());
    }

    public function testReplaceQueryString(): void
    {
        $urlObj = new Url('https://www.example.com/path/to/victory?first=1&second=2&third=3');

        $urlObj->setQueryString('other=3&haha=dudu');

        $this->assertEquals('https://www.example.com/path/to/victory?other=3&haha=dudu', $urlObj->getAbsoluteUrl());
        $this->assertEquals('/path/to/victory?other=3&haha=dudu', $urlObj->getRelativeUrl());

        // Test individual parts of the query string
        $this->assertEquals(false, $urlObj->has('first'));
        $this->assertEquals(false, $urlObj->has('second'));
        $this->assertEquals(false, $urlObj->has('third'));
        $this->assertEquals(null, $urlObj->get('first'));
        $this->assertEquals(null, $urlObj->get('second'));
        $this->assertEquals(null, $urlObj->get('third'));
        $this->assertEquals('3', $urlObj->get('other'));
        $this->assertEquals('dudu', $urlObj->get('haha'));
    }

    public function testComplexQueryString(): void
    {
        $urlObj = new Url('https://www.example.com/path/to/victory?first=1&second=2&third=3');

        $urlObj->add('first', '939393333');

        // Add new argument
        $urlObj->add('four', 'yessas');

        // Check result
        $this->assertEquals('/path/to/victory?first=939393333&second=2&third=3&four=yessas', $urlObj->getRelativeUrl());

        // Add numeric array
        $urlObj->add('five', ['sexy', 'hexy']);

        // Check result
        $this->assertEquals('/path/to/victory?first=939393333&second=2&third=3&four=yessas&five' . urlencode('[0]') . '=sexy&five' . urlencode('[1]') . '=hexy', $urlObj->getRelativeUrl());
        $this->assertEquals(true, $urlObj->has('five'));
        $this->assertEquals(['sexy', 'hexy'], $urlObj->get('five'));

        // Add associative array
        $urlObj->add('five', ['heey' => 'sexy', 'nooo' => 'hexy']);

        // Check result
        $this->assertEquals('/path/to/victory?first=939393333&second=2&third=3&four=yessas&five' . urlencode('[heey]') . '=sexy&five' . urlencode('[nooo]') . '=hexy', $urlObj->getRelativeUrl());
        $this->assertEquals(true, $urlObj->has('five'));
        $this->assertEquals(['heey' => 'sexy', 'nooo' => 'hexy'], $urlObj->get('five'));

        // Remove one of the vars (four)
        $urlObj->remove('four');

        // Check result
        $this->assertEquals('/path/to/victory?first=939393333&second=2&third=3&five' . urlencode('[heey]') . '=sexy&five' . urlencode('[nooo]') . '=hexy', $urlObj->getRelativeUrl());
        $this->assertEquals(false, $urlObj->has('four'));
        $this->assertEquals(null, $urlObj->get('four'));

        // Add four variable again
        $urlObj->add('four', 'other_val');

        // Check result
        $this->assertEquals('/path/to/victory?first=939393333&second=2&third=3&five' . urlencode('[heey]') . '=sexy&five' . urlencode('[nooo]') . '=hexy&four=other_val', $urlObj->getRelativeUrl());
        $this->assertEquals(true, $urlObj->has('four'));
        $this->assertEquals('other_val', $urlObj->get('four'));
    }

    public function testEmptyQueryString(): void
    {
        $urlObj = new Url('https://www.example.com/path/to/victory?first=1&second=2&third=3');

        $urlObj->setQueryString('');

        $this->assertEquals('https://www.example.com/path/to/victory', $urlObj->getAbsoluteUrl());
        $this->assertEquals('/path/to/victory', $urlObj->getRelativeUrl());

        // Test individual parts of the query string
        $this->assertEquals(false, $urlObj->has('first'));
        $this->assertEquals(false, $urlObj->has('second'));
        $this->assertEquals(false, $urlObj->has('third'));
        $this->assertEquals(null, $urlObj->get('first'));
        $this->assertEquals(null, $urlObj->get('second'));
        $this->assertEquals(null, $urlObj->get('third'));
    }

    public function testInvalidScheme(): void
    {
        $this->expectException(UrlException::class);

        $urlObj = new Url('https://www.example.com/path/to/victory?first=1&second=2&third=3');

        $urlObj->setScheme('something');
    }

    public function testInvalidUrl(): void
    {
        $this->expectException(UrlException::class);

        new Url('http:///example.com');
    }
}
