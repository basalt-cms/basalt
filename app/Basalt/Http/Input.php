<?php

namespace Basalt\Http;

class Input
{
    protected $input;

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