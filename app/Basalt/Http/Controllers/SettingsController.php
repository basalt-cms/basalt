<?php

namespace Basalt\Http\Controllers;

class SettingsController extends Controller
{
    public function settings()
    {
        return $this->render('admin.settings.settings');
    }

    public function updateSettings()
    {
        //
    }
}