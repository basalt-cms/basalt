<?php

require 'vendor/autoload.php';

try {
    $app = new \Basalt\App;

    $app->run();
} catch (Exception $e) {
    echo '<pre>'.print_r($e, true).'</pre>';
}