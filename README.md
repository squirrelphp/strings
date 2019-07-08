Squirrel Strings
================

[![Build Status](https://img.shields.io/travis/com/squirrelphp/strings.svg)](https://travis-ci.com/squirrelphp/strings) [![Test Coverage](https://api.codeclimate.com/v1/badges/780aeeff88d9a49b2d2a/test_coverage)](https://codeclimate.com/github/squirrelphp/strings/test_coverage) ![PHPStan](https://img.shields.io/badge/style-level%207-success.svg?style=flat-round&label=phpstan) [![Packagist Version](https://img.shields.io/packagist/v/squirrelphp/strings.svg?style=flat-round)](https://packagist.org/packages/squirrelphp/strings)  [![PHP Version](https://img.shields.io/packagist/php-v/squirrelphp/strings.svg)](https://packagist.org/packages/squirrelphp/strings) [![Software License](https://img.shields.io/badge/license-MIT-success.svg?style=flat-round)](LICENSE)

Handles common string operations in applications:

- Filter a string (remove newlines, remove excess spaces, wrap long words, etc.)
- Generate a random string with a set of characters
- Condense a number into a string, and convert back from a string to a number
- Process an URL and modify it in a safe way (convert to relative URL, change parts of it, etc.)

Filter
------

Filters a string according to specific criterias. Each filter does exactly one thing (ideally) so they can be combined depending on how an input needs to be changed / processed.

Additional filters can easily be defined - for example to process custom tags. This library has some basic useful filters which can be used.

Random
------

Generates random strings according to a list of possible characters which can be used.

With the two included classes (one with unicode support, one for ASCII-only) it is easy to define a random generator with your own set of characters which should be allowed to appear in a random string. These are sensible values:

- "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789" for 62 possible values per character, each can be A-Z, a-z or 0-9 and these values are very safe to use in applications (no special characters, only alphanumeric)
- "abcdefghijklmnopqrstuvwxyz0123456789" for 36 possible values per character, same as above except this is the case insensitive version, for when there should be no difference between "A" and "a" (for example)
- "234579ACDEFGHKMNPQRSTUVWXYZ" or "234579acdefghkmnpqrstuvwxyz" for 27 read-friendly uppercase or lowercase characters: if a person has to enter a code it is good to avoid characters which are very similar and easily confusable, like 0 (number zero) and O (letter), or 8 (number eight) and B (letter)

Defining your own range of possible characters is easy, and even unicode characters can be used.

Condense
--------

Convert an integer to a string with a given "character set" - this way we can encode an integer to condense it (so an integer with 8 numbers is now only a 4-character-string) and later convert it back when needed.

The main use case are tokens in URLs, so less space is needed, as even large numbers become short strings if you use 36 or 62 values per character: with 62 possible characters ("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789") a string which is three characters long can cover numbers up to 238'328, with five characters you can cover numbers up to 916'132'832.

A side benefit of condensing is that it becomes less obvious an integer is used - tokens just look random and do not divulge their intent. 

Defining your own range of possible characters is easy, and even unicode characters can be used.

URL
---

The URL class accepts an URL in the constructor and then lets you get or change certain parts of the URL to do the following:

- Get scheme, host, path, query string and specific query string variables
- Change an absolute URL to a relative URL
- Change scheme, host, path and query string
- Replace query string variables, or add/remove them

This can be used to easily build or change your URLs, or to sanitize certain parts of a given URL, for example when redirecting: use the relative URL instead of the absolute URL to avoid malicious redirecting to somewhere outside of your control.