<?php

try {
    session_start();

    require 'vendor/autoload.php';

    $app = new \Basalt\App;

    $app->run();
} catch (Exception $e) {
    echo sprintf('<h1>%s</h1><h2>%s file %s line</h2><p>%s</p>', get_class($e), $e->getFile(), $e->getLine(), $e->getMessage());
}
