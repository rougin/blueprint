<?php

namespace Rougin\Blueprint;

use Psr\Container\ContainerInterface;
use Rougin\Slytherin\Container\Container;
use Symfony\Component\Console\Application as Console;

/**
 * Blueprint Application
 *
 * @package Blueprint
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Application
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $commands = '';

    /**
     * @var string
     */
    protected $name = 'Blueprint';

    /**
     * @var string
     */
    protected $namespace = 'Rougin\Blueprint\Commands';

    /**
     * @var string
     */
    protected $templates = '';

    /**
     * @var string
     */
    protected $version = '0.6.0';

    /**
     * Initializes the Blueprint instance.
     *
     * @param \Psr\Container\ContainerInterface|null $container
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container ?: new Container;

        $this->commands = __DIR__ . '/Commands';

        $this->templates = __DIR__ . '/Templates';

        $this->console = new Console($this->name, $this->version);
    }

    /**
     * Returns the Symfony\Component\Console\Application.
     *
     * @return \Symfony\Component\Console\Application
     */
    public function console()
    {
        $commands = $this->commands;

        is_string($commands) && $commands = $this->classes();

        foreach ((array) $commands as $command) {
            $instance = $this->container->get($command);

            $this->console->add($instance);
        }

        return $this->console;
    }

    /**
     * Sets the array of commands or its path.
     *
     * @param  array|string $value
     * @return self
     */
    public function commands($value)
    {
        $this->commands = $value;

        return $this;
    }

    /**
     * Sets the namespace of the commands.
     *
     * @param  string $value
     * @return self
     */
    public function namespace($value)
    {
        $this->namespace = $value;

        return $this;
    }

    /**
     * Sets the path for the templates.
     *
     * @param  string $value
     * @return self
     */
    public function templates($value)
    {
        $this->templates = $value;

        return $this;
    }

    /**
     * Returns an array of command classes.
     *
     * @return string[]
     */
    protected function classes()
    {
        list($items, $pattern) = array(array(), '/\\.[^.\\s]{3,4}$/');

        $files = glob($this->commands . '/*.php');

        $path = strlen($this->commands . DIRECTORY_SEPARATOR);

        foreach ((array) $files as $file) {
            $substring = substr($file, $path);

            $class = preg_replace($pattern, '', $substring);

            $items[] = $this->namespace . '\\' . $class;
        }

        return $items;
    }
}
