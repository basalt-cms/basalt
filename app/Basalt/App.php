<?php

namespace Basalt;

use Basalt\Http\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

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
            return require dirname(dirname(__FILE__)).'/routes.php';
        };

        $this->container->context = function() {
            $context = new RequestContext($_SERVER['REQUEST_URI']);
            $this->container->request->prepareContext($context);
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

    public function run()
    {
        $this->prepareResponse();

        $this->container->response->send();
    }

    protected function prepareResponse()
    {
        $this->container->response = function($container) {
            $route = $container->matcher->match(isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/');
            list($controller, $action) = explode('@', $route['_controller']);

            $attributes_keys = array_filter(array_keys($route), function($key) {
                return strpos($key, '_') !== 0;
            });
            $attributes = array_intersect_key($route, array_flip($attributes_keys));

            $controller = new $controller($this);
            $response = call_user_func_array([$controller, $action], $attributes);

            return $response;
        };
    }
}