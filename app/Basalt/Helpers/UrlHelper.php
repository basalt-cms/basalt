<?php

namespace Basalt\Helpers;

use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RouteCollection;

class UrlHelper
{
    protected $generator;
    protected $routes;

    public function __construct(UrlGenerator $generator, RouteCollection $routes)
    {
        $this->generator = $generator;
        $this->routes = $routes;
    }

    public function mainUrl()
    {
        return rtrim(pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME), '\\').'/';
    }

    public function toRoute($name, $parameters = [])
    {
        return $this->mainUrl().'index.php/'.$this->generator->generate($name, $parameters, UrlGenerator::RELATIVE_PATH);
    }

    public function getMethod($name)
    {
        $route = $this->routes->get($name);

        return $route->getMethods()[0];
    }
}