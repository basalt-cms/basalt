<?php

namespace Basalt\Http\Controllers;

use Basalt\App;
use Basalt\Http\Response;
use Basalt\View;

class Controller
{
    protected $app;
    protected $view;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->view = new View;
    }

    protected function render($name, $data = [])
    {
        $body = $this->view->render($name, $data);

        return new Response($body);
    }
} 