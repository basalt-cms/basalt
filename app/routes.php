<?php

use Basalt\Http\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add('index', new Route(
    '/', [
        '_controller' => 'Basalt\\Http\\Controllers\\PagesController@page'
    ], [], [], '', [], Request::METHOD_GET));

$routes->add('dashboard', new Route(
    '/admin', [
        '_controller' => 'Basalt\\Http\\Controllers\\AdminPanelController@dashboard'
    ], [], [], '', [], Request::METHOD_GET));

$routes->add('pages', new Route(
    '/admin/pages', [
        '_controller' => 'Basalt\\Http\\Controllers\\PagesController@pages'
    ], [], [], '', [], Request::METHOD_GET));

$routes->add('newPage', new Route(
    '/admin/pages/new', [
        '_controller' => 'Basalt\\Http\\Controllers\\PagesController@newPage' // Cuz new is a keyword
    ], [], [], '', [], Request::METHOD_GET));

$routes->add('addPage', new Route(
    '/admin/pages', [
        '_controller' => 'Basalt\\Http\\Controllers\\PagesController@add'
    ], [], [], '', [], Request::METHOD_POST));

$routes->add('editPage', new Route(
    '/admin/pages/{id}', [
        '_controller' => 'Basalt\\Http\\Controllers\\PagesController@edit'
    ], [], [], '', [], Request::METHOD_GET));

$routes->add('updatePage', new Route(
    '/admin/pages/{id}', [
        '_controller' => 'Basalt\\Http\\Controllers\\PagesController@update'
    ], [], [], '', [], Request::METHOD_PUT));

$routes->add('changeOrderPage', new Route(
    '/admin/pages/order', [
        '_controller' => 'Basalt\\Http\\Controllers\\PagesController@changeOrder'
    ], [], [], '', [], Request::METHOD_POST));

$routes->add('deletePage', new Route(
    '/admin/{id}', [
        '_controller' => 'Basalt\\Http\\Controllers\\PagesController@delete'
    ], [], [], '', [], Request::METHOD_DELETE));

$routes->add('page', new Route(
    '/{slug}', [
        '_controller' => 'Basalt\\Http\\Controllers\\PagesController@page'
    ], [], [], '', [], Request::METHOD_GET));

$routes->add('settings', new Route(
    '/admin/settings', [
        '_controller' => 'Basalt\\Http\\Controllers\\SettingsController@settings'
    ], [], [], '', [], Request::METHOD_GET));

$routes->add('updateSettings', new Route(
    '/admin/settings', [
        '_controller' => 'Basalt\\Http\\Controllers\\SettingsController@update'
    ], [], [], '', [], Request::METHOD_POST));

return $routes;