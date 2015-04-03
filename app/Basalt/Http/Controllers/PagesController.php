<?php

namespace Basalt\Http\Controllers;

use Basalt\Database\Page;
use Basalt\Database\PageMapper;
use Basalt\Database\SettingMapper;
use Basalt\Validator\ValidationException;
use Basalt\Http\Response;

class PagesController extends Controller
{
    protected $dataMapper;
    
    public function __construct($app)
    {
        parent::__construct($app);

        $this->dataMapper = new PageMapper($this->app->container->pdo);
    }
    
    public function page($slug = null)
    {
        $menu = $this->dataMapper->all();

        if (is_null($slug)) {
            $page = $this->dataMapper->getIndex();
        } else {
            $page = $this->dataMapper->getBySlug($slug);
        }

        $settingsMapper = new SettingMapper($this->app->container->pdo);
        $website_name = $settingsMapper->get('website_name');
        $website_author = $settingsMapper->get('website_author');

        return $this->render('page', compact('menu', 'page', 'settings', 'website_name', 'website_author'));
    }

    public function pages()
    {
        $pages = $this->dataMapper->all(true);

        $message = $this->getFlash('message');

        return $this->render('admin.pages.pages', compact('pages', 'message'));
    }

    public function newPage()
    {
        $errors = $this->getFlash('errors');

        return $this->render('admin.pages.new', compact('errors'));
    }

    public function add()
    {
        $input = $this->app->container->request->input;

        $page = new Page;
        $page->name = $input['name'];
        $page->slug = $input['slug'];
        $page->content = $input['content'];
        $page->draft = isset($input['draft']);

        try {
            $this->dataMapper->save($page);

            $this->flash('message', 'Page has been added successful.');

            return $this->redirect('pages');
        } catch (ValidationException $e) {
            $this->flash('errors', $e->getErrors());
            $this->flash('input', serialize($this->app->container->request->input));

            return $this->redirect('newPage');
        }
    }

    public function edit($id)
    {
        $page = $this->dataMapper->getById($id);

        $errors = $this->getFlash('errors');

        return $this->render('admin.pages.edit', compact('page', 'errors'));
    }

    public function update($id)
    {
        $input = $this->app->container->request->input;

        $page = $this->dataMapper->getById($id);
        $page->name = $input['name'];
        $page->slug = $input['slug'];
        $page->content = $input['content'];
        $page->draft = isset($input['draft']);

        try {
            $this->dataMapper->save($page);

            $this->flash('message', 'Page has been updated successful.');

            return $this->redirect('pages');
        } catch (ValidationException $e) {
            $this->flash('errors', $e->getErrors());
            $this->flash('input', serialize($this->app->container->request->input));

            return $this->redirect(['editPage', ['id' => $id]]);
        }
    }

    public function changeOrder()
    {
        $order = $this->app->container->request->input['item'];

        $this->dataMapper->changeOrder($order);

        return new Response;
    }

    public function delete($id)
    {
        if (1 !== $id) {
            $this->dataMapper->delete($id);

            $this->flash('message', 'Page has been deleted successful.');
        }

        return $this->redirect('pages');
    }
}