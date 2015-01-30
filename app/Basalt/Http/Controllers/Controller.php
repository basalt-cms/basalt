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

    /**
     * @param string|array $to
     * @return RedirectResponse
     * @param boolean $external
     */
    protected function redirect($to, $external = false)
    {
        if ($external) {
            if (is_array($to)) {
                $name = $to[0];
                $parameters = $to[1];
            } else {
                $name = $to;
                $parameters = [];
            }

            $url = $this->app->container->generator->generate($name, $parameters);

            return new RedirectResponse($url);
        }

        return new RedirectResponse($to);
    }
} 