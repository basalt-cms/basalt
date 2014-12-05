<?php

namespace Basalt\Providers;


class RouteServiceProvider extends ServiceProvider
{
    public function provide()
    {
        $this->app->container->routes = function() {
            return require dirname(dirname(dirname(__FILE__))).'/routes.php';
        };
    }
}