<?php

namespace Basalt\Facades;

use Basalt\Http\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RoutesFacade
{
    protected $routeCollection;

    public function __construct(RouteCollection $routeCollection)
    {
        $this->routeCollection = $routeCollection;
    }

    public function add($name, $path, $action, $methods = [], $defaults = [], $requirements = [], $options = [], $host = '', $schemes = [])
    {
        $defaults = array_merge($defaults, [
            '_controller' => $action
        ]);

        $route = new Route($path, $defaults, $requirements, $options, $host, $schemes, $methods);

        $this->routeCollection->add($name, $route);
    }

    public function addGet($name, $path, $action, $defaults = [], $requirements = [], $options = [], $host = '', $schemes = [])
    {
        $this->add($name, $path, $action, Request::METHOD_GET, $defaults, $requirements, $options, $host, $schemes);
    }

    public function addPost($name, $path, $action, $defaults = [], $requirements = [], $options = [], $host = '', $schemes = [])
    {
        $this->add($name, $path, $action, Request::METHOD_POST, $defaults, $requirements, $options, $host, $schemes);
    }

    public function addPut($name, $path, $action, $defaults = [], $requirements = [], $options = [], $host = '', $schemes = [])
    {
        $this->add($name, $path, $action, Request::METHOD_PUT, $defaults, $requirements, $options, $host, $schemes);
    }

    public function addDelete($name, $path, $action, $defaults = [], $requirements = [], $options = [], $host = '', $schemes = [])
    {
        $this->add($name, $path, $action, Request::METHOD_DELETE, $defaults, $requirements, $options, $host, $schemes);
    }

    public function getRouteCollection()
    {
        return $this->routeCollection;
    }
}
