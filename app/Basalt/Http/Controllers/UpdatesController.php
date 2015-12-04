<?php

namespace Basalt\Http\Controllers;

class UpdatesController extends Controller
{
    public function updates()
    {
        return $this->render('admin.updates.updates');
    }
}
