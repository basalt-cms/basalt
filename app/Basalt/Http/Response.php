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
     * Constructor.
     *
     * @param $body
     * @param int $status
     * @param string $mime
     */
    public function __construct($body, $status = 200)
    {
        $this->body = $body;
        $this->status = $status;
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
     * Send response to client.
     */
    public function send()
    {
        header('HTTP/1.1 '.$this->status);

        echo $this->body;
    }
} 