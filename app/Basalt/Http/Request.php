<?php

namespace Basalt\Http;

class Request
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    const METHOD_OVERRIDE = '_METHOD';

    protected $headers;
    protected $input;

    public function __construct()
    {
        $this->headers = new Headers;

        $this->extractInput();
    }

    public function getMethod()
    {
        if (isset($_REQUEST[self::METHOD_OVERRIDE])) {
            $method = strtoupper($_REQUEST[self::METHOD_OVERRIDE]);

            if($method === self::METHOD_GET || $method === self::METHOD_POST || $method === self::METHOD_PUT || $method === self::METHOD_DELETE) {
                return $method;
            }
        }

        return $_SERVER['REQUEST_METHOD'];
    }

    public function isGet()
    {
        return $this->getMethod() === self::METHOD_GET;
    }

    public function isPost()
    {
        return $this->getMethod() === self::METHOD_POST;
    }

    public function isPut()
    {
        return $this->getMethod() === self::METHOD_PUT;
    }

    public function isDelete()
    {
        return $this->getMethod() === self::METHOD_DELETE;
    }

    public function isAjax()
    {
        if (isset($this->headers['X_REQUESTED_WITH']) && $this->headers['X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return true;
        }

        return false;
    }

    public function get($name, $default = null)
    {
        return (isset($this->input[$name])) ? $this->input[$name] : $default;
    }

    public function exists($name)
    {
        return isset($this->input[$name]);
    }

    protected function extractInput()
    {
        $this->input = array_merge($_GET, $_POST);
    }
}