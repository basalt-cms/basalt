<?php

namespace Basalt\Database;

class Setting
{
    public $id, $name, $value, $type;

    const TYPE_VARCHAR = 0;
    const TYPE_BOOLEAN = 1;
    const TYPE_TEXT = 2;

    public function validate()
    {
        return true;
    }
} 