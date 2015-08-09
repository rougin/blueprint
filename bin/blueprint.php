<?php

/**
 * Define the VENDOR path
 */

define('VENDOR', realpath('vendor') . '/');

/**
 * Include the Composer Autoloader
 */

require VENDOR . 'autoload.php';

use Symfony\Component\Yaml\Exception\ParseException;
use Rougin\Blueprint\Commands\InitializationCommand;
use Auryn\Injector;

/**
 * Load the dependency injector
 */

$injector = new Injector;

/**
 * Initialize Blueprint
 */

$blueprint = $injector->make('Rougin\Blueprint\Blueprint');

/**
 * Check if blueprint.yml exists in the working directory
 */

if (!file_exists('blueprint.yml')) {
    $blueprint->console->add(new InitializationCommand);

    return $blueprint->console->run();
}

/**
 * Parse the blueprint.yml
 */

try {
    $yml = str_replace(
        '%%CURRENT_DIRECTORY%%',
        __DIR__,
        file_get_contents('blueprint.yml')
    );

    $blueprint->parse($yml);
} catch (ParseException $e) {
    echo 'Unable to parse the YAML string: ' . $e->getMessage();
}

/**
 * Preload the Twig_Environment in order make it as a dependency
 */

define('COMMANDS', $blueprint->getCommandPath());
define('TEMPLATES', $blueprint->getTemplatePath());

$templates = $blueprint->getTemplatePath();

$injector->delegate('Twig_Environment', function () use ($injector, $templates) {
    $loader = new Twig_Loader_Filesystem($templates);
    $twig = new Twig_Environment($loader);

    return $twig;
});

/**
 * Search the following commands based on the specified directory
 */

$directory = new RecursiveDirectoryIterator(
    $blueprint->getCommandPath(),
    FilesystemIterator::SKIP_DOTS
);

$files = new RecursiveIteratorIterator(
    $directory,
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($files as $path) {
    $file = str_replace(
        [$blueprint->getCommandPath(), '/'],
        ['', '\\'],
        $path->__toString()
    );

    /**
     * Instantiate/provision the specified class instance
     */

    $className = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
    $class = $injector->make($blueprint->getCommandNamespace() . $className);

    /**
     * Add it to the list of commands
     */

    $blueprint->console->add($class);
}

/**
 * Run the console application
 */

$blueprint->console->run();