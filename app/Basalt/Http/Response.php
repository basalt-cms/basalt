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
     * @var array Headers array.
     */
    protected $headers;

    /**
     * Constructor.
     *
     * @param string $body Response body.
     * @param int $status Status code
     * @param array $headers Headers array.
     */
    public function __construct($body = '', $status = 200, array $headers = [])
    {
        $this->body = $body;
        $this->status = $status;
        $this->headers = $headers;
    }

    public static function blank()
    {
        return new self;
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
     * @return void
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
     * @return void
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
     *
     * @return void
     */
    public function send()
    {
        header('HTTP/1.1 '.$this->status);
        foreach ($this->headers as $key => $value) {
            header($key.': '.$value);
        }


        echo $this->body;
    }
}
