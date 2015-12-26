<?php

namespace Basalt\Http\Controllers;

use Basalt\App;
use Basalt\Auth\AuthenticationException;
use Basalt\Auth\Authenticator;
use Basalt\Database\UserMapper;
use Basalt\Http\RedirectResponse;
use Basalt\Http\Response;
use Basalt\View;

abstract class Controller
{
    /**
     * @var \Basalt\App Application.
     */
    protected $app;
    /**
     * @var \Basalt\View View.
     */
    protected $view;

    /**
     * Constructor.
     *
     * @param \Basalt\App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->view = new View($app['twig']);
    }

    /**
     * Allows only logged in users to proceed.
     *
     * @param bool $guestsOnly
     * @throws \Basalt\Auth\AuthenticationException
     */
    protected function authorize($guestsOnly = false)
    {
        $userMapper = new UserMapper($this->app['pdo']);
        $authenticator = new Authenticator($userMapper);

        $isLoggedIn = $authenticator->isLoggedIn();

        if (($guestsOnly && $isLoggedIn)) {
            throw new AuthenticationException('Only guests can access.', AuthenticationException::LOGGED_IN);
        }

        if ((!$guestsOnly && !$isLoggedIn)) {
            throw new AuthenticationException('Only authorized users can access.', AuthenticationException::NOT_LOGGED_IN);
        }
    }

    /**
     * Return response.
     *
     * @param string $name Name of the view to render.
     * @param array $data Data
     * @return \Basalt\Http\Response
     */
    protected function render($name, $data = [])
    {
        $body = $this->view->render($name, $data);

        return new Response($body);
    }

    /**
     * Return redirect response.
     *
     * @param string|array $to URL or route name with parameters to redirect.
     * @return RedirectResponse
     */
    protected function redirect($to)
    {
        if (is_array($to)) {
            list($name, $parameters) = $to;
        } else {
            $name = $to;
            $parameters = [];
        }

        $url = $this->app['urlHelper']->toRoute($name, $parameters);

        return new RedirectResponse($url);
    }

    /**
     * Flash value.
     *
     * @param string $name Flash message name.
     * @param mixed $value Value to flash.
     * @return void
     */
    protected function setFlash($name, $value)
    {
        $this->app['flash']->set($name, $value);
    }

    /**
     * Return flashed message.
     *
     * @param string $name Flash message name.
     * @return mixed
     */
    protected function getFlash($name)
    {
        return $this->app['flash']->get($name);
    }

}
