<?php

namespace Rougin\Blueprint;

use Auryn\Injector;
use Symfony\Component\Console\Application as Symfony;

/**
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
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
    protected $paths = array();

    /**
     * @param \Symfony\Component\Console\Application $console
     * @param \Auryn\Injector                        $injector
     */
    public function __construct(Symfony $console, Injector $injector)
    {
        $this->paths['commands'] = '';

        $this->paths['namespace'] = '';

        $this->paths['templates'] = '';

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
     * @param string                 $path
     * @param \Twig_Environment|null $twig
     * @param array                  $extensions
     *
     * @return self
     */
    public function setTemplatePath($path, \Twig_Environment $twig = null, $extensions = [])
    {
        $this->paths['templates'] = $path;

        if ($twig === null)
        {
            $twig = new \Twig_Environment(new \Twig_Loader_Filesystem($path));
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
     * @param string $path
     *
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
     * @param string $path
     *
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
     * @param boolean $console
     *
     * @return \Symfony\Component\Console\Application|boolean
     */
    public function run($console = false)
    {
        $instance = $this->instance();

        if ($console)
        {
            return $instance;
        }

        return $instance->run() === 0;
    }

    /**
     * Sets up Twig and gets all commands from the specified path.
     *
     * @return \Symfony\Component\Console\Application
     */
    protected function instance()
    {
        $files = glob($this->getCommandPath() . '/*.php');

        $path = strlen($this->getCommandPath() . DIRECTORY_SEPARATOR);

        $pattern = '/\\.[^.\\s]{3,4}$/';

        foreach ((array) $files as $file)
        {
            $class = preg_replace($pattern, '', substr($file, $path));

            $class = $this->getCommandNamespace() . '\\' . $class;

            $reflection = new \ReflectionClass($class);

            if (! $reflection->isAbstract())
            {
                $this->console->add($this->injector->make($class));
            }
        }

        return $this->console;
    }
}
