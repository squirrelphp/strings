<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\Common\RegexExceptionTrait;
use Squirrel\Strings\StringFilterInterface;

/**
 * Attempts to convert all unicode letters to their most suitable ascii letter, for example:
 *
 * ö => o
 * é => e
 * î => i
 *
 * And so on. The additional information is lost, so this filter is most useful for something like
 * search normalization, to find more results even if they don't match 100%, or if you want to use a
 * normalized string in a URL, email address, etc.
 *
 * The attempt is to normalize as many letters as possible, yet there might still be many missing, so you
 * will need another filter like ReplaceNonAlphanumericFilter to replace those
 */
class NormalizeLettersToAsciiFilter implements StringFilterInterface
{
    use RegexExceptionTrait;

    public function filter(string $string): string
    {
        // Maps special characters (with diacritics) to their base character followed by the diacritical mark
        // Examples: Ú => U´,  á => a`
        $string = \Normalizer::normalize($string, \Normalizer::FORM_D);

        // Remove diacritics
        $string = \preg_replace('@\pM@u', "", $string);

        // @codeCoverageIgnoreStart
        if ($string === null) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        // Replace diacritics not covered by the examples above
        $string = \str_replace('ø', 'o', $string);
        $string = \str_replace('º', '', $string);
        $string = \str_replace('æ', 'ae', $string);
        $string = \str_replace('œ', 'oe', $string);
        $string = \str_replace('ß', 'ss', $string);
        $string = \str_replace("\u{0133}", 'ij', $string);
        $string = \str_replace("\u{0153}", 'o', $string);
        $string = \str_replace("\u{00f0}", 'd', $string);
        $string = \str_replace("\u{0111}", 'd', $string);
        $string = \str_replace("\u{0127}", 'h', $string);
        $string = \str_replace("\u{0131}", 'i', $string);
        $string = \str_replace("\u{0138}", 'k', $string);
        $string = \str_replace("\u{0140}", 'l', $string);
        $string = \str_replace("\u{0142}", 'l', $string);
        $string = \str_replace("\u{0149}", 'n', $string);
        $string = \str_replace("\u{014b}", 'n', $string);
        $string = \str_replace("\u{017f}", 's', $string);
        $string = \str_replace("\u{00fe}", 't', $string);
        $string = \str_replace("\u{0167}", 't', $string);

        return $string;
    }
}
