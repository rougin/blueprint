<?php

namespace Rougin\Blueprint\Fixture\Commands;

use Rougin\Blueprint\Command;

/**
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class HelloCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'hello';

    /**
     * @var string
     */
    protected $description = 'Hello someone';

    /**
     * Configures the current command.
     *
     * @return void
     */
    public function init()
    {
        $this->addArgument('name', 'Name of the user to be greeted');

        $this->addOptionalArgument('surname', 'Surname of the user');

        $this->addValueOption('age', 'Age of the user', 23);
    }

    /**
     * Executes the command.
     *
     * @return integer
     */
    public function run()
    {
        $name = $this->getArgument('name');

        $age = $this->getOption('age');

        if ($surname = $this->getArgument('surname'))
        {
            $name = $name . ' ' . $surname;
        }

        $text = 'Hello ' . $name . '! You\'re age is ' . $age . '.';

        $this->showText($text);

        return self::RETURN_SUCCESS;
    }
}
