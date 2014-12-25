<?php

namespace Basalt;

class View
{
    protected $app;

    /**
     * Constructor
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Return rendered view.
     *
     * @param $name
     * @param array $data
     * @return string
     */
    public function render($name, $data = [])
    {
        $name = str_replace('.', '/', $name);

        return $this->app->container->twig->render($name.'.html.twig', $data);
    }
} 