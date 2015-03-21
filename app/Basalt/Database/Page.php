<?php

namespace Basalt\Database;

use Basalt\Validator\ValidationException;
use Basalt\Validator\Validator;

class Page
{
    public $id, $name, $slug, $content, $draft = false;

    public function validate()
    {
        $validator = new Validator;
        $validator->importRow('name', $this->name, ['IsNotEmpty'])
                  ->importRow('slug', $this->slug, ['IsNotEmpty', 'IsSlug'])
                  ->importRow('content', $this->content, ['IsNotEmpty'])
                  ->run();

        $errors = $validator->getErrors();
        if (false === empty($errors)) {
            throw new ValidationException($errors);
        }
    }
} 