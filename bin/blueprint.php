<?php

// Define the VENDOR path
$vendor = realpath('vendor');

// Include the Composer Autoloader
require $vendor . '/autoload.php';

use Auryn\Injector;
use Rougin\Blueprint\Commands\InitializationCommand;
use Symfony\Component\Yaml\Exception\ParseException;

// Load the dependency injector
$injector = new Injector;

// Initialize Blueprint
$blueprint = $injector->make('Rougin\Blueprint\Blueprint');

$blueprint->console->setName('Blueprint');
$blueprint->console->setVersion('0.1.1');

if ( ! defined('BLUEPRINT_FILENAME')) {
    define('BLUEPRINT_FILENAME', 'blueprint.yml');
}

if ( ! defined('BLUEPRINT_DIRECTORY')) {
    define('BLUEPRINT_DIRECTORY', __DIR__);
}

// Check if BLUEPRINT_FILENAME exists in the working directory
if ( ! file_exists(BLUEPRINT_FILENAME)) {
    $blueprint->console->add(new InitializationCommand);

    return $blueprint;
}

// Parse the blueprint.yml
try {
    $yml = str_replace(
        '%%CURRENT_DIRECTORY%%',
        BLUEPRINT_DIRECTORY,
        file_get_contents(BLUEPRINT_FILENAME)
    );

    $blueprint->parse($yml);
} catch (ParseException $exception) {
    $blueprint->addError(
        'Unable to parse the YAML string: ' .
        $exception->getMessage()
    );

    return $blueprint;
}

// Check if the commands and templates path exists
if ( ! is_dir($blueprint->getCommandPath())) {
    $blueprint->addError(
        'Oops! We cannot find the directory "' .
        $blueprint->getCommandPath() . '"!'
    );

    return $blueprint;
} 

if ( ! is_dir($blueprint->getTemplatePath())) {
    $blueprint->addError(
        'Oops! We cannot find the directory "' .
        $blueprint->getTemplatePath() . '"!'
    );

    return $blueprint;
}

// Define constants
define('BLUEPRINT_COMMANDS', $blueprint->getCommandPath());
define('BLUEPRINT_TEMPLATES', $blueprint->getTemplatePath());

// Preload the Twig_Environment in order make it as a dependency
$injector->delegate('Twig_Environment', function () use ($injector) {
    $loader = new Twig_Loader_Filesystem(BLUEPRINT_TEMPLATES);
    $twig = new Twig_Environment($loader);

    return $twig;
});

// Search the following commands based on the specified directory
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

    if (strpos($file, '.php') === FALSE) {
        continue;
    }

    $className = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
    $class = $blueprint->getCommandNamespace() . $className;

    // Instantiate/provision the specified class instance
    try {
        // Add it to the list of available commands
        if (is_subclass_of($class, 'Rougin\Blueprint\AbstractCommand')) {
            $command = $injector->make($class);

            $blueprint->console->add($command);
        }
    } catch (Auryn\InjectionException $exception) {
        $blueprint->addError($exception->getMessage());
    }
}

// Run the console application
return $blueprint;
