<?php

namespace Basalt\Http\Controllers;

class AdminPanelController extends Controller
{
    public function dashboard()
    {
        return $this->render('admin.dashboard');
    }
}