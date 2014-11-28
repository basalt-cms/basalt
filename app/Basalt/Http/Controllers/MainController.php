<?php

namespace Basalt\Http\Controllers;

class MainController extends Controller
{
    public function index()
    {
        $name = 'WORKS!';

        return $this->render('index', compact('name'));
    }
} 