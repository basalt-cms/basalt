<?php

namespace Basalt\Helpers;

use Basalt\App;
use Basalt\Http\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;

class HtmlHelper
{
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function form($routeName, $parameters = [])
    {
        $route = $this->app->container->routes->get($routeName);
        $method = $route->getMethods()[0];
        $url = $this->app->container->mainUrl.'index.php/'.$this->app->container->generator->generate($routeName, [], UrlGenerator::RELATIVE_PATH); // TODO: Embrace this brothel

        $params = $parameters;

        $parameters = '';
        foreach ($params as $key => $value) {
            $parameters .= ' '.$key.'="'.$value.'"';
        }

        $html = '<form action="'.$url.'" method="POST"'.$parameters.'>';

        if ($method != Request::METHOD_POST) {
            $html .= '<input type="hidden" name="'. Request::METHOD_OVERRIDE.'" value="'.$method.'">';
        }

        return $html;
    }

    public function endForm()
    {
        return '</form>';
    }
}