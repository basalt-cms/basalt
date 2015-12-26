<?php

namespace Basalt\Http\Controllers;

use Basalt\Database\Page;
use Basalt\Database\PageMapper;
use Basalt\Database\SettingMapper;
use Basalt\Http\Response;

class PagesController extends Controller
{
    protected $dataMapper;
    
    public function __construct($app)
    {
        parent::__construct($app);

        $this->dataMapper = new PageMapper($this->app['pdo']);
    }
    
    public function page($slug = '')
    {
        $menu = $this->dataMapper->all();

        if (empty($slug)) {
            $page = $this->dataMapper->getIndex();
        } else {
            $page = $this->dataMapper->getBySlug($slug);
        }

        $settingsMapper = new SettingMapper($this->app['pdo']);
        $website_name = $settingsMapper->get('website_name');
        $website_author = $settingsMapper->get('website_author');

        return $this->render('page', compact('menu', 'page', 'settings', 'website_name', 'website_author'));
    }

    public function pages()
    {
        $this->authorize();

        $pages = $this->dataMapper->all(true);

        $message = $this->getFlash('message');

        return $this->render('admin.pages.pages', compact('pages', 'message'));
    }

    public function newPage()
    {
        $this->authorize();

        $page = new Page;
        $errors = $this->getFlash('errors');

        return $this->render('admin.pages.form', compact('page', 'errors'));
    }

    public function add()
    {
        $this->authorize();

        $input = $this->app['request']->input;

        $page = new Page;
        $page->name = $input['name'];
        $page->slug = $input['slug'];
        $page->content = $input['content'];
        $page->draft = isset($input['draft']);

        $validator = $page->validator();

        if($validator->fails()) {
            $this->setFlash('errors', $validator->getErrors());
            $this->setFlash('input', serialize($this->app['request']->input));

            return $this->redirect('newPage');
        } else {
            $this->dataMapper->save($page);

            $this->setFlash('message', 'Page has been added successful.');

            return $this->redirect('pages');
        }
    }

    public function edit($id)
    {
        $this->authorize();

        $page = $this->dataMapper->getById($id);

        $errors = $this->getFlash('errors');

        return $this->render('admin.pages.form', compact('page', 'errors'));
    }

    public function update($id)
    {
        $this->authorize();

        $input = $this->app['request']->input;

        $page = $this->dataMapper->getById($id);
        $page->name = $input['name'];
        $page->slug = $input['slug'];
        $page->content = $input['content'];
        $page->draft = isset($input['draft']);

        $validator = $page->validator();

        if ($validator->fails()) {
            $this->setFlash('errors', $validator->getErrors());
            $this->setFlash('input', serialize($this->app['request']->input));

            return $this->redirect(['editPage', ['id' => $id]]);
        } else {
            $this->dataMapper->save($page);

            $this->setFlash('message', 'Page has been updated successful.');

            return $this->redirect('pages');
        }
    }

    public function changeOrder()
    {
        $this->authorize();

        $order = $this->app['request']->input['item'];

        $this->dataMapper->changeOrder($order);

        return Response::blank();
    }

    public function delete($id)
    {
        $this->authorize();

        if (1 !== $id) {
            $this->dataMapper->delete($id);

            $this->setFlash('message', 'Page has been deleted successful.');
        }

        return $this->redirect('pages');
    }
}
