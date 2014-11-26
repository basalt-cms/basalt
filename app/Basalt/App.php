<?php

namespace Basalt;

use Basalt\Http\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class App
{
    public $container;

    public function __construct()
    {
        $this->container = new Container($this);

        $this->container->request = function() {
            return new Request;
        };

        $this->container->routes = function() {
            $routes = new RouteCollection();

            // TODO: Move routes to other file.
            $indexRoute = new Route(
                '/', [
                    '_controller' => 'Main:index'
                ], [], [], '', [], Request::METHOD_GET);

            $routes->add('index', $indexRoute);

            return $routes;
        };

        $this->container->context = function() {
            $context = new RequestContext($_SERVER['REQUEST_URI']);
            $context->setMethod($this->container->request->getMethod());

            return $context;
        };

        $this->container->matcher = function($container) {
            return new UrlMatcher($container->routes, $container->context);
        };

        $this->container->generator = function($container) {
          return new UrlGenerator($container->routes, $container->context);
        };
    }
}