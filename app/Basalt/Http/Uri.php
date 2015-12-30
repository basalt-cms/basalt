<?php

namespace Basalt\Http;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    /**
     * @var array
     */
    protected $allowedSchemes = [
        'http' => 80,
        'https' => 443
    ];

    /**
     * @var string
     */
    private $scheme = '';

    /**
     * @var string
     */
    private $userInfo = '';

    /**
     * @var string
     */
    private $host = '';

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $path = '';

    /**
     * @var string
     */
    private $query = '';

    /**
     * @var string
     */
    private $fragment = '';

    /**
     * Generated URI string.
     *
     * @var string|null
     */
    private $uriString;

    /**
     * Uri constructor.
     * @param string $uri
     * @throws InvalidArgumentException
     */
    public function __construct($uri = '')
    {
        if (!is_string($uri)) {
            throw new InvalidArgumentException('URI passed to constructor must be a string.');
        }

        if (!empty($uri)) {
            $this->parseUri($uri);
        }
    }

    /**
     * Uri clone magic method.
     *
     * Set URI string to null to re-generate it.
     */
    public function __clone()
    {
        $this->uriString = null;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        if (null !== $this->uriString) {
            return $this->uriString;
        }

        $this->uriString = static::createUriString(
            $this->scheme,
            $this->getAuthority(),
            $this->getPath(),
            $this->query,
            $this->fragment
        );

        return $this->uriString;
    }

    /**
     * Creates URI string.
     *
     * @param $scheme
     * @param $authority
     * @param $path
     * @param $query
     * @param $fragment
     * @return string
     */
    private static function createUriString($scheme, $authority, $path, $query, $fragment)
    {
        $uri = '';
        
        if (!empty($scheme)) {
            $uri .= sprintf('%s://', $scheme);
        }
        
        if (!empty($authority)) {
            $uri .= $authority;
        }
        
        if ($path) {
            if (empty($path) || '/' !== substr($path, 0, 1)) {
                $path = '/' . $path;
            }
            
            $uri .= $path;
        }
        
        if ($query) {
            $uri .= sprintf('?%s', $query);
        }
        
        if ($fragment) {
            $uri .= sprintf('#%s', $query);
        }
        
        return $uri;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthority()
    {
        if (empty($this->host)) {
            return '';
        }

        $authority = $this->host;

        if (!empty($this->userInfo)) {
            $authority = $this->userInfo . '@' . $authority;
        }

        if ($this->isNonStandardPort($this->scheme, $this->scheme, $this->port)) {
            $authority .= ':' . $this->port;
        }

        return $authority;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }

    /**
     * {@inheritdoc}
     */
    public function getHost()
    {
        return $this->getHost();
    }

    /**
     * {@inheritdoc}
     */
    public function getPort()
    {
        return $this->isNonStandardPort($this->scheme, $this->host, $this->port) ? $this->port : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * {@inheritdoc}
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * {@inheritdoc}
     */
    public function withScheme($scheme)
    {
        $scheme = $this->filterScheme($scheme);

        if ($scheme === $this->scheme) {
            return clone $this;
        }

        $uri = clone $this;
        $uri->scheme = $scheme;

        return $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function withUserInfo($user, $password = null)
    {
        $userInfo = $user;
        if ($password) {
            $userInfo .= ':' . $password;
        }

        if ($userInfo === $this->userInfo) {
            return clone $this;
        }

        $uri = clone $this;
        $uri->userInfo = $userInfo;

        return $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function withHost($host)
    {
        if ($host === $this->host) {
            return clone $this;
        }

        $uri = clone $this;
        $uri->host = $host;

        return $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function withPort($port)
    {
        if (!(is_integer($port) || (is_string($port) && is_numeric($port)))) {
            throw new InvalidArgumentException('Port must be an integer or integer string.');
        }

        $port = (int) $port;

        if ($port === $this->port) {
            return clone $this;
        }

        if ($port < 1 || $port > 65535) {
            throw new InvalidArgumentException('Port must be a valid TCP/UDP port.');
        }

        $uri = clone $this;
        $uri->port = $port;

        return $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function withPath($path)
    {
        if (!is_string($path)) {
            throw new InvalidArgumentException('Path must be a string.');
        }

        if (strpos($path, '?') !== false) {
            throw new InvalidArgumentException('Path must not contain a query string.');
        }

        if (strpos($path, '#') !== false) {
            throw new InvalidArgumentException('Path must not contain a URI fragment.');
        }

        $path = $this->filterPath($path);

        if ($path === $this->path) {
            return clone $this;
        }

        $uri = clone $this;
        $uri->path = $path;

        return $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function withQuery($query)
    {
        if (!is_string($query)) {
            throw new InvalidArgumentException('Query string must be a string');
        }

        if (strpos($query, '#') !== false) {
            throw new InvalidArgumentException('Query string must not contain a URI fragment.');
        }

        $query = $this->filterQuery($query);

        if ($query === $this->query) {
            return clone $this;
        }

        $uri = clone $this;
        $uri->query = $query;

        return $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function withFragment($fragment)
    {
        $fragment = $this->filterFragment($fragment);

        if ($fragment === $this->fragment) {
            return clone $this;
        }

        $uri = clone $this;
        $uri->fragment = $fragment;

        return $uri;
    }

    /**
     * Parses URI string into class fields.
     *
     * @param string $uri
     */
    private function parseUri($uri)
    {
        $parsed  = parse_url($uri);

        if (false === $parsed) {
            throw new InvalidArgumentException('Given URI seems to be malformed.');
        }

        $this->scheme = isset($parsed['scheme']) ? $this->filterScheme($parsed['scheme']) : '';
        $this->userInfo = isset($parsed['user']) ? $parsed['user'] : '';
        $this->userInfo .= isset($parsed['pass']) ? ':' . $parsed['pass'] : '';
        $this->host = isset($parsed['host']) ? $parsed['host'] : '';
        $this->port = isset($parsed['port']) ? $parsed['port'] : null;
        $this->path = isset($parsed['path']) ? $this->filterPath($parsed['path']) : '';
        $this->query = isset($parsed['query']) ? $this->filterQuery($parsed['query']) : '';
        $this->fragment = isset($parsed['fragment']) ? $this->filterFragment($parsed['fragment']) : '';
    }

    /**
     * Is specified port non-standard for specified scheme?
     *
     * @param string $scheme
     * @param string $host
     * @param int $port
     * @return bool
     */
    private function isNonStandardPort($scheme, $host, $port)
    {
        if (!$scheme) {
            return true;
        }

        if (!$host || !$port) {
            return false;
        }

        return !isset($this->allowedSchemes[$scheme]) || $port !== $this->allowedSchemes[$scheme];
    }

    /**
     * Filters scheme.
     *
     * @param string $scheme
     * @return string
     */
    private function filterScheme($scheme)
    {
        $scheme = strtolower($scheme);
        $scheme = preg_replace('#:(//)?$#', '', $scheme);

        if (empty($scheme)) {
            return '';
        }

        if (!array_key_exists($scheme, $this->allowedSchemes)) {
            throw new InvalidArgumentException(sprintf('Invalid scheme, %s given.', $scheme));
        }

        return $scheme;
    }

    /**
     * Filters path.
     *
     * @param string $path
     * @return string
     */
    private function filterPath($path)
    {
        return preg_replace_callback(
            '/(?:[^a-zA-Z0-9_\-\.~:@&=\+\$,\/;%]+(?![A-Fa-f0-9]{2}))/',
            function ($matches) {
                return rawurlencode($matches[0]);
            },
            $path);
    }

    /**
     * Filters fragment.
     *
     * @param null|string $fragment
     * @return string
     */
    private function filterFragment($fragment)
    {
        if (null === $fragment) {
            $fragment = '';
        }

        if (!empty($fragment) && strpos($fragment, '#') === 0 ) {
            $fragment = substr($fragment, 1);
        }

        return $this->filterQueryOrFragment($fragment);
    }

    /**
     * Filters query and ensures it is properly encoded.
     *
     * @param string$query
     * @return string
     */
    private function filterQuery($query)
    {
        if (!empty($query) && strpos($query, '?') === 0) {
            $query = substr($query, 1);
        }

        $parts = explode('&', $query);
        foreach ($parts as $index => $part) {
            list($key, $value) = $this->splitQueryValue($part);

            if (is_null($value)) {
                $parts[$index] = $this->filterQueryOrFragment($key);
                continue;
            }

            $parts[$index] = sprintf('%s=%s', $this->filterQueryOrFragment($key), $this->filterQueryOrFragment($value));
        }

        return implode('&', $parts);
    }

    /**
     * Splits query value into key => value array.
     *
     * @param string $value
     * @return array
     */
    private function splitQueryValue($value)
    {
        $data = explode('=', $value, 2);

        if (1 === count($data)) {
            $data[] = null;
        }

        return $data;
    }

    /**
     * Filters query or fragment.
     *
     * @param string $value
     * @return string
     */
    private function filterQueryOrFragment($value)
    {
        return preg_replace_callback(
            '/(?:[^a-zA-Z0-9_\-\.~!\$&\\\(\)\*\+,;=%:@\/\?]+|%(?![A-Fa-f0-9]{2}))/',
            function ($matches) {
                return rawurlencode($matches[0]);
            },
            $value);
    }
}
