<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;
use Squirrel\Strings\StringFilterRunner;

class StreamlineInputWithNewlinesFilter implements StringFilterInterface
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
            new NormalizeNewlinesToUnixStyleFilter(),
            new RemoveExcessSpacesFilter(),
            new LimitConsecutiveUnixNewlinesFilter(2)
        );
    }

    public function filter(string $string): string
    {
        return $this->filterRunner->filter($string);
    }
}
