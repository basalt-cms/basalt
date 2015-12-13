<?php

namespace Basalt\Http\Controllers;

class UpdatesController extends Controller
{
    public function updates()
    {
        $this->authorize();

        return $this->render('admin.updates.updates');
    }
}
