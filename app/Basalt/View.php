<?php

namespace Basalt;

use Twig_Environment;

class View
{
    protected $twig;

    /**
     * Constructor.
     *
     * @param Twig_Environment $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
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

        return $this->twig->render($name.'.html.twig', $data);
    }
}
