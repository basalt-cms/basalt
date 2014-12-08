<?php

namespace Basalt\Http\Controllers;

use Basalt\Database\PageMapper;

class MainController extends Controller
{
    public function index()
    {
        $pageMapper = new PageMapper($this->app->container->pdo);
        $pages = $pageMapper->all();

        return $this->render('index', compact('pages'));
    }
} 