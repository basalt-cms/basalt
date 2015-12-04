<?php

namespace Basalt;

use Basalt\Http\ResponseExpectedException;
use Basalt\Http\Response;
use Basalt\Providers\ServiceProvider;

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
        $this->container->mainUrl = rtrim(pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME), '\\').'/';
    }

    /**
     * Run providers and send response.
     *
     * @return void
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
     * Run providers.
     *
     * @return void
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
            $response = call_user_func_array([$controller, $action], $attributes);

            if (!($response instanceof Response)) {
                throw new ResponseExpectedException;
            }

            return $response;
        };
    }
}
