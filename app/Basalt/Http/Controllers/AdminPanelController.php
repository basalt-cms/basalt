<?php

namespace Basalt\Http\Controllers;

class AdminPanelController extends Controller
{
    public function dashboard()
    {
        $this->authorize();

        return $this->render('admin.dashboard');
    }
}
