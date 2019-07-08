<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\Common\RegexExceptionTrait;
use Squirrel\Strings\StringFilterInterface;

/**
 * Forcefully wrap long words by adding spaces after a certain number of characters
 *
 * - HTML tags are preserved if $htmlAllowed is set to true
 * - HTML tags count as one character each
 */
class WrapLongWordsFilter implements StringFilterInterface
{
    use RegexExceptionTrait;

    /**
     * @var int
     */
    private $maxCharacters = 80;

    /**
     * @var bool
     */
    private $htmlAllowed = false;

    /**
     * @param int $maxCharacters
     * @param bool $htmlAllowed
     */
    public function __construct(int $maxCharacters = 80, bool $htmlAllowed = false)
    {
        $this->maxCharacters = $maxCharacters;
        $this->htmlAllowed = $htmlAllowed;
    }

    public function filter(string $string): string
    {
        // No HTML allowed - this makes it easier to wrap
        if ($this->htmlAllowed === false) {
            return $this->wrapNonHTML($string);
        }

        // Factor in HTML tags
        return $this->wrapWithHTML($string);
    }

    /**
     * Wrap long words and do not factor in HTML
     *
     * @param string $string
     * @return string
     */
    private function wrapNonHTML(string $string): string
    {
        $string = \preg_replace("/([^ \n]{" . $this->maxCharacters . '})(?=[^ \n])/siu', "\\1 ", $string);

        // @codeCoverageIgnoreStart
        if ($string === null) {
            throw $this->generateRegexException();
        }
        // @codeCoverageIgnoreEnd

        return $string;
    }

    /**
     * Wrap long words without disturbing existing HTML tags
     *
     * @param string $string
     * @return string
     */
    private function wrapWithHTML(string $string): string
    {
        // Look for HTML tags
        if (\preg_match_all('/([<][^>]+[>])/si', $string, $tags)) {
            // Exchange all tags with character 17
            $string = \preg_replace('/[<][^>]+[>]/si', \chr(17), $string);

            // @codeCoverageIgnoreStart
            if ($string === null) {
                throw $this->generateRegexException();
            }
            // @codeCoverageIgnoreEnd

            // Add extra spaces to break up long words
            $string = \preg_replace("/([^ \n]{" . $this->maxCharacters . '})(?=[^ \n])/siu', "\\1 ", $string);

            // @codeCoverageIgnoreStart
            if ($string === null) {
                throw $this->generateRegexException();
            }
            // @codeCoverageIgnoreEnd

            // Put HTML tags back into the string - exchange characters 17 with HTML tags
            foreach ($tags[0] as $key => $val) {
                $string = \preg_replace('/' . \chr(17) . '/si', $tags[0][$key], $string, 1);

                // @codeCoverageIgnoreStart
                if ($string === null) {
                    throw $this->generateRegexException();
                }
                // @codeCoverageIgnoreEnd
            }

            return $string;
        } else { // No HTML tags found - use the non-HTML wrapper
            return $this->wrapNonHTML($string);
        }
    }
}
