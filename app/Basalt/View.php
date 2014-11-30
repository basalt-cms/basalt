<?php

namespace Basalt;

class View
{
    /**
     * @var \Twig_Environment Twig.
     */
    protected $twig;

    /**
     * Constructor
     */
    public function __construct()
    {
        $twigLoader = new \Twig_Loader_Filesystem(dirname(dirname(__FILE__)).'/views');
        $this->twig = new \Twig_Environment($twigLoader, [
            //'cache' => '../cache/twig',
            'autoescape' => false
        ]);
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
        return $this->twig->render($name.'.html.twig', $data);
    }
} 