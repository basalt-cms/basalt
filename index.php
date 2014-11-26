<?php

require 'vendor/autoload.php';

$app = new \Basalt\App;

var_dump($app->container->matcher->match($_SERVER['PATH_INFO']));