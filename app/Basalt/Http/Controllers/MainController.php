<?php

namespace Basalt\Http\Controllers;

use Basalt\Database\PageMapper;
use Basalt\Database\SettingMapper;

class MainController extends Controller
{
    public function index()
    {
        $pageMapper = new PageMapper($this->app->container->pdo);
        $pages = $pageMapper->all();

        $settingMapper = new SettingMapper($this->app->container->pdo);
        $siteName = $settingMapper->get('site_name')->value;

        return $this->render('index', compact('pages', 'siteName'));
    }
} 