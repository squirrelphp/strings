<?php

namespace Squirrel\Strings;

use Squirrel\Debug\Debug;
use Squirrel\Strings\Exception\UrlException;

class Url
{
    /**
     * URL parts from parse_url
     */
    private array $urlParts = [];

    /**
     * Query string from the URL as array
     */
    private array $queryStringArray = [];

    /**
     * Initialize new URL
     */
    public function __construct(string $url)
    {
        // Parse the URL and divide them up into separate parts
        $urlParts = \parse_url($url);

        // For very bad URLs parse_url can return false
        if ($urlParts === false) {
            throw Debug::createException(UrlException::class, 'Invalid URL provided', ignoreClasses: self::class);
        }

        $this->urlParts = $urlParts;

        // Parses query string and converts it into an array
        if (isset($this->urlParts['query']) && \strlen($this->urlParts['query']) > 0) {
            $this->setQueryString($this->urlParts['query']);
        }
    }

    /**
     * Add name and value pair to query string
     */
    public function add(string $name, mixed $value): void
    {
        $this->queryStringArray[$name] = $value;
    }

    /**
     * Remove name and value pair from query string
     */
    public function remove(string $name): void
    {
        if (isset($this->queryStringArray[$name])) {
            unset($this->queryStringArray[$name]);
        }
    }

    /**
     * Checks if a query string part was defined
     */
    public function has(string $name): bool
    {
        if (isset($this->queryStringArray[$name])) {
            return true;
        }

        return false;
    }

    /**
     * Get the value of a query string part
     */
    public function get(string $name): mixed
    {
        if (isset($this->queryStringArray[$name])) {
            return $this->queryStringArray[$name];
        }

        return null;
    }

    /**
     * Get scheme of the URL, like http or https
     */
    public function getScheme(): string
    {
        return $this->urlParts['scheme'] ?? '';
    }

    /**
     * Get host of the URL, like www.example.com or en.nonsense.se
     */
    public function getHost(): string
    {
        return $this->urlParts['host'] ?? '';
    }

    /**
     * Get the path of the URL, like /product/details/67395
     */
    public function getPath(): string
    {
        return $this->urlParts['path'] ?? '/';
    }

    /**
     * Get the query of the URL, like key1=value1&key2=value2
     */
    public function getQueryString(): string
    {
        // Prepare query string
        $this->prepareQueryString();

        return $this->urlParts['query'];
    }

    /**
     * Set scheme of the URL
     */
    public function setScheme(string $scheme): void
    {
        if ($scheme !== 'http' && $scheme !== 'https') {
            throw Debug::createException(UrlException::class, 'Invalid URL scheme', ignoreClasses: self::class);
        }

        $this->urlParts['scheme'] = $scheme;
    }

    /**
     * Set host of the URL, like www.example.com or en.nonsense.se
     */
    public function setHost(string $host): void
    {
        $this->urlParts['host'] = $host;
    }

    /**
     * Set the path of the URL, like /product/details/67395
     */
    public function setPath(string $path): void
    {
        $this->urlParts['path'] = $path;
    }

    /**
     * Set the query of the URL, like key1=value1&key2=value2
     */
    public function setQueryString(string $query): void
    {
        $this->urlParts['query'] = $query;

        // Parses query string and converts it into an array
        if (\strlen($this->urlParts['query']) > 0) {
            \parse_str($this->urlParts['query'], $this->queryStringArray);
        } else {
            $this->queryStringArray = [];
        }
    }

    /**
     * Get URL with all parts included
     */
    public function getAbsoluteUrl(): string
    {
        return $this->__toString();
    }

    /**
     * Get relative URL, so no scheme or host included
     */
    public function getRelativeUrl(): string
    {
        // Prepare query string
        $this->prepareQueryString();

        // Generate changed URL
        return (isset($this->urlParts['path']) && \strlen($this->urlParts['path']) > 0 ? $this->urlParts['path'] : '/') . (isset($this->urlParts['query']) && \strlen($this->urlParts['query']) > 0 ? '?' . $this->urlParts['query'] : '');
    }

    /**
     * Put together the new query string
     */
    private function prepareQueryString(): void
    {
        $this->urlParts['query'] = \http_build_query($this->queryStringArray);
    }

    /**
     * Generate URL from given URL data
     */
    public function __toString(): string
    {
        // Prepare query string
        $this->prepareQueryString();

        // Generate changed URL
        return (isset($this->urlParts['scheme']) && \strlen($this->urlParts['scheme']) > 0 && isset($this->urlParts['host']) && \strlen($this->urlParts['host']) > 0 ? $this->urlParts['scheme'] . '://' : '') . (isset($this->urlParts['host']) && \strlen($this->urlParts['host']) > 0 ? $this->urlParts['host'] : '') . (isset($this->urlParts['path']) && \strlen($this->urlParts['path']) > 0 ? $this->urlParts['path'] : '/') . (isset($this->urlParts['query']) && \strlen($this->urlParts['query']) > 0 ? '?' . $this->urlParts['query'] : '');
    }
}
