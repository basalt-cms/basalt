<?php

namespace Basalt;

class Container
{
    public $app;
    protected $data = [];

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function __get($name)
    {
        return (is_callable($this->data[$name])) ? $this->data[$name]($this) : $this->data[$name];
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
    }
} 