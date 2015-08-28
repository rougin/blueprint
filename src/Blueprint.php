<?php

namespace Rougin\Blueprint;

use Symfony\Component\Console\Application;
use Symfony\Component\Yaml\Parser;
use Colors\Color;

/**
 * Blueprint
 *
 * A tool for generating files or templates for your PHP projects
 * 
 * @package Blueprint
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Blueprint
{
    protected $color;
    protected $errorMessage;
    protected $templates;
    protected $commands = [
        'path' => '',
        'namespace' => '',
    ];

    public $console;
    public $hasError;
    public $parser;

    /**
     * @param Application $console
     * @param Color       $color
     * @param Parser      $parser
     */
    public function __construct(
        Application $console,
        Color $color,
        Parser $parser
    ) {
        $this->color = $color;
        $this->console = $console;
        $this->parser = $parser;

        $this->commands['path'] = __DIR__;
        $this->templates = __DIR__;
    }

    /**
     * Get the templates path
     * 
     * @return string
     */
    public function getTemplatePath()
    {
        return $this->templates;
    }

    /**
     * Get the commands path
     * 
     * @return string
     */
    public function getCommandPath()
    {
        return $this->commands['path'];
    }

    /**
     * Get the namespace of the commands path
     * @return [type] [description]
     */
    public function getCommandNamespace()
    {
        return $this->commands['namespace'];
    }

    /**
     * Parse the file and get its specified paths
     * 
     * @param  string $file
     * @return Rougin\Blueprint\Blueprint
     */
    public function parse($file)
    {
        $result = $this->parser->parse($file);

        $this->commands['path'] = $result['paths']['commands'];
        $this->commands['namespace'] = $result['namespaces']['commands'];
        $this->templates = $result['paths']['templates'];

        return $this;
    }

    /**
     * Add an error message
     * 
     * @param  string $message
     * @return void
     */
    public function addError($message)
    {
        $this->hasError = TRUE;
        $this->errorMessage = $message;

        return $this;
    }

    /**
     * Shows an error message
     * 
     * @param  string $message
     * @return string
     */
    public function showError()
    {
        return $this->color
            ->fg('white')
            ->bg('red', $this->errorMessage) . PHP_EOL;
    }
}
