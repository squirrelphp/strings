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
 * The attempt is to normalize as many letters as possible focusing on latin unicode characters - there
 * might still be some missing
 */
class NormalizeLettersToAsciiFilter implements StringFilterInterface
{
    use RegexExceptionTrait;

    /**
     * @var array All these characters need to be specifically converted by this class, key is decimal unicode codepoint
     */
    private $codepointConversions = [
        198 => 'AE', // Æ
        216 => 'O', // Ø
        223 => 'ss', // ß
        230 => 'ae', // æ
        248 => 'o', // ø
        272 => 'D', // Đ
        273 => 'd', // đ
        294 => 'H', // Ħ
        295 => 'h', // ħ
        305 => 'i', // ı
        306 => 'IJ', // Ĳ
        307 => 'ij', // ĳ
        319 => 'L', // Ŀ
        320 => 'l', // ŀ
        321 => 'L', // Ł
        322 => 'l', // ł
        329 => 'n', // ŉ
        338 => 'OE', // Œ
        339 => 'oe', // œ
        358 => 'T', // Ŧ
        359 => 't', // ŧ
        383 => 's', // ſ
        384 => 'b', // ƀ
        385 => 'B', // Ɓ
        386 => 'B', // Ƃ
        387 => 'b', // ƃ
        391 => 'C', // Ƈ
        392 => 'c', // ƈ
        393 => 'D', // Ɖ
        394 => 'D', // Ɗ
        395 => 'D', // Ƌ
        396 => 'd', // ƌ
        401 => 'F', // Ƒ
        402 => 'f', // ƒ
        403 => 'G', // Ɠ
        405 => 'hv', // ƕ
        407 => 'I', // Ɨ
        408 => 'K', // Ƙ
        409 => 'k', // ƙ
        410 => 'l', // ƚ
        413 => 'N', // Ɲ
        414 => 'n', // ƞ
        415 => 'O', // Ɵ
        420 => 'P', // Ƥ
        421 => 'p', // ƥ
        427 => 't', // ƫ
        428 => 'T', // Ƭ
        429 => 't', // ƭ
        430 => 'T', // Ʈ
        434 => 'V', // Ʋ
        435 => 'Y', // Ƴ
        436 => 'y', // ƴ
        437 => 'Z', // Ƶ
        438 => 'z', // ƶ
        452 => 'DZ', // Ǆ
        453 => 'Dz', // ǅ
        454 => 'dz', // ǆ
        455 => 'LJ', // Ǉ
        456 => 'Lj', // ǈ
        457 => 'lj', // ǉ
        458 => 'NJ', // Ǌ
        459 => 'Nj', // ǋ
        460 => 'nj', // ǌ
        484 => 'G', // Ǥ
        485 => 'g', // ǥ
        497 => 'DZ', // Ǳ
        498 => 'Dz', // ǲ
        499 => 'dz', // ǳ
        544 => 'N', // Ƞ
        545 => 'd', // ȡ
        548 => 'Z', // Ȥ
        549 => 'z', // ȥ
        564 => 'l', // ȴ
        565 => 'n', // ȵ
        566 => 't', // ȶ
        567 => 'j', // ȷ
        570 => 'A', // Ⱥ
        571 => 'C', // Ȼ
        572 => 'c', // ȼ
        573 => 'L', // Ƚ
        574 => 'T', // Ⱦ
        575 => 's', // ȿ
        576 => 'z', // ɀ
        579 => 'B', // Ƀ
        580 => 'U', // Ʉ
        582 => 'E', // Ɇ
        583 => 'e', // ɇ
        584 => 'J', // Ɉ
        585 => 'j', // ɉ
        586 => 'Q', // Ɋ
        587 => 'q', // ɋ
        588 => 'R', // Ɍ
        589 => 'r', // ɍ
        590 => 'Y', // Ɏ
        591 => 'y', // ɏ
        7834 => 'a', // ẚ
        7836 => 's', // ẜ
        7837 => 's', // ẝ
        7838 => 'SS', // ẞ
        7930 => 'LL', // Ỻ
        7931 => 'll', // ỻ
        7932 => 'V', // Ỽ
        7933 => 'v', // ỽ
        7934 => 'Y', // Ỿ
        7935 => 'y', // ỿ
    ];

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

        // Replace characters with diacritics not covered by the Normalizer call above
        foreach ($this->codepointConversions as $codepointAsInt => $replacementCharacter) {
            $string = \str_replace(\IntlChar::chr($codepointAsInt), $replacementCharacter, $string);
        }

        return $string;
    }
}
