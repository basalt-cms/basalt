<?php

namespace Basalt\Http\Controllers;


use Basalt\Database\PageMapper;

class PageController extends Controller
{
    public function page($slug = '')
    {
        $pageMapper = new PageMapper($this->app->container->pdo);

        $menu = $pageMapper->all();

        if (empty($slug)) {
            $page = $pageMapper->getIndex();
        } else {
            $page = $pageMapper->get($slug);
        }

        return $this->render('page', compact('menu', 'page'));
    }

    public function adminIndex()
    {
        $pageMapper = new PageMapper($this->app->container->pdo);

        $pages = $pageMapper->all(true);

        return $this->render('admin.pages', compact('pages'));
    }
}