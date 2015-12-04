<?php

namespace Basalt\Http\Controllers;

class PluginsController extends Controller
{
    public function plugins()
    {
        return $this->render('admin.plugins.plugins');
    }
}
