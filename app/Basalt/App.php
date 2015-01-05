<?php

namespace Basalt;

use Basalt\Exceptions\ResponseExpectedException;
use Basalt\Http\Response;
use Basalt\Exceptions\ConfigNotFoundException;
use Basalt\Providers\ServiceProvider;

class App
{
    /**
     * @var array Config;
     */
    public $config;
    /**
     * @var \Basalt\Container Container.
     */
    public $container;

    /**
     * Constructor
     *
     * @throws ConfigNotFoundException
     */
    public function __construct()
    {
        $this->loadConfig();

        $this->container = new Container($this);
        $this->container->mainUrl = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME).'/';
    }

    /**
     * Run providers and send response.
     */
    public function run()
    {
        $this->runProviders();
        $this->prepareResponse();
        $this->container->response->send();
    }

    /**
     * Load configuration file.
     *
     * @throws ConfigNotFoundException
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
     * Run providers.
     */
    protected function runProviders()
    {
        $providers = $this->config['providers'];

        foreach ($providers as $provider) {
            $provider = new $provider($this);

            if (!($provider instanceof ServiceProvider)) {
                continue;
            }

            $provider->provide();
        }

    }

    /**
     * Prepare response.
     */
    protected function prepareResponse()
    {
        $this->container->response = function($container) {
            $route = $container->matcher->match(isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/');
            list($controller, $action) = explode('@', $route['_controller']);

            $attributesKeys = array_filter(array_keys($route), function($key) {
                return strpos($key, '_') !== 0;
            });
            $attributes = array_intersect_key($route, array_flip($attributesKeys));

            $controller = new $controller($this);
            $response = call_user_func_array([$controller, $action], $attributes);

            if (!($response instanceof Response)) {
                throw new ResponseExpectedException;
            }

            return $response;
        };
    }
}