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

        $message = $this->getFlash('message');

        return $this->render('admin.pages', compact('pages', 'message'));
    }

    public function newPage()
    {
        return $this->render('admin.page');
    }

    public function addPage()
    {
        $input = $this->app->container->request->input;

        $page = new Page;
        $page->name = $input['name'];
        $page->slug = $input['slug'];
        $page->content = $input['content'];
        $page->draft = isset($input['draft']);

        $pageMapper = new PageMapper($this->app->container->pdo);

        try {
            $pageMapper->save($page);

            $this->flash('message', 'Page has been added succesful.');

            return $this->redirect('pages');
        } catch (ValidationException $e) {
            $this->flash('errors', $e->getErrors());
            $this->flash('input', serialize($this->app->container->request->input));

            return $this->redirect('newPage');
        }
    }

    public function deletePage($id)
    {
        if (1 !== $id) {
            $pageMapper = new PageMapper($this->app->container->pdo);
            $pageMapper->delete($id);

            $this->flash('message', 'Page has been deleted succesful.');
        }

        return $this->redirect('pages');
    }
}