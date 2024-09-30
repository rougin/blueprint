<?php

namespace Rougin\Blueprint\Fixture\Commands;

use Rougin\Blueprint\Command;

/**
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class GreetCommand extends Command
{
    /**
     * @var string[]
     */
    protected $aliases = array('wave');

    /**
     * @var string
     */
    protected $name = 'greet';

    /**
     * @var string
     */
    protected $description = 'Greet someone';

    /**
     * Configures the current command.
     *
     * @return void
     */
    public function init()
    {
        $this->addArgument('name', 'Name of the user to be greeted');

        $this->addOptionalArrayArgument('aliases', 'Aliases of the user');

        $this->addNegatableOption('yell', 'If set, the task will yell in uppercase letters');
    }

    /**
     * Executes the command.
     *
     * @return integer
     */
    public function run()
    {
        /** @var string */
        $name = $this->getArgument('name');

        $text = sprintf('Hello %s!', $name);

        /** @var string[]|null */
        $aliases = $this->getArgument('aliases');

        if ($aliases)
        {
            $aliases = implode(', ', $aliases);

            $text = sprintf('Hello %s alias %s!', $name, $aliases);
        }

        if ($this->getOption('yell'))
        {
            $text = strtoupper($text);
        }

        $this->showText($text);

        return self::RETURN_SUCCESS;
    }
}
