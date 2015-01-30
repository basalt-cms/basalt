<?php

namespace Basalt\Http\Controllers;

use Basalt\Database\Page;
use Basalt\Database\PageMapper;
use Basalt\Exceptions\ValidationException;

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

    public function pages()
    {
        $pageMapper = new PageMapper($this->app->container->pdo);

        $pages = $pageMapper->all(true);

        return $this->render('admin.pages', compact('pages'));
    }

    public function newPage()
    {
        return $this->render('admin.new-page');
    }

    public function addPage()
    {
        $page = new Page;
        $page->name = $_POST['name'];
        $page->slug = $_POST['slug'];
        $page->content = $_POST['content'];
        $page->draft = isset($_POST['draft']);

        $pageMapper = new PageMapper($this->app->container->pdo);

        try {
            $pageMapper->save($page);
        } catch (ValidationException $e) {
            // Errors etc.
        }
    }
}