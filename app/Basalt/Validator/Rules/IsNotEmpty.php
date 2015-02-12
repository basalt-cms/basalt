<?php

use Basalt\Validator\Base;
use Basalt\Validator\RuleInterface;

class IsNotEmpty extends Base implements RuleInterface
{
    public function execute()
    {
        return false === empty($this->value);
    }
}