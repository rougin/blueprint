<?php

namespace Rougin\Blueprint;

use Auryn\Injector;
use ReflectionClass;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Auryn\InjectionException;
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
     * Sets the templates path.
     * 
     * @return string
     */
    public function setTemplatePath($path)
    {
        $this->paths['templates'] = $path;

        return $this;
    }

    /**
     * Sets the commands path.
     * 
     * @return string
     */
    public function setCommandPath($path)
    {
        $this->paths['commands'] = $path;

        return $this;
    }

    /**
     * Sets the namespace of the commands path.
     * 
     * @return string
     */
    public function setCommandNamespace($path)
    {
        $this->paths['namespace'] = $path;

        return $this;
    }

    /**
     * Runs the current console.
     * 
     * @return boolean|void
     */
    public function run()
    {
        // Preloads the "Twig_Environment" in order make it as a dependency
        $this->injector->delegate('Twig_Environment', function () {
            $loader = new Twig_Loader_Filesystem($this->getTemplatePath());

            return new Twig_Environment($loader);
        });

        $commandPath = strlen($this->paths['commands'] . DIRECTORY_SEPARATOR);
        $files = glob($this->paths['commands'] . '/*.php');

        foreach ($files as $file) {
            $className = preg_replace(
                '/\\.[^.\\s]{3,4}$/', '',
                substr($file, $commandPath)
            );

            $className = $this->paths['namespace'] . '\\' . $className;

            try {
                $reflection = new ReflectionClass($className);

                if ( ! $reflection->isAbstract()) {
                    $command = $this->injector->make($className);

                    $this->console->add($command);
                }
            } catch (InjectionException $exception) {
                echo $exception->getMessage() . PHP_EOL;

                return;
            }
        }

        return $this->console->run();
    }
}
