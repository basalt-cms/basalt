<?php

namespace Basalt\Providers;

use Basalt\Container;
use Basalt\Helpers\UrlHelper;
use Basalt\Http\Flash;
use Basalt\Http\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;

class AppServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function provide()
    {
        $this->container->request = function() {
            if (isset($_POST[Request::METHOD_OVERRIDE])) {
                $method = strtoupper($_POST[Request::METHOD_OVERRIDE]);
            } else {
                $method = $_SERVER['REQUEST_METHOD'];
            }

            return new Request($_SERVER['REQUEST_URI'], $method, 'php://memory', apache_request_headers());
        };

        $this->container->context = function(Container $container) {
            return $container->request->makeContext();
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
