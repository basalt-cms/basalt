<?php

namespace Basalt\Http;

use Psr\Http\Message\StreamInterface;

trait MessageTrait
{
    /**
     * @var string
     */
    private $protocol = '1.1';

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var array Map of normalized headers' names to original ones.
     */
    private $headersMap = [];

    /**
     * @var StreamInterface|null
     */
    private $stream;

    /**
     * {@inheritdoc}
     */
    public function getProtocolVersion()
    {
        return $this->protocol;
    }

    /**
     * {@inheritdoc}
     */
    public function withProtocolVersion($version)
    {
        $message = clone $this;

        $message->protocol = $version;

        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * {@inheritdoc}
     */
    public function hasHeader($name)
    {
        return array_key_exists(strtolower($name), $this->headersMap);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader($name)
    {
        if (!$this->hasHeader($name)) {
            return [];
        }

        $header = $this->headersMap[strtolower($name)];

        $value = $this->headers[$header];
        $value = is_array($value) ? $value : [$value];

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderLine($name)
    {
        $value = $this->getHeader($name);

        if (empty($value)) {
            return '';
        }

        return implode(',', $value);
    }

    /**
     * {@inheritdoc}
     */
    public function withHeader($name, $value)
    {
        if (is_string($value)) {
            $value = [$value];
        }

        if (!is_array($value)) {
            throw new \InvalidArgumentException('Header value must be a string or array of strings.');
        }

        $headerMapName = strtolower($name);

        $message = clone $this;

        $message->headersMap[$headerMapName] = $name;
        $message->headers[$name] = $value;

        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function withAddedHeader($name, $value)
    {
        if (is_string($value)) {
            $value = [$value];
        }

        if (!is_array($value)) {
            throw new \InvalidArgumentException('Header value must be a string or array of strings.');
        }

        if (!$this->$this->hasHeader($name)) {
            return $this->withHeader($name, $value);
        }

        $headerMapName = strtolower($name);
        $header = $this->headersMap[strtolower($headerMapName)];

        $message = clone $this;

        $message->headersMap[$headerMapName] = $name;
        $message->headers[$name] = array_merge($this->headers[$header], $value);

        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function withoutHeader($name)
    {
        if (!$this->hasHeader($name)) {
            return clone $this;
        }

        $headerMapName = strtolower($name);
        $header = $this->headersMap[$headerMapName];

        $message = clone $this;
        unset($message->headers[$header], $message->headersMap[$headerMapName]);

        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->stream;
    }

    /**
     * {@inheritdoc}
     */
    public function withBody(StreamInterface $body)
    {
        $message = clone $this;

        $message->stream = $body;

        return $message;
    }

    private function filterHeaders(array $originalHeaders)
    {
        $headersMap = $headers = [];

        foreach ($originalHeaders as $header => $value) {
            if (!is_string($header) || (!is_array($value) && !is_string($value))) {
                continue;
            }

            if (!is_array($value)) {
                $value = [$value];
            }

            $headersMap[strtolower($header)] = $header;
            $headers[$header] = $value;
        }

        return [$headersMap, $headers];
    }
}
