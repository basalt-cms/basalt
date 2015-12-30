<?php

namespace Basalt\Http;

use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Symfony\Component\Routing\RequestContext;

class Request implements RequestInterface
{
    use MessageTrait;

    const METHOD_OVERRIDE = '_METHOD';

    /**
     * @var \Basalt\Http\Input Input.
     */
    public $input;

    /**
     * @var string HTTP method.
     */
    private $method;

    /**
     * @var string|null
     */
    private $requestTarget;

    /**
     * @var UriInterface|null
     */
    private $uri;

    private $validMethods = [
        'CONNECT',
        'DELETE',
        'GET',
        'HEAD',
        'OPTIONS',
        'PATCH',
        'POST',
        'PUT',
        'TRACE'
    ];

    /**
     * Constructor.
     *
     * @param string|null $uri
     * @param string|null|UriInterface $method
     * @param string|resource|StreamInterface $body
     * @param array array $headers
     */
    public function __construct($uri = null, $method = null, $body = 'php://memory', array $headers = [])
    {
        if (!is_null($uri) && !is_string($uri) && !$uri instanceof UriInterface) {
            throw new InvalidArgumentException('URI must be a null, string or Psr\Http\Message\UriMessage instance.');
        }

        $this->validateMethod($method);

        if (!is_string($body) && !is_resource($body) && !$body instanceof StreamInterface) {
            throw new InvalidArgumentException('Body must be a string, resource or Psr\Http\Message\StreamInterface instance.');
        }

        if (is_string($uri)) {
            $uri = new Uri($uri);
        }

        $this->method = $method;
        $this->uri = $uri;
        $this->stream = ($body instanceof StreamInterface) ? $body : new Stream($body, 'r');
        list($this->headersMap, $headers) = $this->filterHeaders($headers);
        $this->headers = $headers;

        $this->extractInput();
        $this->setUpRequestTarget();
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders()
    {
        $headers = $this->getHeaders();

        if (!$this->hasHeader('host') && $this->uri && $this->uri->getHost()) {
            $headers['Host'] = [$this->getHostFromUri()];
        }

        return $headers;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader($name)
    {
        if (!$this->hasHeader($name)) {
            if (strtolower($name) == 'host' && $this->uri && $this->uri->getHost()) {
                return [$this->getHostFromUri()];
            }

            return [];
        }

        $header = $this->headersMap[strtolower($name)];

        $value = $this->headers[$header];
        $value = is_array($value) ? $value : [$value];

        return $value;
    }

    /**
     * Extract input variables.
     */
    private function extractInput()
    {
        $this->input = new Input(array_merge($_GET, $_POST));
    }

    private function setUpRequestTarget()
    {
        if (!$this->uri) {
            return '/';
        }

        $target = $this->uri->getPath();

        if ($query = $this->uri->getQuery()) {
            $target .= '?' . $query;
        }

        if (empty($target)) {
            $target = '/';
        }

        return $target;
    }

    public function makeContext()
    {
        return new RequestContext($this->uri, $this->method);
    }

    /**
     * Is request sent by ajax?
     *
     * @return bool
     */
    public function isAjax()
    {
        return (isset($_SERVER['X_REQUESTED_WITH']) && $_SERVER['X_REQUESTED_WITH'] === 'XMLHttpRequest');
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTarget()
    {
        return $this->requestTarget;
    }

    /**
     * {@inheritdoc}
     */
    public function withRequestTarget($requestTarget)
    {
        if (preg_match('#\s#', $requestTarget)) {
            throw new \InvalidArgumentException('Request cannot contain whitespace.');
        }

        $request = clone $this;

        $request->requestTarget = $requestTarget;

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * {@inheritdoc}
     */
    public function withMethod($method)
    {
        $this->validateMethod($method);
    }

    /**
     * {@inheritdoc}
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * {@inheritdoc}
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $request = clone $this;

        $request->uri = $uri;

        if ($preserveHost) {
            return $request;
        }

        if (!$uri->getHost()) {
            return $request;
        }

        $host = $uri->getHost();
        if ($port = $uri->getPort()) {
            $host .= ':' . $port;
        }

        $request->headersMap['host'] = 'Host';
        $request->headers['Host'] = [$host];

        return $request;
    }

    private function getHostFromUri()
    {
        $host = $this->uri->getHost();
        $host .= ($port = $this->uri->getPort()) ? ':' . $port : '';

        return $host;
    }

    private function validateMethod($method)
    {
        $method = strtoupper($method);

        if (!in_array($method, $this->validMethods)) {
            throw new \InvalidArgumentException(sprintf('Unsupported HTTP method %s', $method));
        }
    }
}
