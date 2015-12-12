<?php

namespace Basalt\Database;

use Basalt\Validator\ValidationException;
use Basalt\Validator\Validator;

class Page
{
    public $id, $name, $slug, $content, $draft = false;

    public function validator()
    {
        $validator = new Validator;
        $validator->importRow('name', $this->name, ['IsNotEmpty'])
                  ->importRow('slug', $this->slug, ['IsNotEmpty', 'IsSlug'])
                  ->importRow('content', $this->content, ['IsNotEmpty'])
                  ->run();

        return $validator;
    }
}
