<?php

require 'vendor/autoload.php';

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Rougin\Blueprint\Application;
use Rougin\Slytherin\Container\Container;

$root = str_replace('bin', 'src', __DIR__);

$default = $root . '/Templates/blueprint.yml';

$file = getcwd() . '/blueprint.yml';

file_exists($file) === false && $file = $default;

$app = new Application($file, $root);

$container = new Container;

$filesystem = new Filesystem(new Local($app['root']));

$container->set(get_class($filesystem), $filesystem);

$loader = new Twig_Loader_Filesystem($app['templates']);

$twig = new Twig_Environment($loader);

$container->set(get_class($twig), $twig);

$app->container($container)->run();
