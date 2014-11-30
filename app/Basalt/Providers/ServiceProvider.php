<?php

namespace Basalt\Providers;

use Basalt\App;

abstract class ServiceProvider
{
    /**
     * @var \Basalt\App Application.
     */
    protected $app;

    /**
     * Constructor
     *
     * @param \Basalt\App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Provide service.
     */
    abstract public function provide();
} 