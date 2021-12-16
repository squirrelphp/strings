<?php

namespace Squirrel\Strings;

use Squirrel\Debug\Debug;
use Squirrel\Strings\Exception\RegexException;

/**
 * Wrapper around common regex functions for better type hints and error handling
 */
final class Regex
{
    /**
     * @pure
     *
     * @throws RegexException
     */
    public static function isMatch(
        string $pattern,
        string $subject,
        int $offset = 0,
    ): bool {
        $result = @\preg_match($pattern, $subject, offset: $offset);

        if (!\is_int($result)) {
            throw self::generateRegexException();
        }

        return \boolval($result);
    }

    /**
     * @pure
     *
     * @throws RegexException
     */
    public static function getMatches(
        string $pattern,
        string $subject,
        int $flags = 0,
        int $offset = 0,
    ): ?array {
        // Include unmatched subpatterns in $matches as null instead of empty string
        $flags = $flags | PREG_UNMATCHED_AS_NULL;

        $result = @\preg_match_all($pattern, $subject, $matches, $flags, $offset);

        if (!\is_int($result)) {
            throw self::generateRegexException();
        }

        if ($result === 0) {
            return null;
        }

        return $matches;
    }

    /**
     * @pure
     *
     * @throws RegexException
     */
    public static function replace(
        string|array $pattern,
        string|array $replacement,
        string $subject,
        int $limit = -1,
    ): string {
        $result = @\preg_replace($pattern, $replacement, $subject, $limit);

        if (!\is_string($result)) {
            throw self::generateRegexException();
        }

        return $result;
    }

    /**
     * @pure
     *
     * @throws RegexException
     */
    public static function replaceArray(
        string|array $pattern,
        string|array $replacement,
        array $subject,
        int $limit = -1,
    ): array {
        $result = [];

        foreach ($subject as $key => $singleSubject) {
            $result[$key] = self::replace($pattern, $replacement, $singleSubject, $limit);
        }

        return $result;
    }

    /**
     * @throws RegexException
     */
    public static function replaceWithCallback(
        string|array $pattern,
        callable $callback,
        string $subject,
        int $limit = -1,
        int $flags = 0,
    ): string {
        $result = @\preg_replace_callback($pattern, $callback, $subject, $limit, flags: $flags);

        if (!\is_string($result)) {
            throw self::generateRegexException();
        }

        return $result;
    }

    /**
     * @throws RegexException
     */
    public static function replaceArrayWithCallback(
        string|array $pattern,
        callable $callback,
        array $subject,
        int $limit = -1,
        int $flags = 0,
    ): array {
        $result = [];

        foreach ($subject as $key => $singleSubject) {
            $result[$key] = self::replaceWithCallback($pattern, $callback, $singleSubject, $limit, flags: $flags);
        }

        return $result;
    }

    private static function generateRegexException(): \Throwable
    {
        $constants = \get_defined_constants(true)['pcre'];

        // Find the regex error code
        foreach ($constants as $name => $value) {
            if ($value === \preg_last_error()) {
                $error = $name;
            }
        }

        return Debug::createException(
            RegexException::class,
            'Regex error in ' . __CLASS__ . ': ' . ( $error ?? 'Unrecognized error code' ),
            ignoreClasses: [
                self::class,
            ],
        );
    }
}
