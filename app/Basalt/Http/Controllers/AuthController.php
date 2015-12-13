<?php

namespace Basalt\Http\Controllers;

use Basalt\App;
use Basalt\Auth\Authenticator;
use Basalt\Database\UserMapper;

class AuthController extends Controller
{
    protected $userMapper;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->userMapper = new UserMapper($this->app->container->pdo);
    }

    public function login()
    {
        $this->authorize(true);

        $errors = $this->getFlash('errors');

        return $this->render('admin.login', compact('errors'));
    }

    public function authenticate()
    {
        $this->authorize(true);

        $input = $this->app->container->request->input;
        $authenticator = new Authenticator($this->userMapper);

        if ($authenticator->authenticate($input['email'], $input['password'])) {
            return $this->redirect('dashboard');
        } else {
            $this->setFlash('errors', 'Email address and/or password are incorrect.');

            return $this->redirect('login');
        }
    }

    public function logout()
    {
        $this->authorize();

        $authenticator = new Authenticator($this->userMapper);
        $authenticator->logOut();

        return $this->redirect('login');
    }
}
