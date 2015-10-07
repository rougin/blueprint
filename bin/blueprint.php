<?php

if ( ! defined('BLUEPRINT_FILENAME')) {
    define('BLUEPRINT_FILENAME', 'blueprint.yml');
}

if ( ! defined('BLUEPRINT_DIRECTORY')) {
    define('BLUEPRINT_DIRECTORY', getcwd());
}

$blueprint = new Rougin\Blueprint\Blueprint(
    new Symfony\Component\Console\Application,
    new Auryn\Injector
);

// Information of the command application
$blueprint->console->setName('Blueprint');
$blueprint->console->setVersion('0.1.1');

// Adds a "init" command if the file does not exists
if ( ! file_exists(BLUEPRINT_FILENAME)) {
    $blueprint->console->add(
        new Rougin\Blueprint\Commands\InitializationCommand
    );

    return $blueprint;
}

$parser = new Symfony\Component\Yaml\Parser;
$file = file_get_contents(BLUEPRINT_FILENAME);
$yml = str_replace('%%CURRENT_DIRECTORY%%', BLUEPRINT_DIRECTORY, $file);

// Gets the array from the parsed YML file
$blueprint->getPaths($parser->parse($yml));

// Preloads the "Twig_Environment" in order make it as a dependency
$blueprint->injector->delegate('Twig_Environment', function () use ($blueprint) {
    $loader = new \Twig_Loader_Filesystem($blueprint->getTemplatePath());

    return new \Twig_Environment($loader);
});

return $blueprint;