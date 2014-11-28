<?php

use Basalt\Http\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$indexRoute = new Route(
    '/', [
        '_controller' => 'Basalt\\Http\\Controllers\\MainController@index'
    ], [], [], '', [], Request::METHOD_GET);

$routes->add('index', $indexRoute);

return $routes;