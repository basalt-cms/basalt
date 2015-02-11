<?php

namespace Basalt\Http;

class Flash
{
    const MESSAGE_PREFIX = 'flash_';

    /**
     * Return flashed message.
     *
     * @param string $name Flash message name.
     * @param bool $delete Should it be deleted?
     * @return mixed
     */
    public function get($name, $delete = true)
    {
        if (isset($_SESSION[$name = self::MESSAGE_PREFIX . $name])) {
            $value = $_SESSION[$name];

            if ($delete) {
                unset($_SESSION[$name]);
            }

            return $value;
        }

        return null;
    }

    /**
     * Flash value.
     *
     * @param string $name Flash message name.
     * @param mixed $value Value to flash.
     * @return void
     */
    public function flash($name, $value)
    {
        $_SESSION[self::MESSAGE_PREFIX . $name] = $value;
    }
}