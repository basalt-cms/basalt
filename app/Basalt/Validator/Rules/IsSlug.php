<?php

use Basalt\Validator\Base;
use Basalt\Validator\RuleInterface;

class IsSlug extends Base implements RuleInterface
{
    public function execute()
    {
        return 1 === preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $this->slug);
    }
}