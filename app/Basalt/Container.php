<?php

namespace Basalt;

class Container
{
    /**
     * @var \Basalt\App Application
     */
    public $app;
    /**
     * @var array Data.
     */
    protected $data = [];

    /**
     * Constructor.
     *
     * @param \Basalt\App $app
     */
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