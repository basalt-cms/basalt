<?php

namespace Basalt;

class View
{
    protected $app;

    /**
     * Constructor.
     *
     * @param \Basalt\App $app Application.
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Return rendered view.
     *
     * @param string $name Name of the view to render.
     * @param array $data Data.
     * @return string
     */
    public function render($name, $data = [])
    {
        $name = str_replace('.', '/', $name);

        return $this->app->container->twig->render($name.'.html.twig', $data);
    }
} 