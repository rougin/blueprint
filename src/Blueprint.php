<?php

namespace Rougin\Blueprint;

use Auryn\InjectionException;
use Auryn\Injector;
use Symfony\Component\Console\Application;

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
    protected $paths;

    public $console;
    public $injector;

    /**
     * @param Application $console
     * @param Injector    $injector
     */
    public function __construct(Application $console, Injector $injector)
    {
        $this->console = $console;
        $this->injector = $injector;
    }

    /**
     * Gets the templates path.
     * 
     * @return string
     */
    public function getTemplatePath()
    {
        return $this->paths['templates'];
    }

    /**
     * Gets the commands path.
     * 
     * @return string
     */
    public function getCommandPath()
    {
        return $this->paths['commands'];
    }

    /**
     * Gets the namespace of the commands path.
     * 
     * @return string
     */
    public function getCommandNamespace()
    {
        return $this->paths['namespace'];
    }

    /**
     * Gets the list of directory paths.
     * 
     * @param  mixed  $result
     * @return void
     */
    public function getPaths($result)
    {
        if ( ! is_array($result)) {
            return;
        }

        $this->paths['commands'] = $result['paths']['commands'];
        $this->paths['namespace'] = $result['namespaces']['commands'];
        $this->paths['templates'] = $result['paths']['templates'];

        return;
    }

    /**
     * Runs the current console.
     * 
     * @return boolean|void
     */
    public function run()
    {
        $commandPath = strlen($this->paths['commands'] . DIRECTORY_SEPARATOR);
        $files = glob($this->paths['commands'] . '/*.php');

        foreach ($files as $file) {
            $className = preg_replace(
                '/\\.[^.\\s]{3,4}$/', '',
                substr($file, $commandPath)
            );

            try {
                $command = $this->injector->make(
                    $this->paths['namespace'] . '\\' . $className
                );

                $this->console->add($command);
            } catch (InjectionException $exception) {
                echo $exception->getMessage() . PHP_EOL;

                return;
            }
        }

        return $this->console->run();
    }
}
