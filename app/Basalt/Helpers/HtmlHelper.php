<?php

namespace Basalt\Helpers;

use Basalt\Http\Flash;
use Basalt\Http\Request;
use Twig_Extension;
use Twig_SimpleFunction;

class HtmlHelper extends Twig_Extension
{
    /**
     * @var \Basalt\App Application.
     */
    protected $urlHelper;

    /**
     * @var \Basalt\Http\Input Flashed input.
     */
    protected $flash;

    /**
     * Constructor.
     *
     * @param Flash $flash
     * @param UrlHelper $urlHelper
     */
    public function __construct(Flash $flash, UrlHelper $urlHelper)
    {
        $this->urlHelper = $urlHelper;
        $this->flash = unserialize($flash->get('input'));
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'HtmlHelper';
    }

    /**
     * Return HTML form tag.
     *
     * @param string|array $route Route name and optional parameters for action.
     * @param array $parameters HTML parameters.
     * @return string
     */
    public function form($route, $parameters = [])
    {
        if (is_array($route)) {
            list($routeName, $routeParameters) = $route;
        } else {
            $routeName = $route;
            $routeParameters = [];
        }

        $url = $this->urlHelper->toRoute($routeName, $routeParameters);
        $method = $this->urlHelper->getMethod($routeName);
        $parameters = $this->buildParameters($parameters);

        $html = sprintf('<form action="%s" method="POST"%s>', $url, $parameters);

        if ($method != 'POST') {
            $html .= sprintf('<input type="hidden" name="%s" value="%s">', Request::METHOD_OVERRIDE, $method);
        }

        return $html;
    }

    /**
     * Return HTML close form tag.
     *
     * @return string
     */
    public function closeForm()
    {
        return '</form>';
    }

    /**
     * Return HTML input tag.
     *
     * @param string $type Input tag type.
     * @param string $name Name.
     * @param string $default Default value.
     * @param array $parameters HTML parameters.
     * @return string
     */
    public function input($type, $name = '', $default = '', $parameters = [])
    {
        $default = (isset($this->flash[$name])) ? $this->flash[$name] : $default;
        $default = htmlspecialchars($default, ENT_QUOTES, ini_get('default_charset'), false);
        $default = (empty($default)) ? '' : sprintf(' value="%s"', $default);

        $parameters = $this->buildParameters($parameters);

        return sprintf('<input type="%s" name="%s"%s>', $type, $name, $default . $parameters);
    }

    /**
     * Return HTML email input tag.
     *
     * @param string $name Name.
     * @param string $default Default value.
     * @param array $parameters HTML parameters.
     * @return string
     */
    public function email($name, $default = '', $parameters = [])
    {
        return $this->input('email', $name, $default, $parameters);
    }

    /**
     * Return HTML password input tag.
     *
     * @param string $name Name.
     * @param array $parameters HTML parameters.
     * @return string
     */
    public function password($name, $parameters = [])
    {
        return $this->input('password', $name, '', $parameters);
    }

    /**
     * Return HTML text input tag.
     *
     * @param string $name Name.
     * @param string $default Default value.
     * @param array $parameters HTML parameters.
     * @return string
     */
    public function text($name, $default = '', $parameters = [])
    {
        return $this->input('text', $name, $default, $parameters);
    }

    /**
     * Return HTML textarea tag.
     *
     * @param string $name Name.
     * @param string $default Default value.
     * @param array $parameters HTML parameters.
     * @return string
     */
    public function textarea($name, $default = '', $parameters = [])
    {
        $default = (isset($this->flash[$name])) ? $this->flash[$name] : $default;
        $parameters = $this->buildParameters($parameters);

        return sprintf('<textarea name="%s"%s>%s</textarea>', $name, $parameters, $default);
    }

    /**
     * Return HTML submit input tag.
     *
     * @param string $text Submit button label.
     * @param array $parameters HTML parameters.
     * @return string
     */
    public function submit($text, $parameters = [])
    {
        $parameters = $this->buildParameters($parameters);

        return sprintf('<input type="submit" value="%s"%s>', $text, $parameters);
    }

    /**
     * Build string with parameters ready to paste into the HTML tag.
     *
     * @param array $parameters HTML parameters
     * @return string
     */
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
