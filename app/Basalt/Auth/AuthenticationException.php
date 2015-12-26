<?php

namespace Basalt\Auth;

use Exception;

class AuthenticationException extends Exception
{
    const NOT_LOGGED_IN = 0;
    const LOGGED_IN = 1;
}
