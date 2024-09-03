<?php

namespace Rougin\Blueprint;

use Psr\Container\ContainerInterface;
use Rougin\Slytherin\Container\Container;
use Symfony\Component\Console\Application as Symfony;
use Symfony\Component\Yaml\Yaml;

/**
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Application implements \ArrayAccess
{
    /**
     * @var string[]
     */
    protected $allowed = array(
        'commands',
        'file',
        'namespace',
        'root',
        'templates',
    );

    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var \Symfony\Component\Console\Application
     */
    protected $console;

    /**
     * @var string
     */
    protected $commands = '';

    /**
     * @var string
     */
    protected $file = 'blueprint.yml';

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
    protected $root = '';

    /**
     * @var string
     */
    protected $templates = '';

    /**
     * @var string
     */
    protected $version = '0.7.0';

    /**
     * @param string|null $file
     * @param string|null $root
     */
    public function __construct($file = null, $root = null)
    {
        $this->console = new Symfony($this->name, $this->version);

        $this->container = new Container;

        if ($file === null)
        {
            return;
        }

        $this->file = $file;

        if ($root === null)
        {
            $root = dirname($file);
        }

        $this->root = $root;

        $this->parse($file);
    }

    /**
     * Sets the PSR-11 container.
     *
     * @param \Psr\Container\ContainerInterface $container
     *
     * @return self
     */
    public function container(ContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Whether or not an offset exists.
     *
     * @param mixed $offset
     *
     * @return boolean
     * @throws \InvalidArgumentException
     */
    public function offsetExists($offset)
    {
        if (in_array($offset, $this->allowed))
        {
            return $this->$offset !== '';
        }

        $message = 'Key "' . $offset . '" does not exists!';

        throw new \InvalidArgumentException($message);
    }

    /**
     * Returns the value at specified offset.
     *
     * @param mixed $offset
     *
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function offsetGet($offset)
    {
        $this->offsetExists($offset);

        return $this->$offset;
    }

    /**
     * Assigns a value to the specified offset.
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        $this->offsetExists($offset);

        $this->$offset = $value;
    }

    /**
     * Unsets an offset.
     *
     * @param mixed $offset
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function offsetUnset($offset)
    {
        $this->offsetExists($offset);

        $this->$offset = null;
    }

    /**
     * Runs the console instance.
     *
     * @param boolean $console
     *
     * @return \Symfony\Component\Console\Application
     */
    public function run($console = false)
    {
        $commands = $this->commands;

        if (is_string($commands))
        {
            $commands = $this->classes();
        }

        foreach ($commands as $command)
        {
            $item = $this->container->get($command);

            $this->console->add($item);
        }

        if (! $console)
        {
            $this->console->run();
        }

        return $this->console;
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

        foreach ($files as $file)
        {
            $substring = substr($file, $path);

            $class = preg_replace($pattern, '', $substring);

            $class = $this->namespace . '\\' . $class;

            $reflection = new \ReflectionClass($class);

            if ($reflection->isAbstract())
            {
                continue;
            }

            $items[] = $class;
        }

        return $items;
    }

    /**
     * Parses the YAML file.
     *
     * @param string $file
     *
     * @return void
     */
    protected function parse($file)
    {
        $search = '%%CURRENT_DIRECTORY%%';

        $yaml = file_get_contents($file);

        $yaml = str_replace($search, $this->root, $yaml);

        $result = Yaml::parse($yaml);

        $this->commands = $result['paths']['commands'];

        $this->namespace = $result['namespaces']['commands'];

        $this->templates = $result['paths']['templates'];
    }

    /**
     * Calls methods from the Console instance.
     *
     * @param string $method
     * @param mixed  $params
     *
     * @return mixed
     */
    public function __call($method, $params)
    {
        /** @var callable */
        $class = array($this->console, $method);

        return call_user_func_array($class, $params);
    }
}
