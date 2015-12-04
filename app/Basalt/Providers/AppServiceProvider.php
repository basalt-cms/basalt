<?php

namespace Basalt\Providers;

use Basalt\Container;
use Basalt\Helpers\UrlHelper;
use Basalt\Http\Flash;
use Basalt\Http\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

class AppServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function provide()
    {
        $this->container->request = function() {
            return new Request;
        };

        $this->container->context = function() {
            $context = new RequestContext($_SERVER['REQUEST_URI']);
            $this->container->request->prepareContext($context);

            return $context;
        };

        $this->container->matcher = function(Container $container) {
            return new UrlMatcher($container->routes, $container->context);
        };

        $this->container->generator = function(Container $container) {
            return new UrlGenerator($container->routes, $container->context);
        };

        $this->container->urlHelper = function(Container $container) {
            return new UrlHelper($container->generator, $container->routes);
        };

        $this->container->flash = function() {
            return new Flash;
        };
    }
}
