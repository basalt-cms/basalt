<?php

namespace Basalt\Http\Controllers;

use Basalt\App;
use Basalt\Http\RedirectResponse;
use Basalt\Http\Response;
use Basalt\View;

class Controller
{
    /**
     * @var \Basalt\App Application.
     */
    protected $app;
    /**
     * @var \Basalt\View View.
     */
    protected $view;

    /**
     * Constructor.
     *
     * @param \Basalt\App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->view = new View($app);
    }

    /**
     * Return response.
     *
     * @param $name
     * @param array $data
     * @return \Basalt\Http\Response
     */
    protected function render($name, $data = [])
    {
        $body = $this->view->render($name, $data);

        return new Response($body);
    }

    protected function redirect($to)
    {
        return new RedirectResponse($to);
    }
} 