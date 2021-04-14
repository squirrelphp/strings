<?php

namespace Squirrel\Strings\Filter;

use Squirrel\Strings\StringFilterInterface;

/**
 * Streamline email format, converting domain part to lowercase and keeping the username part case-sensitive
 */
class StreamlineEmailFilter implements StringFilterInterface
{
    public function filter(string $string): string
    {
        if (\strpos($string, '@') === false) {
            return $string;
        }

        // Split up email in username and domain part
        $emailParts = \explode('@', $string);

        // The first part is always the username part
        $username = \array_shift($emailParts);

        // All the other parts are the domain part
        $domain = \implode('@', $emailParts);

        $lowercaseFilter = new LowercaseFilter();

        // Only lowercase the domain part, username can be case sensitive
        return $username . '@' . $lowercaseFilter->filter($domain);
    }
}
