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
     * Constructor.
     *
     * @param \Basalt\App $app Application.
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Provide service.
     *
     * @return void
     */
    abstract public function provide();
}
