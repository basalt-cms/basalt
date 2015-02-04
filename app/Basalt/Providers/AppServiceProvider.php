<?php

namespace Basalt\Providers;

use Basalt\Http\Flash;
use Basalt\Http\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

class AppServiceProvider extends ServiceProvider
{
    public function provide()
    {
        $this->app->container->request = function() {
            return new Request;
        };

        $this->app->container->context = function() {
            $context = new RequestContext($_SERVER['REQUEST_URI']);
            $this->app->container->request->prepareContext($context);

            return $context;
        };

        $this->app->container->matcher = function($container) {
            return new UrlMatcher($container->routes, $container->context);
        };

        $this->app->container->generator = function($container) {
            return new UrlGenerator($container->routes, $container->context);
        };

        $this->app->container->flash = function() {
            return new Flash;
        };
    }
}