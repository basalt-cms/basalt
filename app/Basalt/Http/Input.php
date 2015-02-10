<?php

namespace Basalt\Http;

class Input
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

    public function __get($name)
    {
        return $this->input[$name];
    }

    public function __isset($name)
    {
        return isset($this->input[$name]);
    }
}