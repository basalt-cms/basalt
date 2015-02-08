<?php

namespace Basalt\Helpers;

use Basalt\App;
use Basalt\Http\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig_Extension;
use Twig_SimpleFunction;

class HtmlHelper extends Twig_Extension
{
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('form', [$this, 'form']),
            new Twig_SimpleFunction('closeForm', [$this, 'closeForm']),
            new Twig_SimpleFunction('input', [$this, 'input']),
            new Twig_SimpleFunction('email', [$this, 'email']),
            new Twig_SimpleFunction('password', [$this, 'password']),
            new Twig_SimpleFunction('text', [$this, 'text']),
            new Twig_SimpleFunction('textarea', [$this, 'textarea']),
            new Twig_SimpleFunction('submit', [$this, 'submit'])
        ];
    }

    public function getName()
    {
        return 'HtmlHelper';
    }

    public function form($routeName, $parameters = [])
    {
        $route = $this->app->container->routes->get($routeName);
        $method = $route->getMethods()[0];
        $url = $this->app->container->mainUrl.'index.php/'.$this->app->container->generator->generate($routeName, [], UrlGenerator::RELATIVE_PATH); // TODO: Embrace this brothel

        $params = '';
        foreach ($parameters as $key => $value) {
            $params .= ' '.$key.'="'.$value.'"';
        }

        $html = '<form action="'.$url.'" method="POST"'.$params.'>';

        if ($method != Request::METHOD_POST) {
            $html .= '<input type="hidden" name="'. Request::METHOD_OVERRIDE.'" value="'.$method.'">';
        }

        return $html;
    }

    public function closeForm()
    {
        return '</form>';
    }

    public function input($type, $name = '', $default = '', $parameters = [])
    {
        $default = (empty($default)) ? '' : ' value="' . $default . '"';
        $parameters = $this->buildParameters($parameters);

        return sprintf('<input type="%s" name="%s"%s>', $type, $name, $default . $parameters);
    }

    public function email($name, $default = '', $parameters = [])
    {
        return $this->input('email', $name, $default, $parameters);
    }

    public function password($name, $default = '', $parameters = [])
    {
        return $this->input('password', $name, $default, $parameters);
    }

    public function text($name, $default = '', $parameters = [])
    {
        return $this->input('text', $name, $default, $parameters);
    }

    public function textarea($name, $default = '', $parameters = [])
    {
        $parameters = $this->buildParameters($parameters);

        return sprintf('<textarea name="%s"%s>%s</textarea>', $name, $parameters, $default);
    }

    public function submit($text, $parameters = [])
    {
        $parameters = $this->buildParameters($parameters);

        return sprintf('<input type="submit" value="%s"%s>', $text, $parameters);
    }

    protected function buildParameters($parameters)
    {
        $params = '';
        foreach ($parameters as $key => $value) {
            if (is_int($key)) {
                $params .= sprintf(' %s', $value);
            } else {
                $params .= sprintf(' %s="%s"', $key, $value);
            }
        }

        return $params;
    }
}