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
        $settings = $this->dataMapper->all();

        $message = $this->getFlash('message');

        return $this->render('admin.settings.settings', compact('settings', 'message'));
    }

    public function update()
    {
        $input = $this->app->container->request->input;

        foreach($input['settings'] as $name => $value) {
            $setting = $this->dataMapper->get($name);
            $setting->value = $value;

            $this->dataMapper->save($setting);
        }

        $this->setFlash('message', 'Settings have been updated successful.');

        return $this->redirect('settings');
    }
}
