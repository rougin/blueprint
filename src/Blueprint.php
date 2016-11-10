<?php

namespace Rougin\Blueprint;

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
     * @param \Auryn\Injector                        $injector
     */
    public function __construct(Application $console, \Auryn\Injector $injector)
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
     * @param  string            $path
     * @param  \Twig_Environment $twig
     * @param  array             $extensions
     * @return self
     */
    public function setTemplatePath($path, \Twig_Environment $twig = null, $extensions = [])
    {
        $this->paths['templates'] = $path;

        if (is_null($twig)) {
            $loader = new \Twig_Loader_Filesystem($path);
            $twig   = new \Twig_Environment($loader);
        }

        $twig->setExtensions($extensions);

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
     * @return \Symfony\Component\Console\Application|boolean
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
        $files   = glob($this->getCommandPath() . '/*.php');
        $path    = strlen($this->getCommandPath() . DIRECTORY_SEPARATOR);
        $pattern = '/\\.[^.\\s]{3,4}$/';

        foreach ($files as $file) {
            $className = preg_replace($pattern, '', substr($file, $path));
            $className = $this->getCommandNamespace() . '\\' . $className;

            $class = new \ReflectionClass($className);

            if ($class->isAbstract()) {
                continue;
            }

            $this->console->add($this->injector->make($className));
        }

        return $this->console;
    }
}
