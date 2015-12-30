<?php

namespace Basalt\Http;

use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

class Stream implements StreamInterface
{
    /**
     * @var resource
     */
    protected $resource;

    /**
     * @var string|resource
     */
    protected $stream;

    /**
     * Stream constructor.
     *
     * @param string|resource $stream
     * @param string $mode
     * @throws InvalidArgumentException
     */
    public function __construct($stream, $mode = 'r')
    {
        $this->stream = $stream;

        if (is_resource($stream)) {
            $this->resource = $stream;
        } elseif (is_string($stream)) {
            set_error_handler(function ($errno, $errstr) {
                throw new InvalidArgumentException('File provided for stream must be a valid path with valid permissions');
            }, E_WARNING);

            $this->resource = fopen($stream, $mode);

            restore_error_handler();
        } else {
            throw new InvalidArgumentException('Provided stream must be a string stream identifier or resource');
        }
    }

    public static function createFromContent($content)
    {
        $stream = fopen('php://temp', 'r+');

        if (!empty($content)) {
            fwrite($stream, $content);
            fseek($stream, 0);
        }

        return new self($stream);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        if (!$this->isReadable()) {
            return '';
        }

        try {
            $this->rewind();

            return $this->getContents();
        } catch (RuntimeException $e) {
            return '';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        if (!$this->resource) {
            return;
        }

        $resource = $this->detach();
        fclose($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function detach()
    {
        $resource = $this->resource;
        $this->resource = null;

        return $resource;
    }

    /**
     * {@inheritdoc}
     */
    public function getSize()
    {
        if (null === $this->resource) {
            return null;
        }

        return fstat($this->resource)['size'];
    }

    /**
     * {@inheritdoc}
     */
    public function tell()
    {
        if (!$this->resource) {
            throw new RuntimeException('No resource available; cannot tell position.');
        }

        $result = ftell($this->resource);

        if (!is_int($result)) {
            throw new RuntimeException('Error occurred during tell operation.');
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function eof()
    {
        if (!$this->resource) {
            return true;
        }

        return feof($this->resource);
    }

    /**
     * {@inheritdoc}
     */
    public function isSeekable()
    {
        if (!$this->resource) {
            return false;
        }

        return stream_get_meta_data($this->resource)['seekable'];
    }

    /**
     * {@inheritdoc}
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if (!$this->resource) {
            throw new RuntimeException('No resource available; cannot seek position');
        }

        if (!$this->isSeekable()) {
            throw new RuntimeException('Stream is not seekable.');
        }

        $result = fseek($this->resource, $offset, $whence);

        if (0 !== $result) {
            throw new RuntimeException('Error seeking within stream.');
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        return $this->seek(0);
    }

    /**
     * {@inheritdoc}
     */
    public function isWritable()
    {
         if (!$this->resource) {
             return false;
         }

        $uri = stream_get_meta_data($this->resource)['uri'];

        return is_writeable($uri);
    }

    /**
     * {@inheritdoc}
     */
    public function write($string)
    {
        if (!$this->resource) {
            throw new RuntimeException('No resource available; cannot write.');
        }

        $result = fwrite($this->resource, $string);

        if (false === $result) {
            throw new RuntimeException('Error writing to stream');
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function isReadable()
    {
        if (!$this->resource) {
            return false;
        }

        $mode = stream_get_meta_data($this->resource)['mode'];

        return strstr($mode, 'r') || strstr($mode, '+');
    }

    /**
     * {@inheritdoc}
     */
    public function read($length)
    {
        if (!$this->resource) {
            throw new RuntimeException('No resource available; cannot read');
        }

        if (!$this->isReadable()) {
            throw new RuntimeException('Stream is not readable');
        }

        $result = fread($this->resource, $length);

        if (false === $result) {
            throw new RuntimeException('Error reading stream.');
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getContents()
    {
        if (!$this->isReadable()) {
            return '';
        }

        $result = stream_get_contents($this->resource);

        if (false === $result) {
            throw new RuntimeException('Error reading from stream');
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata($key = null)
    {
        if (null === $key) {
            return stream_get_meta_data($this->resource);
        }

        $metadata = stream_get_meta_data($this->resource);

        if (!array_key_exists($key, $metadata)) {
            return null;
        }

        return $metadata[$key];
    }
}
