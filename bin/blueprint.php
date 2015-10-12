<?php

// Parses the data from a YAML format
$parser = new Rougin\Blueprint\Parser(
    new Symfony\Component\Yaml\Parser
);

$content = $parser->parse('blueprint.yml', getcwd());

$blueprint = new Rougin\Blueprint\Blueprint(
    new Symfony\Component\Console\Application,
    new Auryn\Injector
);

// Information of the command application
$blueprint->console->setName('Blueprint');
$blueprint->console->setVersion('0.1.1');

// Adds a "init" command if the file does not exists
if ( ! file_exists('blueprint.yml')) {
    $blueprint->console->add(
        new Rougin\Blueprint\Commands\InitializationCommand
    );

    return $blueprint;
}

// Set paths from the parsed result
$blueprint
    ->setTemplatePath($content['paths']['templates'])
    ->setCommandPath($content['paths']['commands'])
    ->setCommandNamespace($content['namespaces']['commands']);

return $blueprint;