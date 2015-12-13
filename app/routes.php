<?php

use Basalt\Facades\RoutesFacade;
use Symfony\Component\Routing\RouteCollection;

$routes = new RoutesFacade(new RouteCollection);

$routes->addGet('index', '/', 'Basalt\\Http\\Controllers\\PagesController@page');

$routes->addGet('login', '/admin/login', 'Basalt\\Http\\Controllers\\AuthController@login');
$routes->addPost('authenticate', '/admin/login', 'Basalt\\Http\\Controllers\\AuthController@authenticate');
$routes->addGet('logout', '/admin/logout', 'Basalt\\Http\\Controllers\\AuthController@logout');

$routes->addGet('dashboard', '/admin', 'Basalt\\Http\\Controllers\\AdminPanelController@dashboard');

$routes->addGet('pages', '/admin/pages', 'Basalt\\Http\\Controllers\\PagesController@pages');
$routes->addGet('newPage', '/admin/pages/new', 'Basalt\\Http\\Controllers\\PagesController@newPage');
$routes->addPost('addPage', '/admin/pages', 'Basalt\\Http\\Controllers\\PagesController@add');
$routes->addGet('editPage', '/admin/pages/{id}', 'Basalt\\Http\\Controllers\\PagesController@edit');
$routes->addPut('updatePage', '/admin/pages/{id}', 'Basalt\\Http\\Controllers\\PagesController@update');
$routes->addPost('changePagesOrder', '/admin/', 'Basalt\\Http\\Controllers\\PagesController@changeOrder');
$routes->addDelete('deletePage', '/admin/{id}', 'Basalt\\Http\\Controllers\\PagesController@delete');

$routes->addGet('plugins', '/admin/plugins', 'Basalt\\Http\\Controllers\\PluginsController@plugins');

$routes->addGet('settings', '/admin/settings', 'Basalt\\Http\\Controllers\\SettingsController@settings');
$routes->addPost('updateSettings', '/admin/settings', 'Basalt\\Http\\Controllers\\SettingsController@update');

$routes->addGet('updates', '/admin/updates', 'Basalt\\Http\\Controllers\\UpdatesController@updates');

$routes->addGet('page', '/{slug}', 'Basalt\\Http\\Controllers\\PagesController@page', ['slug' => '']);

return $routes->getRouteCollection();
