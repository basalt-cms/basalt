<?php

namespace Basalt\Database;

use Basalt\Exceptions\ValidationException;

class Page
{
    public $id, $name, $slug, $content, $draft = 0;

    public function validate()
    {
        $errors = [];

        if (empty($this->name)) {
            $errors[] = 'name.empty';
        }
        if (empty($this->slug)) {
            $errors[] = 'slug.empty';
        }
        if (0 === preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $this->slug)) {
            $errors[] = 'slug.invalid';
        }
        if (empty($this->content)) {
            $errors[] = 'content.empty';
        }

        if (!empty($errors)) {
            $exception = new ValidationException($errors);

            throw $exception;
        }
    }
} 