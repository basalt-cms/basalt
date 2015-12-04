<?php

namespace Basalt\Http;

class Input implements \ArrayAccess
{
    /**
     * @var array Array with input stuff.
     */
    protected $input;

    /**
     * Constructor.
     *
     * @param array $input Input.
     */
    public function __construct(array $input)
    {
        $this->input = $input;
    }

    public function offsetExists($offset)
    {
        return isset($this->input[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->input[$offset]) ? $this->input[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        // You cannot change it.
    }

    public function offsetUnset($offset)
    {
        // You cannot unset it.
    }
}
