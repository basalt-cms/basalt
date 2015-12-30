<?php

namespace Basalt\Facades;

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
     * Adds CONNECT route to route collection.
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
    public function addConnect($name, $path, $action, $defaults = [], $requirements = [], $options = [], $host = '', $schemes = [])
    {
        $this->add($name, $path, $action, 'CONNECT', $defaults, $requirements, $options, $host, $schemes);
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
        $this->add($name, $path, $action, 'DELETE', $defaults, $requirements, $options, $host, $schemes);
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
        $this->add($name, $path, $action, 'GET', $defaults, $requirements, $options, $host, $schemes);
    }

    /**
     * Adds HEAD route to route collection.
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
    public function addHead($name, $path, $action, $defaults = [], $requirements = [], $options = [], $host = '', $schemes = [])
    {
        $this->add($name, $path, $action, 'HEAD', $defaults, $requirements, $options, $host, $schemes);
    }

    /**
     * Adds OPTIONS route to route collection.
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
    public function addOptions($name, $path, $action, $defaults = [], $requirements = [], $options = [], $host = '', $schemes = [])
    {
        $this->add($name, $path, $action, 'OPTIONS', $defaults, $requirements, $options, $host, $schemes);
    }

    /**
     * Adds PATCH route to route collection.
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
    public function addPatch($name, $path, $action, $defaults = [], $requirements = [], $options = [], $host = '', $schemes = [])
    {
        $this->add($name, $path, $action, 'PATCH', $defaults, $requirements, $options, $host, $schemes);
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
        $this->add($name, $path, $action, 'POST', $defaults, $requirements, $options, $host, $schemes);
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
        $this->add($name, $path, $action, 'PUT', $defaults, $requirements, $options, $host, $schemes);
    }

    /**
     * Adds TRACE route to route collection.
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
    public function addTrace($name, $path, $action, $defaults = [], $requirements = [], $options = [], $host = '', $schemes = [])
    {
        $this->add($name, $path, $action, 'TRACE', $defaults, $requirements, $options, $host, $schemes);
    }

    /**
     * Returns route collection.
     *
     * @return RouteCollection Route collecton
     */
    public function getRouteCollection()
    {
        return $this->routeCollection;
    }
}
