<?php

namespace Basalt\Database;

class Setting
{
    public $id, $name, $value;

    public function __toString()
    {
        return $this->value;
    }
}
