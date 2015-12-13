<?php

namespace Basalt;

use Basalt\Auth\AuthenticationException;
use Basalt\Http\RedirectResponse;
use Basalt\Http\ResponseExpectedException;
use Basalt\Http\Response;
use Basalt\Providers\ServiceProvider;
use ReflectionClass;

class App
{
    /**
     * @var array Config array.
     */
    public $config;

    /**
     * @var \Basalt\Container Inversion of control container.
     */
    public $container;

    /**
     * Constructor.
     *
     * @throws ConfigNotFoundException
     */
    public function __construct()
    {
        $this->loadConfig();

        $this->container = new Container($this);
    }

    /**
     * Run providers and send response.
     *
     * @return void
     */
    public function run()
    {
        $this->prepareResponse();
        $this->runProviders();

        $this->container->response->send();
    }

    /**
     * Load configuration file.
     *
     * @throws ConfigNotFoundException
     * @return void
     */
    protected function loadConfig()
    {
        $configFile = dirname(dirname(__FILE__)).'/config.php';

        if (!file_exists($configFile)) {
            throw new ConfigNotFoundException;
        }

        $this->config = require $configFile;
    }

    /**
     * Adds provider to array.
     *
     * @param string $name Provider's class name.
     * @return bool
     */
    public function addProvider($name)
    {
        $reflection = new ReflectionClass($name);

        if (false === class_exists($name) || false === $reflection->isSubclassOf('Basalt\Providers\ServiceProvider')) {
            return false;
        }

        $this->config['providers'][] = $name;
    }

    /**
     * Run providers.
     *
     * @return void
     */
    protected function runProviders()
    {
        $providers = $this->config['providers'];

        foreach ($providers as $provider) {
            $provider = new $provider($this->container);

            if (false === $provider instanceof ServiceProvider) {
                continue;
            }

            $provider->provide();
        }
    }

    /**
     * Prepare response.
     *
     * @return void
     */
    protected function prepareResponse()
    {
        $this->container->response = function(Container $container) {
            $route = $container->matcher->match(isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/');
            list($controller, $action) = explode('@', $route['_controller']);

            $attributesKeys = array_filter(array_keys($route), function($key) {
                return strpos($key, '_') !== 0;
            });
            $attributes = array_intersect_key($route, array_flip($attributesKeys));

            $controller = new $controller($this);

            try {
                $response = call_user_func_array([$controller, $action], $attributes);
            } catch (AuthenticationException $e) {
                $url = $container->urlHelper->toRoute('login');

                return new RedirectResponse($url);
            }

            if (!($response instanceof Response)) {
                throw new ResponseExpectedException;
            }

            return $response;
        };
    }
}
