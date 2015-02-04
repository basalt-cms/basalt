<?php

namespace Basalt\Http;

class Flash
{
    const MESSAGE_PREFIX = 'flash_';

    public function get($name, $delete = true)
    {
        if (isset($_SESSION[$name = self::MESSAGE_PREFIX.$name])) {
            $value = $_SESSION[$name];

            if ($delete) {
                unset($_SESSION[$name]);
            }

            return $value;
        }

        return null;
    }

    public function flash($name, $value)
    {
        $_SESSION[self::MESSAGE_PREFIX.$name] = $value;
    }
}