<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir
    ]
)->register();

$loader->registerNamespaces(
    [
        'Phalcon\Db' => APP_PATH . '/library/Phalcon/Db',
        'App\Controllers' => $config->application->controllersDir,
        'App\Models' => $config->application->modelsDir,
    ]
)->register();