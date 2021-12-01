<?php

namespace Squirrel\Strings\Tests;

use Squirrel\Strings\Exception\RegexException;
use Squirrel\Strings\Regex;

class RegexTest extends \PHPUnit\Framework\TestCase
{
    public function testMatch(): void
    {
        $expectations = [
            [
                'pattern' => '/ladida./',
                'subject' => 'some ladida thing ladida2',
                'result' => true,
                'matches' => [
                    [
                        'ladida ',
                        'ladida2',
                    ],
                ],
            ],
            [
                'pattern' => '/nomatch/',
                'subject' => 'some ladida thing ladida2',
                'result' => false,
                'matches' => null,
            ],
            [
                'pattern' => '/nomatch/',
                'subject' => 'some ladida thing ladida2',
                'result' => false,
                'matches' => null,
            ],
            [
                'pattern' => '/ladida./',
                'subject' => 'some ladida thing ladida2',
                'result' => true,
                'matches' => [
                    [
                      'ladida2',
                    ],
                ],
                'offset' => 10,
            ],
            [
                'pattern' => '/ladida./',
                'subject' => 'some ladida thing ladida2',
                'result' => true,
                'matches' => [
                    [
                        [
                            'ladida ',
                            5,
                        ],
                        [
                            'ladida2',
                            18,
                        ],
                    ],
                ],
                'flags' => PREG_OFFSET_CAPTURE,
            ],
            [
                'pattern' => '/la(di)da./',
                'subject' => 'some ladida thing ladida2',
                'result' => true,
                'matches' => [
                    [
                        'ladida ',
                        'ladida2',
                    ],
                    [
                        'di',
                        'di',
                    ],
                ],
            ],
        ];

        foreach ($expectations as $expectation) {
            $isMatch = Regex::isMatch(
                $expectation['pattern'],
                $expectation['subject'],
                $expectation['offset'] ?? 0,
            );

            $matches = Regex::getMatches(
                $expectation['pattern'],
                $expectation['subject'],
                $expectation['flags'] ?? 0,
                $expectation['offset'] ?? 0,
            );

            $this->assertSame($expectation['result'], $isMatch);
            $this->assertSame($expectation['matches'], $matches);
        }
    }

    public function testReplace(): void
    {
        $expectations = [
            [
                'pattern' => '/ladida./',
                'replacement' => 'dumdidu,',
                'subject' => 'some ladida thing ladida2',
                'result' => 'some dumdidu,thing dumdidu,',
            ],
            [
                'pattern' => '/ladida./',
                'replacement' => 'dumdidu,',
                'subject' => 'some ladida thing ladida2',
                'result' => 'some dumdidu,thing ladida2',
                'limit' => 1,
            ],
            [
                'pattern' => [
                    '/la/',
                    '/di/',
                    '/da/',
                ],
                'replacement' => [
                    'sum',
                    'ki',
                    'plop',
                ],
                'subject' => 'some ladida thing ladida2',
                'result' => 'some sumkiplop thing sumkiplop2',
            ],
            [
                'pattern' => [
                    '/la/',
                    '/di/',
                    '/da/',
                ],
                'replacement' => [
                    'sum',
                    'ki',
                    'plop',
                ],
                'subject' => 'some ladida thing ladida2',
                'result' => 'some sumkiplop thing ladida2',
                'limit' => 1,
            ],
        ];

        foreach ($expectations as $expectation) {
            $result = Regex::replace(
                $expectation['pattern'],
                $expectation['replacement'],
                $expectation['subject'],
                $expectation['limit'] ?? -1,
            );

            $this->assertSame($expectation['result'], $result);
        }
    }

    public function testReplaceArray(): void
    {
        $expectations = [
            [
                'pattern' => '/ladida./',
                'replacement' => 'dumdidu,',
                'subject' => [
                    'some ladida thing ladida2',
                    'for some ladida',
                    'for some ladida ',
                ],
                'result' => [
                    'some dumdidu,thing dumdidu,',
                    'for some ladida',
                    'for some dumdidu,',
                ],
            ],
            [
                'pattern' => '/ladida./',
                'replacement' => 'dumdidu,',
                'subject' => [
                    'some ladida thing ladida2',
                ],
                'result' => [
                    'some dumdidu,thing ladida2',
                ],
                'limit' => 1,
            ],
            [
                'pattern' => [
                    '/la/',
                    '/di/',
                    '/da/',
                ],
                'replacement' => [
                    'sum',
                    'ki',
                    'plop',
                ],
                'subject' => [
                    'some ladida thing ladida2',
                ],
                'result' => [
                    'some sumkiplop thing sumkiplop2',
                ],
            ],
            [
                'pattern' => [
                    '/la/',
                    '/di/',
                    '/da/',
                ],
                'replacement' => [
                    'sum',
                    'ki',
                    'plop',
                ],
                'subject' => [
                    'some ladida thing ladida2',
                ],
                'result' => [
                    'some sumkiplop thing ladida2',
                ],
                'limit' => 1,
            ],
        ];

        foreach ($expectations as $expectation) {
            $result = Regex::replaceArray(
                $expectation['pattern'],
                $expectation['replacement'],
                $expectation['subject'],
                $expectation['limit'] ?? -1,
            );

            $this->assertSame($expectation['result'], $result);
        }
    }

    public function testReplaceWithCallback(): void
    {
        $expectations = [
            [
                'pattern' => '/ladida./',
                'callback' => function (array $matches): string {
                    return 'dumdidu ';
                },
                'subject' => 'some ladida thing ladida2',
                'result' => 'some dumdidu thing dumdidu ',
            ],
            [
                'pattern' => '/ladida./',
                'callback' => function (array $matches): string {
                    return 'dumdidu ';
                },
                'subject' => 'some ladida thing ladida2',
                'result' => 'some dumdidu thing ladida2',
                'limit' => 1,
            ],
        ];

        foreach ($expectations as $expectation) {
            $result = Regex::replaceWithCallback(
                $expectation['pattern'],
                $expectation['callback'],
                $expectation['subject'],
                $expectation['limit'] ?? -1,
            );

            $this->assertSame($expectation['result'], $result);
        }
    }

    public function testReplaceArrayWithCallback(): void
    {
        $expectations = [
            [
                'pattern' => '/ladida./',
                'callback' => function (array $matches): string {
                    return 'dumdidu ';
                },
                'subject' => [
                    'some ladida thing ladida2',
                    'lirum larum',
                ],
                'result' => [
                    'some dumdidu thing dumdidu ',
                    'lirum larum',
                ],
            ],
            [
                'pattern' => '/ladida./',
                'callback' => function (array $matches): string {
                    return 'dumdidu ';
                },
                'subject' => [
                    'some ladida thing ladida2',
                    'lirum larum',
                ],
                'result' => [
                    'some dumdidu thing ladida2',
                    'lirum larum',
                ],
                'limit' => 1,
            ],
        ];

        foreach ($expectations as $expectation) {
            $result = Regex::replaceArrayWithCallback(
                $expectation['pattern'],
                $expectation['callback'],
                $expectation['subject'],
                $expectation['limit'] ?? -1,
            );

            $this->assertSame($expectation['result'], $result);
        }
    }

    public function testIsMatchException(): void
    {
        try {
            Regex::isMatch('][', 'ladida');
        } catch (RegexException $e) {
            $this->assertSame(__LINE__ - 2, $e->getOriginLine());
            $this->assertSame(__LINE__ - 3, $e->getLine());
            $this->assertSame(__FILE__, $e->getOriginFile());
            $this->assertSame('Regex::isMatch(\'][\', \'ladida\')', $e->getOriginCall());
            $this->assertSame('Regex error in Squirrel\Strings\Regex: PREG_INTERNAL_ERROR', $e->getMessage());
        }
    }

    public function testGetMatchesException(): void
    {
        try {
            Regex::getMatches('][', 'ladida');
        } catch (RegexException $e) {
            $this->assertSame(__LINE__ - 2, $e->getOriginLine());
            $this->assertSame(__LINE__ - 3, $e->getLine());
            $this->assertSame(__FILE__, $e->getOriginFile());
            $this->assertStringStartsWith('Regex::getMatches(', $e->getOriginCall());
            $this->assertSame('Regex error in Squirrel\Strings\Regex: PREG_INTERNAL_ERROR', $e->getMessage());
        }
    }

    public function testReplaceException(): void
    {
        try {
            Regex::replace('][', '', 'ladida');
        } catch (RegexException $e) {
            $this->assertSame(__LINE__ - 2, $e->getOriginLine());
            $this->assertSame(__LINE__ - 3, $e->getLine());
            $this->assertSame(__FILE__, $e->getOriginFile());
            $this->assertStringStartsWith('Regex::replace(', $e->getOriginCall());
            $this->assertSame('Regex error in Squirrel\Strings\Regex: PREG_INTERNAL_ERROR', $e->getMessage());
        }
    }

    public function testReplaceArrayException(): void
    {
        try {
            Regex::replaceArray('][', '', ['ladida']);
        } catch (RegexException $e) {
            $this->assertSame(__LINE__ - 2, $e->getOriginLine());
            $this->assertSame(__LINE__ - 3, $e->getLine());
            $this->assertSame(__FILE__, $e->getOriginFile());
            $this->assertStringStartsWith('Regex::replaceArray(', $e->getOriginCall());
            $this->assertSame('Regex error in Squirrel\Strings\Regex: PREG_INTERNAL_ERROR', $e->getMessage());
        }
    }

    public function testReplaceWithCallbackException(): void
    {
        try {
            Regex::replaceWithCallback('][', function (array $matches): string {
                return '';
            }, 'ladida');
        } catch (RegexException $e) {
            $this->assertSame(__LINE__ - 2, $e->getOriginLine());
            $this->assertSame(__LINE__ - 3, $e->getLine());
            $this->assertSame(__FILE__, $e->getOriginFile());
            $this->assertStringStartsWith('Regex::replaceWithCallback(', $e->getOriginCall());
            $this->assertSame('Regex error in Squirrel\Strings\Regex: PREG_INTERNAL_ERROR', $e->getMessage());
        }
    }

    public function testReplaceArrayWithCallbackException(): void
    {
        try {
            Regex::replaceArrayWithCallback('][', function (array $matches): string {
                return '';
            }, ['ladida']);
        } catch (RegexException $e) {
            $this->assertSame(__LINE__ - 2, $e->getOriginLine());
            $this->assertSame(__LINE__ - 3, $e->getLine());
            $this->assertSame(__FILE__, $e->getOriginFile());
            $this->assertStringStartsWith('Regex::replaceArrayWithCallback(', $e->getOriginCall());
            $this->assertSame('Regex error in Squirrel\Strings\Regex: PREG_INTERNAL_ERROR', $e->getMessage());
        }
    }
}
