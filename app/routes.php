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
        '_controller' => 'Basalt\\Http\\Controllers\\PageController@newPage' // Cuz new is a keyword
    ], [], [], '', [], Request::METHOD_GET));

$routes->add('addPage', new Route(
    '/admin/pages', [
        '_controller' => 'Basalt\\Http\\Controllers\\PageController@add'
    ], [], [], '', [], Request::METHOD_POST));

$routes->add('editPage', new Route(
    '/admin/pages/{id}', [
        '_controller' => 'Basalt\\Http\\Controllers\\PageController@edit'
    ], [], [], '', [], Request::METHOD_GET));

$routes->add('updatePage', new Route(
    '/admin/pages/{id}', [
        '_controller' => 'Basalt\\Http\\Controllers\\PageController@update'
    ], [], [], '', [], Request::METHOD_PUT));

$routes->add('changeOrderPage', new Route(
    '/admin/pages/order', [
        '_controller' => 'Basalt\\Http\\Controllers\\PageController@changeOrder'
    ], [], [], '', [], Request::METHOD_POST));

$routes->add('deletePage', new Route(
    '/admin/{id}', [
        '_controller' => 'Basalt\\Http\\Controllers\\PageController@delete'
    ], [], [], '', [], Request::METHOD_DELETE));

$routes->add('page', new Route(
    '/{slug}', [
        '_controller' => 'Basalt\\Http\\Controllers\\PageController@page'
    ], [], [], '', [], Request::METHOD_GET));

return $routes;