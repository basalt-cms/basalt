<?php

namespace Basalt\Http\Controllers;

class MainController extends Controller
{
    public function index()
    {
        $content = 'content';

        return $this->render('index', compact('content'));
    }
} 