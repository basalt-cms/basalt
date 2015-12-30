<?php

namespace Basalt\Http;

use Basalt\App;
use Basalt\Auth\AuthenticationException;

class ControllerHandler
{
    protected $app;
    protected $controller;
    protected $action;
    protected $attributes = [];

    public function __construct(App $app, array $route)
    {
        $this->app = $app;

        $this->setUpControllerInfo($route);
    }

    public function handleAndReturnResponse()
    {
        try {
            $response = call_user_func_array([$this->controller, $this->action], $this->attributes);
        } catch (AuthenticationException $e) {
            return $this->handleUnauthenticated($e);
        }

        if (false === $this->validateResponse($response)) {
            return $this->handleNoResponse();
        }

        return $response;
    }

    protected function setUpControllerInfo(array $route)
    {
        list($controller, $action) = explode('@', $route['_controller']);

        $attributesKeys = array_filter(array_keys($route), function($key) {
            return strpos($key, '_') !== 0;
        });

        $this->attributes = array_intersect_key($route, array_flip($attributesKeys));

        $this->controller = new $controller($this->app);
        $this->action = $action;
    }

    protected function validateResponse($response)
    {
        return $response instanceof Response;
    }

    protected function handleUnauthenticated(AuthenticationException $e)
    {
        if ($e->getCode() == AuthenticationException::NOT_LOGGED_IN) {
            $url = $this->app['urlHelper']->toRoute('login');
        } else {
            $url = $this->app['urlHelper']->toRoute('index');
        }

        return new RedirectResponse($url);
    }

    protected function handleNoResponse()
    {
        $url = $this->app['urlHelper']->toRoute('404');

        return new RedirectResponse($url);
    }
}
