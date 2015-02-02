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

    /**
     * @var Input Input like GET or POST variables.
     */
    public $input;

    /**
     * @var string HTTP method.
     */
    protected $method;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->extractInput();
        $this->setMethod();
    }

    /**
     * Return HTTP method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Prepare RequestContext.
     *
     * @param RequestContext $context
     */
    public function prepareContext(RequestContext &$context)
    {
        $context->setMethod($this->method);
    }

    /**
     * Is request sended by ajax?
     *
     * @return bool
     */
    public function isAjax()
    {
        return (isset($_SERVER['X_REQUESTED_WITH']) && $_SERVER['X_REQUESTED_WITH'] === 'XMLHttpRequest');
    }

    /**
     * Extract input variables.
     */
    protected function extractInput()
    {
        $this->input = new Input(array_merge($_GET, $_POST));
    }

    /**
     * Set HTTP method.
     */
    protected function setMethod()
    {
        if (isset($_POST[self::METHOD_OVERRIDE])) {
            $method = strtoupper($_POST[self::METHOD_OVERRIDE]);

            if ($method === self::METHOD_GET || $method === self::METHOD_POST || $method === self::METHOD_PUT || $method === self::METHOD_DELETE) {
                $this->method = $method;
                return;
            }
        }

        $this->method = $_SERVER['REQUEST_METHOD'];
    }
}