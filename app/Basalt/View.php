<?php

namespace Basalt;

class View
{
    protected $twig;

    public function __construct()
    {
        $twigLoader = new \Twig_Loader_Filesystem(dirname(dirname(__FILE__)).'/views');
        $this->twig = new \Twig_Environment($twigLoader, [
            //'cache' => '../cache/twig',
            'autoescape' => false
        ]);
    }

    public function render($name, $data = [])
    {
        return $this->twig->render($name.'.html.twig', $data);
    }
} 