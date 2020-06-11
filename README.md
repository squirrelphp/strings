Squirrel Strings
================

[![Build Status](https://img.shields.io/travis/com/squirrelphp/strings.svg)](https://travis-ci.com/squirrelphp/strings) [![Test Coverage](https://api.codeclimate.com/v1/badges/780aeeff88d9a49b2d2a/test_coverage)](https://codeclimate.com/github/squirrelphp/strings/test_coverage) ![PHPStan](https://img.shields.io/badge/style-level%208-success.svg?style=flat-round&label=phpstan) [![Packagist Version](https://img.shields.io/packagist/v/squirrelphp/strings.svg?style=flat-round)](https://packagist.org/packages/squirrelphp/strings)  [![PHP Version](https://img.shields.io/packagist/php-v/squirrelphp/strings.svg)](https://packagist.org/packages/squirrelphp/strings) [![Software License](https://img.shields.io/badge/license-MIT-success.svg?style=flat-round)](LICENSE)

Handles common string operations in PHP applications:

- [Filter a string](#filter-a-string) (remove newlines, remove excess spaces, wrap long words, etc.)
- [Generate a random string](#generate-a-random-string) with a set of characters
- [Condense a string into a number](#condense-a-string-into-a-number), and convert back from a number to a string
- [Process an URL](#url) and modify it in a safe way (convert to relative URL, change parts of it, etc.)

Filter a string
---------------

Filters a string and returns back a string, using the `Squirrel\Strings\StringFilterInterface` interface:

```php
public function filter(string $string): string;
```

Each filter does exactly one thing (ideally) so they can be combined depending on how an input needs to be changed / processed.

Additional filters can easily be defined by implementing `Squirrel\Strings\StringFilterInterface`. Possible ideas for custom filters in applications:

- Process HTML tags, usually highly application dependent (which tags are allowed in which context)
- Streamline user input and combine multiple filters into one filter
- Convert HTML to Markdown, or the other way around

This library has some basic filters which cover a lot of ground and can be used individually or combined. Each filter class ends with a `Filter` suffix, which is not mentioned in the following list in order to keep the titles and texts more readable.

### Newlines, tabs and spaces

Normalizing newlines and removing excess spaces is helpful to not waste space before storing data and to always end up with consistent data, especially if you want to transform spaces and newlines to HTML or some other format later.

#### NormalizeNewlinesToUnixStyle

Replaces all types of newlines (in unicode there are currently 8 different newline characters) with unix newlines (\n) so you will not have a mixture of different newlines with unexpected results.

#### ReplaceUnicodeWhitespaces

Replaces all unicode whitespace characters (currently 16 different ones) with a regular space if you do not care about the minute differences between the spaces (like width, or non-breaking, or mathematical). For most input this makes sense in order to be able to trim and limit unnecessary spaces.

#### RemoveExcessSpaces

Removes any unnecessary spaces, which are:

- Any spaces at the beginning or end of the string
- Any spaces around unix newlines
- Reduce consecutive spaces to just one space

Just operates on regular spaces (unicode 0020, decimal 32) and ignores other unicode whitespaces.

#### LimitConsecutiveUnixNewlines

Limits the number of unix newlines which can appear right after each other. This only handles unix newlines and does not factor in spaces between newlines, so running the above three filters first makes sense.

The first argument of this filter is the number of consecutive newlines allowed, and it defaults to two, but can be set to any number above zero.

#### RemoveZeroWidthSpaces

Zero width spaces are usually not something you want to save in a database, so this filter removes the three main zero width spaces defined in unicode.

#### ReplaceNewlinesWithSpaces

If you do not need any newlines in a text or they are not allowed, this filter replaces any type of newline (in unicode there are currently 8 different newline characters) with a space (to avoid the risk of combining content which is only separated by a newline). Internally this uses the [NormalizeNewlinesToUnixStyle](#normalizenewlinestounixstyle) filter first and then replaces the unix style newlines with spaces.

#### Trim

Trims characters from the beginning and end of the string, using the PHP trim function if the characters given to the constructor are only ASCII, or using regex if unicode characters are trimmed.

By default (if no constructor argument is used) the same characters are trimmed as the PHP trim function trims by default, meaning: " \t\n\r\0\x0B" (ordinary space, horizontal tab, new line, carriage return, NUL byte and vertical tab)

#### ReplaceTabsWithSpaces

Replaces all horizontal tabs with spaces. This is the only filter that deals with horizontal tabs, as tabs might have a different/specific meaning compared to the other unicode spaces.

#### WrapLongWordsNoHTML

Long sequences of characters without a breaking character (like a space or newline) can break layouts and be difficult to display, and it can easily occur in user input or even by accident in regular content you write yourself.

This filter adds a zero-width space after a certain amount of characters (20 by default) in which no unix newlines or regular spaces occur. So if there is ample space even long words are not broken up, but if the space is tight the long word is split up into multiple lines.

This variant of the filter assumes no HTML is allowed, so it may break up any long sequence of characters.

#### WrapLongWordsWithHTML

Long sequences of characters without a breaking character (like a space or newline) can break layouts and be difficult to display, and it can easily occur in user input or even by accident in regular content you write yourself.

This filter adds a zero-width space after a certain amount of characters (20 by default) in which no unix newlines or regular spaces occur. So if there is ample space even long words are not broken up, but if the space is tight the long word is split up into multiple lines.

This variant of the filter looks for HTML tags in the string. If there are none, it behaves like [WrapLongWordsNoHTML](#wraplongwordsnohtml), if HTML tags do occur, each one is temporarily replaced by a substitute character and only counts as one character, and will not be broken up by the filter. So words might be split up "too early" when many HTML tags occur, as HTML tags count as one character for wrapping.

### Cases: lowercase, uppercase, camelcase, snakecase

#### Lowercase

Converts all unicode characters to their lowercase equivalent.

#### Uppercase

Converts all unicode characters to their uppercase equivalent.

#### UppercaseFirstCharacter

Convert the first character in the string to uppercase, correctly handles a unicode character as first character.

#### UppercaseWordsFirstCharacter

Convert the first character of every word in the string to uppercase, correctly handles unicode characters.

#### CamelCaseToSnakeCase

Convert from CamelCase to snake_case. Only supports alphanumeric characters (A-Z, a-z, 0-9), ignores all others!

#### SnakeCaseToCamelCase

Convert from snake_case to CamelCase. Only supports alphanumeric characters (A-Z, a-z, 0-9), ignores all others!

### HTML

#### RemoveHTMLTags

Removes all HTML tags. If HTML tags are malformed this might remove more than expected, as it does not try to validate the HTML, it just removes anything that looks like a HTML tag.

#### RemoveHTMLTagCharacters

Removes the three main characters used in HTML tags: < , > and "

#### ReplaceUnixStyleNewlinesWithParagraphs

For HTML you often want to process newlines in a predictable way, this filter is one possibility:

- Convert double newlines `\n\n` to `</p><p>`
- Convert single newlines `\n` to `<br/>`
- Add `<p>` to the beginning of the string and `</p>` to the end

For simple content without block level HTML tags this is often ideal to structure text and show it on a HTML page.

#### EncodeBasicHTMLEntities

Encode `&"'<>` into their HTML entities (`&amp;`, `&quot;`, `&apos;`, `&lt;`, `&gt;`), which is mainly helpful for correctly and securely displaying text in a HTML context.

#### DecodeBasicHTMLEntities

Does the reverse of [EncodeBasicHTMLEntities](#encodebasichtmlentities), sensible if you know input might contain HTML entities and you want to streamline the text and avoid something like `&amp;amp;`.

#### DecodeAllHTMLEntities

This decodes all HTML entities according to the HTML5 standard (using `html_entity_decode` internally). This is usually not necessary but might make sense if you receive text and know it contains a lot of HTML entities and you do not know exactly which or how many.

### Remove/restrict characters and content

#### RemoveNonUTF8Characters

Removes any characters which are not valid according to the UTF8 specification. This filter is recommended for anything coming from outside of your application (user input, web services, data import) so you can continue to operate on a valid UTF8 string afterwards. Most string functions or databases will otherwise reject a string if it contains invalid characters.

If invalid UTF8 characters must never appear in your application it might make sense to instead check the encoding in your application and throw an exception in this way:

```php
// Checks if $string contains only valid UTF8 characters
if (!\mb_check_encoding($string, 'UTF-8')) {
  // Invalid characters found, log this or throw an exception
}
```

Yet this might be overkill for generic user input, where you just want to try to work things out even if the input is partly malformed (might be better than to fail completely).

#### RemoveNonAlphanumeric

Remove any characters which are not letters or numbers, so only A-Z, a-z and 0-9 are allowed. Can be handy for tokens, parts of an URL, an entered code, or other things where you know no other characters are allowed and you just want to ignore anything non-alphanumeric.

#### RemoveNonNumeric

Remove any characters which are not numbers, so only 0-9 are allowed. Can be handy for tokens, parts of an URL, an entered code, or other things where you know no other characters are allowed and you just want to ignore them.

#### RemoveNonAsciiAndControlCharacters

Remove any characters which are not letters, numbers and basic ASCII characters, so only A-Z, a-z, 0-9, space and ``!"#$'()*+,-./:;<=>?@[\]^_`{|}~`` are allowed (no newlines, control characters, or unicode - all that is removed).

#### RemoveEmails

Remove anything that looks like an email, meaning any string part with non-space characters before and after an @ symbol, so this is quite "greedy".

Originally added to be able to analyze texts and detect the language, where email addresses would only confuse a language detection algorithm, so removing anything that looks like an email from a string should lead to "just text" or at least more analyzable text.

#### RemoveURLs

Remove anything that looks like an URL, meaning any string part that starts with a valid looking scheme, followed by "://", followed by zero or more non-space characters.

Originally added to be able to analyze texts and detect the language, where URLs would only confuse a language detection algorithm, so removing anything that looks like an URL from a string should lead to "just text" or at least more analyzable text.

### Normalize to ASCII

Sometimes unicode with its plethora of characters can be a hindrance - for example in these cases:

- In a database of blocked customers you would want an entered first name like `émil` to also match `emil`, so a customer cannot slightly change his name to circumvent your security measures
- When a user enters his address which you check against a database of known addresses you want it to be user-friendly, so if a user enters `Leon Breitling-Strasse` or `Leon Breitlingstrasse` you want both of these to match `Léon Breitling-Strasse` even though the characters do not match 1:1
- For an URL you want to map to ASCII letters as much as possible, so a blog post with a title like `L'école d'Humanité` becomes `l-ecole-d-humanite` (for an URL like `https://my-blog.com/2019-07-12/l-ecole-d-humanite`) which is both readable for users and search engines

Beware: This works well for countries and languages with latin characters (like most of Europe, North America, South America, most of Africa, Australia), yet not so well with other scripts, like Cyrillic, Arabic, Hanzi or Greek, to name just a few.

#### NormalizeLettersToAscii

Reduces most letters to their base latin ASCII character (A-Z, a-z), if it is possible, so é becomes e, Â becomes A, etc. It is very thorough and uses both the Normalizer from the Intl extension and a long list of custom conversions. Some characters are converted to two ASCII characters (like `Æ` => `AE`, or `ß` to `ss`), so your string might get longer.

#### NormalizeToAlphanumeric

Runs [NormalizeLettersToAscii](#normalizeletterstoascii) from above and then removes any non-alphanumeric characters, so:

- `Léon Breitling-Strasse 13` becomes `LeonBreitlingStrasse13`
- 'Pré Raguel Strasse de l'école' becomes 'PreRaguelStrassedelecole'

#### NormalizeToAlphanumericLowercase

Runs [NormalizeToAlphanumeric](#normalizetoalphanumeric) from above and then converts all characters to lowercase, so:

- `Léon Breitling-Strasse 13` becomes `leonbreitlingstrasse13`
- 'Pré Raguel Strasse de l'école' becomes 'preraguelstrassedelecole'

If you process both the user inputs and the known values in your database in this manner, you can match them and get more matches/results, as spaces, dashes, diacritics etc. are not taken into account.

#### ReplaceNonAlphanumeric

Sometimes you do not want to remove non-alphanumeric characters but instead replace them with a character, for example for URLs you want to convert `L'école d'Humanité` to `l-ecole-d-humanite`.

This is what `ReplaceNonAlphanumeric` does by default - replace all non-alphanumeric characters with a dash, and if multiple non-alphanumeric characters occur in sequence they are replaced by just one dash.

In the contructor of `ReplaceNonAlphanumeric` you can set another replacement character instead of a dash - for example a dot, or a slash, depending on your use case.

### Streamline input

These filters combine other filters into a sensible package to run on user input, and is more of an example than something you might want to use directly in your application.

You can do your own combination of filters by using the `Squirrel\Strings\StringFilterRunner` class.

#### StreamlineInputWithNewlines

Runs the following filters:

- [RemoveNonUTF8Characters](#removenonutf8characters)
- [ReplaceUnicodeWhitespaces](#replaceunicodewhitespaces)
- [ReplaceTabsWithSpaces](#replacetabswithspaces)
- [NormalizeNewlinesToUnixStyle](#normalizenewlinestounixstyle)
- [RemoveExcessSpaces](#removeexcessspaces)
- [LimitConsecutiveUnixNewlines](#limitconsecutiveunixnewlines)

This makes sure the string is valid UTF8 and normalizes all whitespace characters and removes unnecessary whitespace characters, while leaving the content itself alone (works with or without HTML, does not convert HTML entities).

#### StreamlineInputNoNewlines

Runs the following filters:

- [RemoveNonUTF8Characters](#removenonutf8characters)
- [ReplaceUnicodeWhitespaces](#replaceunicodewhitespaces)
- [ReplaceTabsWithSpaces](#replacetabswithspaces)
- [ReplaceNewlinesWithSpaces](#replacenewlineswithspaces)
- [RemoveExcessSpaces](#removeexcessspaces)

Basically the same as [StreamlineInputWithNewlines](#streamlineinputwithnewlines) but newlines are converted to spaces. This is good for common user input like names, emails addresses and any other fields where newlines make no sense.

Generate a random string
------------------------

Generates random strings according to a list of possible characters which can be used.

With the two included classes (one with unicode support, one for ASCII-only) it is easy to define a random generator with your own set of characters which should be allowed to appear in a random string. These are sensible values:

- `ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789` for 62 possible values per character, each can be A-Z, a-z or 0-9 and these values are very safe to use in applications (no special characters, only alphanumeric)
- `abcdefghijklmnopqrstuvwxyz0123456789` for 36 possible values per character, same as above except this is the case insensitive version, for when there should be no difference between "A" and "a" (for example)
- `234579ACDEFGHKMNPQRSTUVWXYZ` or `234579acdefghkmnpqrstuvwxyz` for 27 read-friendly uppercase or lowercase characters: if a person has to enter a code it is good to avoid characters which are very similar and easily confusable, like 0 (number zero) and O (letter), or 8 (number eight) and B (letter)

Defining your own range of possible characters is easy, and even unicode characters can be used.

Condense a string into a number
-------------------------------

Convert an integer to a string with a given "character set" - this way we can encode an integer to condense it (so an integer with 8 numbers is now only a 4-character-string) and later convert it back when needed.

The main use case are tokens in URLs, so less space is needed, as even large numbers become short strings if you use 36 or 62 values per character: with 62 possible characters (`ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789`) a string which is three characters long can cover numbers up to 238'328, with five characters you can cover numbers up to 916'132'832.

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