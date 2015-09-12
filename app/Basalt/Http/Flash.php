<?php

namespace Basalt\Http;

class Flash
{
    private static $messagePrefix = 'basalt_flash';

    /**
     * Check whether flash message is set.
     *
     * @param string $name Flash message name.
     * @return bool
     */
    public function has($name)
    {
        return isset($_SESSION[self::$messagePrefix][$name]);
    }

    /**
     * Return flashed message and delete it.
     *
     * @param string $name Flash message name.
     * @return mixed
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            return null;
        }

        $value = $_SESSION[self::$messagePrefix][$name];

        unset($_SESSION[self::$messagePrefix][$name]);

        return $value;
    }

    /**
     * Return flashed message.
     *
     * @param string $name Flash message name.
     * @return mixed
     */
    public function peek($name)
    {
        if (!$this->has($name)) {
            return null;
        }

        $value = $_SESSION[self::$messagePrefix][$name];

        return $value;
    }

    /**
     * Flash value.
     *
     * @param string $name Flash message name.
     * @param mixed $value Value to flash.
     * @return void
     */
    public function set($name, $value)
    {
        $_SESSION[self::$messagePrefix][$name] = $value;
    }
}