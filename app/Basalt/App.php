<?php

namespace Basalt;

use ArrayAccess;
use Basalt\Http\ControllerHandler;
use Basalt\Providers\ServiceProvider;

class App implements ArrayAccess
{
    /**
     * @var array Config array.
     */
    public $config;

    /**
     * @var \Basalt\Container Inversion of control container.
     */
    protected $container;

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
        if (false === class_exists($name) || false === is_subclass_of($name, 'Basalt\Providers\ServiceProvider')) {
            return false;
        }

        $this->config['providers'][] = $name;

        return true;
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
            /** @var array $route */
            $route = $container->matcher->match(isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/');

            $controllerHandler = new ControllerHandler($container->app, $route);

            return $controllerHandler->handleAndReturnResponse();
        };
    }
    public function offsetExists($offset)
    {
        return isset($this->container->$offset);
    }

    public function offsetGet($offset)
    {
        return $this->container->$offset;
    }

    public function offsetSet($offset, $value)
    {
        $this->container->$offset = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->container->$offset);
    }
}
