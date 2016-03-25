<?php

// Includes the Composer Autoloader
require realpath('vendor') . '/autoload.php';

if ( ! defined('BLUEPRINT_FILENAME')) {
    define('BLUEPRINT_FILENAME', 'blueprint.yml');
}

// Creates a new instance of Blueprint class
$injector = new Auryn\Injector;
$console = new Symfony\Component\Console\Application;
$app = new Rougin\Blueprint\Blueprint($console, $injector);

// Information of the application
$app->console->setName('Blueprint');
$app->console->setVersion('0.2.0');

// Adds a "init" command if the file does not exists
if ( ! file_exists(BLUEPRINT_FILENAME)) {
    $command = new Rougin\Blueprint\Commands\InitializationCommand;

    $app->console->add($command);

    return $app->run();
}

// Parses the data from a YAML format
$file = file_get_contents(BLUEPRINT_FILENAME);
$file = str_replace('%%CURRENT_DIRECTORY%%', getcwd(), $file);
$content = Symfony\Component\Yaml\Yaml::parse($file);

// Set paths from the parsed result
$app->setTemplatePath($content['paths']['templates'])
    ->setCommandPath($content['paths']['commands'])
    ->setCommandNamespace($content['namespaces']['commands']);

// Run the application
$app->run();
