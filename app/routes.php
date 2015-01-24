<?php

use Basalt\Http\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add('index', new Route(
    '/', [
        '_controller' => 'Basalt\\Http\\Controllers\\PageController@page'
    ], [], [], '', [], Request::METHOD_GET));

$routes->add('page', new Route(
    '/{slug}', [
        '_controller' => 'Basalt\\Http\\Controllers\\PageController@page'
    ], [], [], '', [], Request::METHOD_GET));

$routes->add('pages', new Route(
    '/admin/pages', [
        '_controller' => 'Basalt\\Http\\Controllers\\PageController@pages'
    ], [], [], '', [], Request::METHOD_GET));

//$pages

return $routes;