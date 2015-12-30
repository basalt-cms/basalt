<?php

namespace Basalt\Http;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface
{
    use MessageTrait;

    /**
     * Map of standard HTTP status code/reason phrases.
     *
     * @var array
     */
    private $phrases = [
        // INFORMATIONAL CODES
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        // SUCCESS CODES
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        // REDIRECTION CODES
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy', // Deprecated
        307 => 'Temporary Redirect',
        // CLIENT ERROR
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        // SERVER ERROR
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        511 => 'Network Authentication Required',
    ];

    /**
     * @var string
     */
    private $reasonPhrase = '';

    /**
     * @var int
     */
    private $statusCode = 200;

    public function __construct($body = 'php://memory', $status = 200, array $headers = [])
    {
        if (!is_string($body) && !is_resource($body) && !$body instanceof StreamInterface) {
            throw new InvalidArgumentException('Stream must be a string stream identifier, stream resource or '
                . 'Psr\Http\Message\StreamInterface implementation.');
        }

        if (null !== $status) {
            $this->validateStatus($status);
        }

        $this->stream = $body instanceof StreamInterface ? $body : new Stream($body, 'wb+');
        $this->statusCode = $status ? (int) $status : 200;

        list($this->headersMap, $headers) = $this->filterHeaders($headers);
        $this->headers = $headers;
    }

    /**
     * Fabric method for empty response.
     *
     * @return Response
     */
    public static function blank()
    {
        return new self;
    }

    /**
     * Send response to client.
     *
     * @return void
     */
    public function send()
    {
        header(sprintf('HTTP/%s ', $this->getProtocolVersion()) . $this->statusCode);

        foreach ($this->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }


        echo $this->getContents();
    }

    public function getContents()
    {
        return $this->stream->getContents();
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * {@inheritdoc}
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $this->validateStatus($code);

        $response = clone $this;
        $response->statusCode = (int) $code;
        $response->reasonPhrase = $reasonPhrase;

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getReasonPhrase()
    {
        if (!$this->reasonPhrase && isset($this->phrases[$this->statusCode])) {
            $this->reasonPhrase = $this->phrases[$this->statusCode];
        }

        return $this->reasonPhrase;
    }

    private function validateStatus($code)
    {
        if (!is_numeric($code) || is_float($code) || $code < 100 || $code >= 600) {
            throw new InvalidArgumentException('Status code must be an integer between 100 and 599.');
        }
    }
}
