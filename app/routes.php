<?php

use Basalt\Http\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add('index', new Route(
    '/', [
        '_controller' => 'Basalt\\Http\\Controllers\\PageController@page'
    ], [], [], '', [], Request::METHOD_GET));

$routes->add('dashboard', new Route(
    '/admin', [
        '_controller' => 'Basalt\\Http\\Controllers\\AdminPanelController@dashboard'
    ], [], [], '', [], Request::METHOD_GET));

$routes->add('pages', new Route(
    '/admin/pages', [
        '_controller' => 'Basalt\\Http\\Controllers\\PageController@pages'
    ], [], [], '', [], Request::METHOD_GET));

$routes->add('newPage', new Route(
    '/admin/pages/new', [
        '_controller' => 'Basalt\\Http\\Controllers\\PageController@newPage'
    ], [], [], '', [], Request::METHOD_GET));

$routes->add('addPage', new Route(
    '/admin/pages', [
        '_controller' => 'Basalt\\Http\\Controllers\\PageController@addPage'
    ], [], [], '', [], Request::METHOD_POST));

$routes->add('deletePage', new Route(
    '/admin/{id}', [
        '_controller' => 'Basalt\\Http\\Controllers\\PageController@deletePage'
    ], [], [], '', [], Request::METHOD_DELETE));

$routes->add('page', new Route(
    '/{slug}', [
    '_controller' => 'Basalt\\Http\\Controllers\\PageController@page'
], [], [], '', [], Request::METHOD_GET));

return $routes;