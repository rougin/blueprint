<?php

namespace Rougin\Blueprint;

use Psr\Container\ContainerInterface;
use Rougin\Slytherin\Container\ReflectionContainer;
use Rougin\Slytherin\Integration\IntegrationInterface;
use Symfony\Component\Console\Application;

/**
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Blueprint
{
    const VERSION = '0.7.0';

    /**
     * @var \Psr\Container\ContainerInterface|null
     */
    protected $container = null;

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var \Rougin\Slytherin\Integration\IntegrationInterface[]
     */
    protected $packages = array();

    /**
     * @var array<string, string>
     */
    protected $paths =
    [
        'templates' => '',
        'commands' => '',
        'namespace' => '',
    ];

    /**
     * @var string
     */
    protected $version = '';

    /**
     * Adds a package to the application.
     *
     * @param \Rougin\Slytherin\Integration\IntegrationInterface $package
     *
     * @return self
     */
    public function addPackage(IntegrationInterface $package)
    {
        $this->packages[] = $package;

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
     * Gets the commands path.
     *
     * @return string
     */
    public function getCommandPath()
    {
        return $this->paths['commands'];
    }

    /**
     * Returns the specified PSR container.
     *
     * @return \Psr\Container\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container ? $this->container : new ReflectionContainer;
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
     * Returns the current console.
     *
     * @return \Symfony\Component\Console\Application
     */
    public function make()
    {
        $app = new Application;

        $app->setVersion($this->version);

        $app->setName($this->name);

        $commands = $this->getCommands();

        $app->addCommands($commands);

        return $app;
    }

    /**
     * @codeCoverageIgnore
     *
     * Runs the console instance.
     *
     * @return integer
     */
    public function run()
    {
        return $this->make()->run();
    }

    /**
     * Sets the namespace of the commands path.
     *
     * @param string $namespace
     *
     * @return self
     */
    public function setCommandNamespace($namespace)
    {
        $this->paths['namespace'] = $namespace;

        return $this;
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
     * Sets the container for handling the commands.
     *
     * @param \Psr\Container\ContainerInterface $container
     *
     * @return self
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Sets the name of the console application.
     *
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets the templates path.
     *
     * @param string $path
     *
     * @return self
     */
    public function setTemplatePath($path)
    {
        $this->paths['templates'] = $path;

        return $this;
    }

    /**
     * Sets the version of the console application.
     *
     * @param string $version
     *
     * @return self
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Returns the list of available commands from the specified directory.
     *
     * @return \Symfony\Component\Console\Command\Command[]
     */
    protected function getCommands()
    {
        $namespace = $this->getCommandNamespace();

        /** @var string[] */
        $files = glob($this->getCommandPath() . '/*.php');

        $container = $this->getContainer();

        $items = array();

        foreach ($files as $file)
        {
            $name = substr(basename($file), 0, -4);

            $class = $namespace . '\\' . $name;

            $command = $container->get($class);

            if ($command instanceof Command)
            {
                $command = new Wrapper($command);
            }

            /** @var \Symfony\Component\Console\Command\Command $command */
            $items[] = $command;
        }

        return $items;
    }
}
