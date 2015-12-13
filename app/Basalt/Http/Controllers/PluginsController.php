<?php

namespace Basalt\Http\Controllers;

class PluginsController extends Controller
{
    public function plugins()
    {
        $this->authorize();

        return $this->render('admin.plugins.plugins');
    }
}
