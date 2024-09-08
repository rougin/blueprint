<?php

namespace Rougin\Blueprint\Commands;

use Rougin\Blueprint\Command;

/**
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class InitCommand extends Command
{
    /**
     * @var string[]
     */
    protected $aliases =
    [
        'initialize'
    ];

    /**
     * @var string
     */
    protected $name = 'init';

    /**
     * @var string
     */
    protected $description = 'Creates a "blueprint.yml" file';

    /**
     * @var string
     */
    protected $help = 'Allows to create a "blueprint.yml" file in the current working directory.';

    /**
     * Executes the command.
     *
     * @return integer
     */
    public function run()
    {
        /** @var string */
        $path = realpath(__DIR__ . '/../Templates');

        $name = 'blueprint.yml';

        /** @var string */
        $file = file_get_contents($path . '/' . $name);

        $root = $this->getRootPath();

        file_put_contents($root . '/' . $name, $file);

        $this->showPass('"blueprint.yml" added successfully!');

        return Command::RETURN_SUCCESS;
    }

    /**
     * Checks whether the command is enabled or not in the current environment.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return ! file_exists($this->getRootPath() . '/blueprint.yml');
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
