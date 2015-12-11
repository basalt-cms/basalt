<?php

use Basalt\Facades\RoutesFacade;
use Symfony\Component\Routing\RouteCollection;

$facade = new RoutesFacade(new RouteCollection);

$facade->addGet('index', '/', 'Basalt\\Http\\Controllers\\PagesController@page');

$facade->addGet('dashboard', '/admin', 'Basalt\\Http\\Controllers\\AdminPanelController@dashboard');

$facade->addGet('pages', '/admin/pages', 'Basalt\\Http\\Controllers\\PagesController@pages');
$facade->addGet('newPage', '/admin/pages/new', 'Basalt\\Http\\Controllers\\PagesController@newPage');
$facade->addPost('addPage', '/admin/pages', 'Basalt\\Http\\Controllers\\PagesController@add');
$facade->addGet('editPage', '/admin/pages/{id}', 'Basalt\\Http\\Controllers\\PagesController@edit');
$facade->addPut('updatePage', '/admin/pages/{id}', 'Basalt\\Http\\Controllers\\PagesController@update');
$facade->addPost('changePagesOrder', '/admin/', 'Basalt\\Http\\Controllers\\PagesController@changeOrder');
$facade->addDelete('deletePage', '/admin/{id}', 'Basalt\\Http\\Controllers\\PagesController@delete');

$facade->addGet('plugins', '/admin/plugins', 'Basalt\\Http\\Controllers\\PluginsController@plugins');

$facade->addGet('settings', '/admin/settings', 'Basalt\\Http\\Controllers\\SettingsController@settings');
$facade->addPost('updateSettings', '/admin/settings', 'Basalt\\Http\\Controllers\\SettingsController@update');

$facade->addGet('updates', '/admin/updates', 'Basalt\\Http\\Controllers\\UpdatesController@updates');

$facade->addGet('page', '/{slug}', 'Basalt\\Http\\Controllers\\PagesController@page');

return $facade->getRouteCollection();
