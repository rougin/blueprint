<?php

namespace Rougin\Blueprint;

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
    /**
     * @var \Symfony\Component\Console\Application
     */
    public $console;

    /**
     * @var \Auryn\Injector
     */
    public $injector;

    /**
     * @var array
     */
    protected $paths = [
        'commands'  => '',
        'namespace' => '',
        'templates' => '',
    ];

    /**
     * @param \Symfony\Component\Console\Application $console
     * @param \Auryn\Injector $injector
     */
    public function __construct(Application $console, Injector $injector)
    {
        $this->console  = $console;
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
     * Sets the templates path.
     * 
     * @param  string $path
     * @return self
     */
    public function setTemplatePath($path)
    {
        $this->paths['templates'] = $path;

        $loader = new \Twig_Loader_Filesystem($path);
        $twig = new \Twig_Environment($loader);

        $this->injector->share($twig);

        return $this;
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
     * Sets the commands path.
     * 
     * @param  string $path
     * @return self
     */
    public function setCommandPath($path)
    {
        $this->paths['commands'] = $path;

        return $this;
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
     * Sets the namespace of the commands path.
     *
     * @param  string $path
     * @return self
     */
    public function setCommandNamespace($path)
    {
        $this->paths['namespace'] = $path;

        return $this;
    }

    /**
     * Runs the current console.
     *
     * @param  boolean $returnConsoleApp
     * @return \Symfony\Component\Console\Application|boolean|void
     */
    public function run($returnConsoleApp = false)
    {
        $console = $this->getConsoleApp();

        return ($returnConsoleApp) ? $console : $console->run();
    }

    /**
     * Sets up Twig and gets all commands from the specified path.
     * 
     * @return void
     */
    protected function getConsoleApp()
    {
        $pattern = '/\\.[^.\\s]{3,4}$/';
        $files = glob($this->paths['commands'] . '/*.php');
        $path = strlen($this->paths['commands'] . DIRECTORY_SEPARATOR);

        foreach ($files as $file) {
            $className = preg_replace($pattern, '', substr($file, $path));
            $className = $this->paths['namespace'] . '\\' . $className;

            require $file;

            $class = new \ReflectionClass($className);

            if ($class->isAbstract()) {
                continue;
            }

            $this->console->add($this->injector->make($className));
        }

        return $this->console;
    }
}
