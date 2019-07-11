<?php

namespace Squirrel\Strings\Tests;

use Squirrel\Strings\Filter\NormalizeLettersToAsciiFilter;

/**
 * Test all supported special characters which are converted
 */
class NormalizeLettersToAsciiFilterTest extends \PHPUnit\Framework\TestCase
{
    private $codepointsToTest = [
        192 => 'A', // À
        193 => 'A', // Á
        194 => 'A', // Â
        195 => 'A', // Ã
        196 => 'A', // Ä
        197 => 'A', // Å
        198 => 'AE', // Æ
        199 => 'C', // Ç
        200 => 'E', // È
        201 => 'E', // É
        202 => 'E', // Ê
        203 => 'E', // Ë
        204 => 'I', // Ì
        205 => 'I', // Í
        206 => 'I', // Î
        207 => 'I', // Ï
        209 => 'N', // Ñ
        210 => 'O', // Ò
        211 => 'O', // Ó
        212 => 'O', // Ô
        213 => 'O', // Õ
        216 => 'O', // Ø
        217 => 'U', // Ù
        218 => 'U', // Ú
        219 => 'U', // Û
        220 => 'U', // Ü
        221 => 'Y', // Ý
        223 => 'ss', // ß
        224 => 'a', // à
        225 => 'a', // á
        226 => 'a', // â
        227 => 'a', // ã
        228 => 'a', // ä
        229 => 'a', // å
        230 => 'ae', // æ
        231 => 'c', // ç
        232 => 'e', // è
        233 => 'e', // é
        234 => 'e', // ê
        235 => 'e', // ë
        236 => 'i', // ì
        237 => 'i', // í
        238 => 'i', // î
        239 => 'i', // ï
        241 => 'n', // ñ
        242 => 'o', // ò
        243 => 'o', // ó
        244 => 'o', // ô
        245 => 'o', // õ
        246 => 'o', // ö
        248 => 'o', // ø
        249 => 'u', // ù
        250 => 'u', // ú
        251 => 'u', // û
        252 => 'u', // ü
        253 => 'y', // ý
        256 => 'A', // Ā
        257 => 'a', // ā
        258 => 'A', // Ă
        259 => 'a', // ă
        260 => 'A', // Ą
        261 => 'a', // ą
        262 => 'C', // Ć
        263 => 'c', // ć
        264 => 'C', // Ĉ
        265 => 'c', // ĉ
        266 => 'C', // Ċ
        267 => 'c', // ċ
        268 => 'C', // Č
        269 => 'c', // č
        270 => 'D', // Ď
        271 => 'd', // ď
        272 => 'D', // Đ
        273 => 'd', // đ
        274 => 'E', // Ē
        275 => 'e', // ē
        276 => 'E', // Ĕ
        277 => 'e', // ĕ
        278 => 'E', // Ė
        279 => 'e', // ė
        280 => 'E', // Ę
        281 => 'e', // ę
        282 => 'E', // Ě
        283 => 'e', // ě
        284 => 'G', // Ĝ
        285 => 'g', // ĝ
        286 => 'G', // Ğ
        287 => 'g', // ğ
        288 => 'G', // Ġ
        289 => 'g', // ġ
        290 => 'G', // Ģ
        291 => 'g', // ģ
        292 => 'H', // Ĥ
        293 => 'h', // ĥ
        294 => 'H', // Ħ
        295 => 'h', // ħ
        296 => 'I', // Ĩ
        297 => 'i', // ĩ
        298 => 'I', // Ī
        299 => 'i', // ī
        300 => 'I', // Ĭ
        301 => 'i', // ĭ
        302 => 'I', // Į
        303 => 'i', // į
        304 => 'I', // İ
        305 => 'i', // ı
        306 => 'IJ', // Ĳ
        307 => 'ij', // ĳ
        308 => 'J', // Ĵ
        309 => 'j', // ĵ
        310 => 'K', // Ķ
        311 => 'k', // ķ
        313 => 'L', // Ĺ
        314 => 'l', // ĺ
        315 => 'L', // Ļ
        316 => 'l', // ļ
        317 => 'L', // Ľ
        318 => 'l', // ľ
        319 => 'L', // Ŀ
        320 => 'l', // ŀ
        321 => 'L', // Ł
        322 => 'l', // ł
        323 => 'N', // Ń
        324 => 'n', // ń
        325 => 'N', // Ņ
        326 => 'n', // ņ
        327 => 'N', // Ň
        328 => 'n', // ň
        329 => 'n', // ŉ
        332 => 'O', // Ō
        333 => 'o', // ō
        334 => 'O', // Ŏ
        335 => 'o', // ŏ
        336 => 'O', // Ő
        337 => 'o', // ő
        338 => 'OE', // Œ
        339 => 'oe', // œ
        340 => 'R', // Ŕ
        341 => 'r', // ŕ
        342 => 'R', // Ŗ
        343 => 'r', // ŗ
        344 => 'R', // Ř
        345 => 'r', // ř
        346 => 'S', // Ś
        347 => 's', // ś
        348 => 'S', // Ŝ
        349 => 's', // ŝ
        350 => 'S', // Ş
        351 => 's', // ş
        352 => 'S', // Š
        353 => 's', // š
        354 => 'T', // Ţ
        355 => 't', // ţ
        356 => 'T', // Ť
        357 => 't', // ť
        358 => 'T', // Ŧ
        359 => 't', // ŧ
        360 => 'U', // Ũ
        361 => 'u', // ũ
        362 => 'U', // Ū
        363 => 'u', // ū
        364 => 'U', // Ŭ
        365 => 'u', // ŭ
        366 => 'U', // Ů
        367 => 'u', // ů
        368 => 'U', // Ű
        369 => 'u', // ű
        370 => 'U', // Ų
        371 => 'u', // ų
        372 => 'W', // Ŵ
        373 => 'w', // ŵ
        374 => 'Y', // Ŷ
        375 => 'y', // ŷ
        376 => 'Y', // Ÿ
        377 => 'Z', // Ź
        378 => 'z', // ź
        379 => 'Z', // Ż
        380 => 'z', // ż
        381 => 'Z', // Ž
        382 => 'z', // ž
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
        416 => 'O', // Ơ
        417 => 'o', // ơ
        420 => 'P', // Ƥ
        421 => 'p', // ƥ
        427 => 't', // ƫ
        428 => 'T', // Ƭ
        429 => 't', // ƭ
        430 => 'T', // Ʈ
        431 => 'U', // Ư
        432 => 'u', // ư
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
        461 => 'A', // Ǎ
        462 => 'a', // ǎ
        463 => 'I', // Ǐ
        464 => 'i', // ǐ
        465 => 'O', // Ǒ
        466 => 'o', // ǒ
        467 => 'U', // Ǔ
        468 => 'u', // ǔ
        469 => 'U', // Ǖ
        470 => 'u', // ǖ
        471 => 'U', // Ǘ
        472 => 'u', // ǘ
        473 => 'U', // Ǚ
        474 => 'u', // ǚ
        475 => 'U', // Ǜ
        476 => 'u', // ǜ
        478 => 'A', // Ǟ
        479 => 'a', // ǟ
        480 => 'A', // Ǡ
        481 => 'a', // ǡ
        482 => 'AE', // Ǣ
        483 => 'ae', // ǣ
        484 => 'G', // Ǥ
        485 => 'g', // ǥ
        486 => 'G', // Ǧ
        487 => 'g', // ǧ
        488 => 'K', // Ǩ
        489 => 'k', // ǩ
        490 => 'O', // Ǫ
        491 => 'o', // ǫ
        492 => 'O', // Ǭ
        493 => 'o', // ǭ
        494 => 'Ʒ', // Ǯ
        495 => 'ʒ', // ǯ
        496 => 'j', // ǰ
        497 => 'DZ', // Ǳ
        498 => 'Dz', // ǲ
        499 => 'dz', // ǳ
        500 => 'G', // Ǵ
        501 => 'g', // ǵ
        504 => 'N', // Ǹ
        505 => 'n', // ǹ
        506 => 'A', // Ǻ
        507 => 'a', // ǻ
        508 => 'AE', // Ǽ
        509 => 'ae', // ǽ
        510 => 'O', // Ǿ
        511 => 'o', // ǿ
        512 => 'A', // Ȁ
        513 => 'a', // ȁ
        514 => 'A', // Ȃ
        515 => 'a', // ȃ
        516 => 'E', // Ȅ
        517 => 'e', // ȅ
        518 => 'E', // Ȇ
        519 => 'e', // ȇ
        520 => 'I', // Ȉ
        521 => 'i', // ȉ
        522 => 'I', // Ȋ
        523 => 'i', // ȋ
        524 => 'O', // Ȍ
        525 => 'o', // ȍ
        526 => 'O', // Ȏ
        527 => 'o', // ȏ
        528 => 'R', // Ȑ
        529 => 'r', // ȑ
        530 => 'R', // Ȓ
        531 => 'r', // ȓ
        532 => 'U', // Ȕ
        533 => 'u', // ȕ
        534 => 'U', // Ȗ
        535 => 'u', // ȗ
        536 => 'S', // Ș
        537 => 's', // ș
        538 => 'T', // Ț
        539 => 't', // ț
        542 => 'H', // Ȟ
        543 => 'h', // ȟ
        544 => 'N', // Ƞ
        545 => 'd', // ȡ
        548 => 'Z', // Ȥ
        549 => 'z', // ȥ
        550 => 'A', // Ȧ
        551 => 'a', // ȧ
        552 => 'E', // Ȩ
        553 => 'e', // ȩ
        554 => 'O', // Ȫ
        555 => 'o', // ȫ
        556 => 'O', // Ȭ
        557 => 'o', // ȭ
        558 => 'O', // Ȯ
        559 => 'o', // ȯ
        560 => 'O', // Ȱ
        561 => 'o', // ȱ
        562 => 'Y', // Ȳ
        563 => 'y', // ȳ
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
        7680 => 'A', // Ḁ
        7681 => 'a', // ḁ
        7682 => 'B', // Ḃ
        7683 => 'b', // ḃ
        7684 => 'B', // Ḅ
        7685 => 'b', // ḅ
        7686 => 'B', // Ḇ
        7687 => 'b', // ḇ
        7688 => 'C', // Ḉ
        7689 => 'c', // ḉ
        7690 => 'D', // Ḋ
        7691 => 'd', // ḋ
        7692 => 'D', // Ḍ
        7693 => 'd', // ḍ
        7694 => 'D', // Ḏ
        7695 => 'd', // ḏ
        7696 => 'D', // Ḑ
        7697 => 'd', // ḑ
        7698 => 'D', // Ḓ
        7699 => 'd', // ḓ
        7700 => 'E', // Ḕ
        7701 => 'e', // ḕ
        7702 => 'E', // Ḗ
        7703 => 'e', // ḗ
        7704 => 'E', // Ḙ
        7705 => 'e', // ḙ
        7706 => 'E', // Ḛ
        7707 => 'e', // ḛ
        7708 => 'E', // Ḝ
        7709 => 'e', // ḝ
        7710 => 'F', // Ḟ
        7711 => 'f', // ḟ
        7712 => 'G', // Ḡ
        7713 => 'g', // ḡ
        7714 => 'H', // Ḣ
        7715 => 'h', // ḣ
        7716 => 'H', // Ḥ
        7717 => 'h', // ḥ
        7718 => 'H', // Ḧ
        7719 => 'h', // ḧ
        7720 => 'H', // Ḩ
        7721 => 'h', // ḩ
        7722 => 'H', // Ḫ
        7723 => 'h', // ḫ
        7724 => 'I', // Ḭ
        7725 => 'i', // ḭ
        7726 => 'I', // Ḯ
        7727 => 'i', // ḯ
        7728 => 'K', // Ḱ
        7729 => 'k', // ḱ
        7730 => 'K', // Ḳ
        7731 => 'k', // ḳ
        7732 => 'K', // Ḵ
        7733 => 'k', // ḵ
        7734 => 'L', // Ḷ
        7735 => 'l', // ḷ
        7736 => 'L', // Ḹ
        7737 => 'l', // ḹ
        7738 => 'L', // Ḻ
        7739 => 'l', // ḻ
        7740 => 'L', // Ḽ
        7741 => 'l', // ḽ
        7742 => 'M', // Ḿ
        7743 => 'm', // ḿ
        7744 => 'M', // Ṁ
        7745 => 'm', // ṁ
        7746 => 'M', // Ṃ
        7747 => 'm', // ṃ
        7748 => 'N', // Ṅ
        7749 => 'n', // ṅ
        7750 => 'N', // Ṇ
        7751 => 'n', // ṇ
        7752 => 'N', // Ṉ
        7753 => 'n', // ṉ
        7754 => 'N', // Ṋ
        7755 => 'n', // ṋ
        7756 => 'O', // Ṍ
        7757 => 'o', // ṍ
        7758 => 'O', // Ṏ
        7759 => 'o', // ṏ
        7760 => 'O', // Ṑ
        7761 => 'o', // ṑ
        7762 => 'O', // Ṓ
        7763 => 'o', // ṓ
        7764 => 'P', // Ṕ
        7765 => 'p', // ṕ
        7766 => 'P', // Ṗ
        7767 => 'p', // ṗ
        7768 => 'R', // Ṙ
        7769 => 'r', // ṙ
        7770 => 'R', // Ṛ
        7771 => 'r', // ṛ
        7772 => 'R', // Ṝ
        7773 => 'r', // ṝ
        7774 => 'R', // Ṟ
        7775 => 'r', // ṟ
        7776 => 'S', // Ṡ
        7777 => 's', // ṡ
        7778 => 'S', // Ṣ
        7779 => 's', // ṣ
        7780 => 'S', // Ṥ
        7781 => 's', // ṥ
        7782 => 'S', // Ṧ
        7783 => 's', // ṧ
        7784 => 'S', // Ṩ
        7785 => 's', // ṩ
        7786 => 'T', // Ṫ
        7787 => 't', // ṫ
        7788 => 'T', // Ṭ
        7789 => 't', // ṭ
        7790 => 'T', // Ṯ
        7791 => 't', // ṯ
        7792 => 'T', // Ṱ
        7793 => 't', // ṱ
        7794 => 'U', // Ṳ
        7795 => 'u', // ṳ
        7796 => 'U', // Ṵ
        7797 => 'u', // ṵ
        7798 => 'U', // Ṷ
        7799 => 'u', // ṷ
        7800 => 'U', // Ṹ
        7801 => 'u', // ṹ
        7802 => 'U', // Ṻ
        7803 => 'u', // ṻ
        7804 => 'V', // Ṽ
        7805 => 'v', // ṽ
        7806 => 'V', // Ṿ
        7807 => 'v', // ṿ
        7808 => 'W', // Ẁ
        7809 => 'w', // ẁ
        7810 => 'W', // Ẃ
        7811 => 'w', // ẃ
        7812 => 'W', // Ẅ
        7813 => 'w', // ẅ
        7814 => 'W', // Ẇ
        7815 => 'w', // ẇ
        7816 => 'W', // Ẉ
        7817 => 'w', // ẉ
        7818 => 'X', // Ẋ
        7819 => 'x', // ẋ
        7820 => 'X', // Ẍ
        7821 => 'x', // ẍ
        7822 => 'Y', // Ẏ
        7823 => 'y', // ẏ
        7824 => 'Z', // Ẑ
        7825 => 'z', // ẑ
        7826 => 'Z', // Ẓ
        7827 => 'z', // ẓ
        7828 => 'Z', // Ẕ
        7829 => 'z', // ẕ
        7830 => 'h', // ẖ
        7831 => 't', // ẗ
        7832 => 'w', // ẘ
        7833 => 'y', // ẙ
        7834 => 'a', // ẚ
        7835 => 's', // ẛ
        7836 => 's', // ẜ
        7837 => 's', // ẝ
        7838 => 'SS', // ẞ
        7840 => 'A', // Ạ
        7841 => 'a', // ạ
        7842 => 'A', // Ả
        7843 => 'a', // ả
        7844 => 'A', // Ấ
        7845 => 'a', // ấ
        7846 => 'A', // Ầ
        7847 => 'a', // ầ
        7848 => 'A', // Ẩ
        7849 => 'a', // ẩ
        7850 => 'A', // Ẫ
        7851 => 'a', // ẫ
        7852 => 'A', // Ậ
        7853 => 'a', // ậ
        7854 => 'A', // Ắ
        7855 => 'a', // ắ
        7856 => 'A', // Ằ
        7857 => 'a', // ằ
        7858 => 'A', // Ẳ
        7859 => 'a', // ẳ
        7860 => 'A', // Ẵ
        7861 => 'a', // ẵ
        7862 => 'A', // Ặ
        7863 => 'a', // ặ
        7864 => 'E', // Ẹ
        7865 => 'e', // ẹ
        7866 => 'E', // Ẻ
        7867 => 'e', // ẻ
        7868 => 'E', // Ẽ
        7869 => 'e', // ẽ
        7870 => 'E', // Ế
        7871 => 'e', // ế
        7872 => 'E', // Ề
        7873 => 'e', // ề
        7874 => 'E', // Ể
        7875 => 'e', // ể
        7876 => 'E', // Ễ
        7877 => 'e', // ễ
        7878 => 'E', // Ệ
        7879 => 'e', // ệ
        7880 => 'I', // Ỉ
        7881 => 'i', // ỉ
        7882 => 'I', // Ị
        7883 => 'i', // ị
        7884 => 'O', // Ọ
        7885 => 'o', // ọ
        7886 => 'O', // Ỏ
        7887 => 'o', // ỏ
        7888 => 'O', // Ố
        7889 => 'o', // ố
        7890 => 'O', // Ồ
        7891 => 'o', // ồ
        7892 => 'O', // Ổ
        7893 => 'o', // ổ
        7894 => 'O', // Ỗ
        7895 => 'o', // ỗ
        7896 => 'O', // Ộ
        7897 => 'o', // ộ
        7898 => 'O', // Ớ
        7899 => 'o', // ớ
        7900 => 'O', // Ờ
        7901 => 'o', // ờ
        7902 => 'O', // Ở
        7903 => 'o', // ở
        7904 => 'O', // Ỡ
        7905 => 'o', // ỡ
        7906 => 'O', // Ợ
        7907 => 'o', // ợ
        7908 => 'U', // Ụ
        7909 => 'u', // ụ
        7910 => 'U', // Ủ
        7911 => 'u', // ủ
        7912 => 'U', // Ứ
        7913 => 'u', // ứ
        7914 => 'U', // Ừ
        7915 => 'u', // ừ
        7916 => 'U', // Ử
        7917 => 'u', // ử
        7918 => 'U', // Ữ
        7919 => 'u', // ữ
        7920 => 'U', // Ự
        7921 => 'u', // ự
        7922 => 'Y', // Ỳ
        7923 => 'y', // ỳ
        7924 => 'Y', // Ỵ
        7925 => 'y', // ỵ
        7926 => 'Y', // Ỷ
        7927 => 'y', // ỷ
        7928 => 'Y', // Ỹ
        7929 => 'y', // ỹ
        7930 => 'LL', // Ỻ
        7931 => 'll', // ỻ
        7932 => 'V', // Ỽ
        7933 => 'v', // ỽ
        7934 => 'Y', // Ỿ
    ];

    public function testCodepoints()
    {
        $filter = new NormalizeLettersToAsciiFilter();

        $stringOriginal = '';
        $stringExpected = '';

        foreach ($this->codepointsToTest as $codepointAsInt => $expectedResult) {
            $stringOriginal .= \IntlChar::chr($codepointAsInt);
            $stringExpected .= $expectedResult;
        }

        $this->assertEquals($stringExpected, $filter->filter($stringOriginal));
    }
}
