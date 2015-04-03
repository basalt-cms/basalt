<?php

namespace Basalt\Database;

class Setting
{
    public $id, $name, $value;

    public function validate()
    {
        return true;
    }
} 