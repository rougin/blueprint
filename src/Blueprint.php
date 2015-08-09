<?php

namespace Rougin\Blueprint;

use Symfony\Component\Console\Application;
use Symfony\Component\Yaml\Parser;

class Blueprint
{
    protected $commands = [
        'path' => '',
        'namespace' => '',
    ];

    protected $templates;

    public $console;
    public $parser;

    public function __construct(
        Application $console,
        Parser $parser
    ) {
        $this->console = $console;
        $this->parser = $parser;

        $this->commands['path'] = __DIR__;
        $this->templates = __DIR__;
    }

    public function getTemplatePath()
    {
        return $this->templates;
    }

    public function getCommandPath()
    {
        return $this->commands['path'];
    }

    public function getCommandNamespace()
    {
        return $this->commands['namespace'];
    }

    public function parse($file)
    {
        $result = $this->parser->parse($file);

        $this->commands['path'] = $result['paths']['commands'];
        $this->commands['namespace'] = $result['namespaces']['commands'];
        $this->templates = $result['paths']['templates'];

        return $this;
    }
}
