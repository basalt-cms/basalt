<?php

namespace Basalt\Http\Controllers;

use Basalt\Database\SettingMapper;

class SettingsController extends Controller
{
    protected $dataMapper;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->dataMapper = new SettingMapper($this->app->container->pdo);
    }

    public function settings()
    {
        return $this->render('admin.settings.settings');
    }

    public function updateSettings()
    {
        //
    }
}