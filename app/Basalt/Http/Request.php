<?php

namespace Basalt\Http;

use Symfony\Component\Routing\RequestContext;

class Request
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    const METHOD_OVERRIDE = '_METHOD';

    protected $input;
    protected $method;

    public function __construct()
    {
        $this->extractInput();
        $this->setMethod();
    }

    public function get($name, $default = null)
    {
        return (isset($this->input[$name])) ? $this->input[$name] : $default;
    }

    public function exists($name)
    {
        return isset($this->input[$name]);
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function prepareContext(RequestContext &$context)
    {
        $context->setMethod($this->method);
    }

    public function isAjax()
    {
        return (isset($_SERVER['X_REQUESTED_WITH']) && $_SERVER['X_REQUESTED_WITH'] === 'XMLHttpRequest');
    }

    protected function extractInput()
    {
        $this->input = array_merge($_GET, $_POST);
    }

    protected function setMethod()
    {
        if (isset($_REQUEST[self::METHOD_OVERRIDE])) {
            $method = strtoupper($_REQUEST[self::METHOD_OVERRIDE]);

            if ($method === self::METHOD_GET || $method === self::METHOD_POST || $method === self::METHOD_PUT || $method === self::METHOD_DELETE) {
                $this->method = $method;
                return;
            }
        }

        $this->method = $_SERVER['REQUEST_METHOD'];
    }
}