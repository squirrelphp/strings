<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;
use Squirrel\Strings\StringFilterRunner;

/**
 * Recommended way of streamlining input
 */
class StreamlineInputNoNewlinesFilter implements StringFilterInterface
{
    /**
     * @var StringFilterRunner
     */
    private $filterRunner;

    public function __construct()
    {
        $this->filterRunner = new StringFilterRunner(
            new RemoveNonUTF8CharactersFilter(),
            new ReplaceUnicodeWhitespacesFilter(),
            new ReplaceTabsWithSpacesFilter(),
            new ReplaceNewlinesWithSpacesFilter(),
            new RemoveExcessSpacesFilter()
        );
    }

    public function filter(string $string): string
    {
        return $this->filterRunner->filter($string);
    }
}
