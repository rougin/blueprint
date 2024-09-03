<?php

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Rougin\Blueprint\Application;
use Rougin\Slytherin\Container\Container;

require 'vendor/autoload.php';

$root = str_replace('bin', 'src', __DIR__);

$cwd = (string) getcwd();

$file = $cwd . '/blueprint.yml';

if (! file_exists($file))
{
    $file = null;
}

$app = new Application($file);

if (! $file)
{
    $app['commands'] = $root . '/Commands';

    $app['root'] = $cwd;

    $app['templates'] = $root . '/Templates';
}

// NOTE: Third-party packages must be removed in v1.0.0. ---
$container = new Container;
// ---------------------------------------------------------

// Create an instance for Filesystem ---
$adapter = new Local($app['root']);

$filesystem = new Filesystem($adapter);

$class = get_class($filesystem);

$container->set($class, $filesystem);
// -------------------------------------

// Create an instance for Twig --------------
/** @var string[] */
$paths = $app['templates'];

$loader = new Twig_Loader_Filesystem($paths);

$twig = new Twig_Environment($loader);

$container->set(get_class($twig), $twig);
// ------------------------------------------

$app->container($container)->run();
