<?php

namespace Basalt\Http;


class Headers implements \ArrayAccess
{
    protected $headers = [];

    public function __construct()
    {
        $this->extractHeaders();
    }

    public function extractHeaders()
    {
        foreach ($_SERVER as $key => $value) {
            $key = strtoupper($key);

            if(strpos($key, 'X_') === 0 || strpos($key, 'HTTP_') === 0) {
                $this->headers[$key] = $value;
            }
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->headers[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->headers[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->headers[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->headers[$offset]);
    }
}