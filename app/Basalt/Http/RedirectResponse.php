<?php

namespace Basalt\Http;

class RedirectResponse extends Response
{
    /**
     * Constructor.
     *
     * @param string $to URL to redirect.
     * @param int $status Status code.
     * @param array $headers Headers array.
     */
    public function __construct($to, $status = 200, array $headers = [])
    {
        $headers['Location'] = $to;

        parent::__construct('', $status, $headers);
    }
}