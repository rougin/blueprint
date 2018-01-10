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
class Application implements \ArrayAccess
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
     * Whether or not an offset exists.
     *
     * @param  mixed $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        $allowed = array('commands', 'namespace', 'templates');

        if (in_array($offset, $allowed) === false) {
            $message = 'Key "' . $offset . '" does not exists!';

            throw new InvalidArgumentException($message);
        }

        return $this->$offset !== null && $this->$offset !== '';
    }

    /**
     * Returns the value at specified offset.
     *
     * @param  mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        $this->offsetExists();

        return $this->$offset;
    }

    /**
     * Assigns a value to the specified offset.
     *
     * @param  mixed $offset
     * @param  mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->offsetExists();

        $this->$offset = $value;
    }

    /**
     * Unsets an offset.
     *
     * @param  mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->offsetExists();

        $this->$offset = null;
    }

    /**
     * Runs the console instance.
     *
     * @param  boolean $console
     * @return \Symfony\Component\Console\Application
     */
    public function run($console = false)
    {
        $commands = $this->commands;

        is_string($commands) && $commands = $this->classes();

        foreach ((array) $commands as $command) {
            $item = $this->container->get($command);

            $this->console->add($instance = $item);
        }

        $console === false && $this->console->run();

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

        foreach ((array) $files as $file) {
            $substring = substr($file, $path);

            $class = preg_replace($pattern, '', $substring);

            $items[] = $this->namespace . '\\' . $class;
        }

        return $items;
    }

    /**
     * Calls methods from the Console instance.
     *
     * @param  string $method
     * @param  mixed  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $instance = array($this->console, $method);

        return call_user_func_array($instance, $parameters);
    }
}
