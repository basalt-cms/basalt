<?php

namespace Basalt;

use PDO;
use Basalt\Exceptions\ConfigNotFoundException;
use Basalt\Http\Request;
use Basalt\Providers\ServiceProvider;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

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

        $this->container->request = function() {
            return new Request;
        };

        $this->container->context = function() {
            $context = new RequestContext($_SERVER['REQUEST_URI']);
            $this->container->request->prepareContext($context);
            $context->setMethod($this->container->request->getMethod());

            return $context;
        };

        $this->container->matcher = function($container) {
            return new UrlMatcher($container->routes, $container->context);
        };

        $this->container->generator = function($container) {
            return new UrlGenerator($container->routes, $container->context);
        };

        $this->container->pdo = function($container) {
            $config = $container->app->config['database'];

            $dsn = sprintf('mysql:host=%s;dbname=%s', $config['host'], $config['dbname']);

            $pdo = new PDO($dsn, $config['user'], $config['password'], [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
            ]);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        };
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

            $attributes_keys = array_filter(array_keys($route), function($key) {
                return strpos($key, '_') !== 0;
            });
            $attributes = array_intersect_key($route, array_flip($attributes_keys));

            $controller = new $controller($this);
            $response = call_user_func_array([$controller, $action], $attributes);

            return $response;
        };
    }
}