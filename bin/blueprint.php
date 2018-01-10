<?php

require 'vendor/autoload.php';

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

$root = str_replace('bin', 'src', __DIR__);

$file = getcwd() . '/blueprint.yml';

file_exists($file) === false && $file = null;

$app = new Rougin\Blueprint\Application($file);

if ($file === null) {
    $app['commands'] = $root . '/Commands';

    $app['root'] = getcwd();

    $app['templates'] = $root . '/Templates';
}

// NOTE: Third-party packages must be removed in v1.0.0.
$container = new Rougin\Slytherin\Container\Container;

$filesystem = new Filesystem(new Local($app['root']));

$container->set(get_class($filesystem), $filesystem);

$loader = new Twig_Loader_Filesystem($app['templates']);

$twig = new Twig_Environment($loader);

$container->set(get_class($twig), $twig);

$app->container($container)->run();
