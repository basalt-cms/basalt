<?php

namespace Basalt\Providers;


class RouteServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function provide()
    {
        $this->container->routes = function() {
            return require dirname(dirname(dirname(__FILE__))).'/routes.php';
        };
    }
}
