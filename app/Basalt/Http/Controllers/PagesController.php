<?php

namespace Basalt\Http\Controllers;

use Basalt\Database\Page;
use Basalt\Database\PageMapper;
use Basalt\Exceptions\ValidationException;
use Basalt\Http\Response;

class PagesController extends Controller
{
    public function page($slug = null)
    {
        $pageMapper = new PageMapper($this->app->container->pdo);

        $menu = $pageMapper->all();

        if (is_null($slug)) {
            $page = $pageMapper->getIndex();
        } else {
            $page = $pageMapper->getBySlug($slug);
        }

        return $this->render('page', compact('menu', 'page'));
    }

    public function pages()
    {
        $pageMapper = new PageMapper($this->app->container->pdo);

        $pages = $pageMapper->all(true);

        $message = $this->getFlash('message');

        return $this->render('admin.pages.pages', compact('pages', 'message'));
    }

    public function newPage()
    {
        return $this->render('admin.pages.new');
    }

    public function add()
    {
        $input = $this->app->container->request->input;

        $pageMapper = new PageMapper($this->app->container->pdo);

        $page = new Page;
        $page->name = $input['name'];
        $page->slug = $input['slug'];
        $page->content = $input['content'];
        $page->draft = isset($input['draft']);

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

    public function edit($id)
    {
        $pageMapper = new PageMapper($this->app->container->pdo);

        $page = $pageMapper->getById($id);

        return $this->render('admin.pages.edit', compact('page'));
    }

    public function update($id)
    {
        $input = $this->app->container->request->input;

        $pageMapper = new PageMapper($this->app->container->pdo);

        $page = $pageMapper->getById($id);
        $page->name = $input['name'];
        $page->slug = $input['slug'];
        $page->content = $input['content'];
        $page->draft = isset($input['draft']);

        try {
            $pageMapper->save($page);

            $this->flash('message', 'Page has been updated succesful.');

            return $this->redirect('pages');
        } catch (ValidationException $e) {
            $this->flash('errors', $e->getErrors());
            $this->flash('input', serialize($this->app->container->request->input));

            return $this->redirect(['editPage', ['id' => $id]]);
        }
    }

    public function changeOrder()
    {
        if ($this->app->container->request->isAjax()) {
            $order = $this->app->container->request->input['item'];

            $pageMapper = new PageMapper($this->app->container->pdo);
            $pageMapper->changeOrder($order);
        }

        return new Response;
    }

    public function delete($id)
    {
        if (1 !== $id) {
            $pageMapper = new PageMapper($this->app->container->pdo);
            $pageMapper->delete($id);

            $this->flash('message', 'Page has been deleted succesful.');
        }

        return $this->redirect('pages');
    }
}