<?php

namespace Basalt\Http;

class Response
{
    /**
     * @var string Response body.
     */
    protected $body;
    /**
     * @var int Status code.
     */
    protected $status;
    /**
     * @var string Document MIME type.
     */
    protected $mime;

    /**
     * Constructor.
     *
     * @param $body
     * @param int $status
     * @param string $mime
     */
    public function __construct($body, $status = 200, $mime = 'text/html')
    {
        $this->body = $body;
        $this->status = $status;
        $this->mime = $mime;
    }

    /**
     * Return response body.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set response body.
     *
     * @param $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Get status code.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status code.
     *
     * @param $status
     * @throws \InvalidArgumentException
     */
    public function setStatus($status)
    {
        if (!is_int($status)) {
            throw new \InvalidArgumentException;
        }

        $this->status = $status;
    }

    /**
     * Get document MIME type.
     *
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Set document MIME type.
     *
     * @param $mime
     */
    public function setMime($mime)
    {
        $this->mime = $mime;
    }

    /**
     * Send response to client.
     */
    public function send()
    {
        header('HTTP/1.1 '.$this->status);
        header(sprintf('Content-Type: %s;charset=UTF-8', $this->mime));

        echo $this->body;
    }
} 