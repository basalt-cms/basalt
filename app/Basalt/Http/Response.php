<?php

namespace Basalt\Http;

use Basalt\Exceptions\WrongStatusException;

class Response
{
    protected $body;
    protected $headers;
    protected $status;
    protected $mime;

    public function __construct($body, $status = 200, $headers = [], $mime = 'text/html')
    {
        $this->body = $body;
        $this->status = $status;
        $this->headers = $headers;
        $this->mime = $mime;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        if (!is_int($status)) {
            throw new WrongStatusException;
        }

        $this->status = $status;
    }

    public function getMime()
    {
        return $this->mime;
    }

    public function setMime($mime)
    {
        $this->mime = $mime;
    }

    public function send()
    {
        header('HTTP/1.1 '.$this->status);
        header(sprintf('Content-Type: %s;charset=UTF-8', $this->mime));

        echo $this->body;
    }
} 