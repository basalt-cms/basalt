<?php

namespace Basalt\Facades;

use Basalt\Http\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RoutesFacade
{
    /**
     * @var RouteCollection Route collection.
     */
    protected $routeCollection;

    /**
     * RoutesFacade constructor.
     * @param RouteCollection $routeCollection
     */
    public function __construct(RouteCollection $routeCollection)
    {
        $this->routeCollection = $routeCollection;
    }

    /**
     * Adds route to route collection.
     *
     * @param string $name Route name
     * @param string $path Route path
     * @param string $action Route controller action
     * @param array $methods Route methods
     * @param array $defaults Route defaults
     * @param array $requirements Route requirements
     * @param array $options Route options
     * @param string $host Route host
     * @param array $schemes Route schemes
     */
    public function add($name, $path, $action, $methods = [], $defaults = [], $requirements = [], $options = [], $host = '', $schemes = [])
    {
        $defaults = array_merge($defaults, [
            '_controller' => $action
        ]);

        $route = new Route($path, $defaults, $requirements, $options, $host, $schemes, $methods);

        $this->routeCollection->add($name, $route);
    }

    /**
     * Adds GET route to route collection.
     *
     * @param string $name Route name
     * @param string $path Route path
     * @param string $action Route controller action
     * @param array $defaults Route defaults
     * @param array $requirements Route requirements
     * @param array $options Route options
     * @param string $host Route host
     * @param array $schemes Route schemes
     */
    public function addGet($name, $path, $action, $defaults = [], $requirements = [], $options = [], $host = '', $schemes = [])
    {
        $this->add($name, $path, $action, Request::METHOD_GET, $defaults, $requirements, $options, $host, $schemes);
    }

    /**
     * Adds POST route to route collection.
     *
     * @param string $name Route name
     * @param string $path Route path
     * @param string $action Route controller action
     * @param array $defaults Route defaults
     * @param array $requirements Route requirements
     * @param array $options Route options
     * @param string $host Route host
     * @param array $schemes Route schemes
     */
    public function addPost($name, $path, $action, $defaults = [], $requirements = [], $options = [], $host = '', $schemes = [])
    {
        $this->add($name, $path, $action, Request::METHOD_POST, $defaults, $requirements, $options, $host, $schemes);
    }

    /**
     * Adds PUT route to route collection.
     *
     * @param string $name Route name
     * @param string $path Route path
     * @param string $action Route controller action
     * @param array $defaults Route defaults
     * @param array $requirements Route requirements
     * @param array $options Route options
     * @param string $host Route host
     * @param array $schemes Route schemes
     */
    public function addPut($name, $path, $action, $defaults = [], $requirements = [], $options = [], $host = '', $schemes = [])
    {
        $this->add($name, $path, $action, Request::METHOD_PUT, $defaults, $requirements, $options, $host, $schemes);
    }

    /**
     * Adds DELETE route to route collection.
     *
     * @param string $name Route name
     * @param string $path Route path
     * @param string $action Route controller action
     * @param array $defaults Route defaults
     * @param array $requirements Route requirements
     * @param array $options Route options
     * @param string $host Route host
     * @param array $schemes Route schemes
     */
    public function addDelete($name, $path, $action, $defaults = [], $requirements = [], $options = [], $host = '', $schemes = [])
    {
        $this->add($name, $path, $action, Request::METHOD_DELETE, $defaults, $requirements, $options, $host, $schemes);
    }

    /**
     * Return route collection.
     *
     * @return RouteCollection Route collecton
     */
    public function getRouteCollection()
    {
        return $this->routeCollection;
    }
}
