<?php

namespace Basalt\Providers;

use Basalt\Container;

abstract class ServiceProvider
{
    /**
     * @var \Basalt\App Application.
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Provide service.
     *
     * @return void
     */
    abstract public function provide();
}
