<?php

namespace Rougin\Blueprint\Commands;

use Rougin\Blueprint\Command;

/**
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class InitializeCommand extends Command
{
    /**
     * @var string[]
     */
    protected $aliases = array('initialize');

    /**
     * @var string
     */
    protected $file = 'blueprint.yml';

    /**
     * @var string
     */
    protected $name = 'init';

    /**
     * @var string
     */
    protected $path;

    /**
     * Configures the current command.
     *
     * @return void
     */
    public function init()
    {
        $this->path = $this->getPlatePath();

        $text = 'Creates a "' . $this->file . '" file';

        $this->description = $text;

        $text = 'Allows to create a "' . $this->file . '" file in the current working directory.';

        $this->help = (string) $text;
    }

    /**
     * Executes the command.
     *
     * @return integer
     */
    public function run()
    {
        $file = $this->path . '/' . $this->file;

        /** @var string */
        $file = file_get_contents($file);

        $root = $this->getRootPath();

        file_put_contents($root . '/' . $this->file, $file);

        $text = '"' . $this->file . '" added successfully!';

        $this->showPass($text);

        return Command::RETURN_SUCCESS;
    }

    /**
     * Checks whether the command is enabled or not in the current environment.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return ! file_exists($this->getRootPath() . '/' . $this->file);
    }

    /**
     * Returns the source directory for the specified file.
     *
     * @return string
     */
    protected function getPlatePath()
    {
        /** @var string */
        return realpath(__DIR__ . '/../Templates');
    }

    /**
     * Returns the root directory from the package.
     *
     * @return string
     */
    protected function getRootPath()
    {
        /** @var string */
        $vendor = realpath(__DIR__ . '/../../../../../');

        $exists = file_exists($vendor . '/../autoload.php');

        return $exists ? $vendor : __DIR__ . '/../../';
    }
}
