<?php

namespace Basalt\Http;

class RedirectResponse extends Response
{
    public function __construct($to, $status = 200, array $headers = [])
    {
        $headers['Location'] = $to;

        parent::__construct('', $status, $headers);
    }
}