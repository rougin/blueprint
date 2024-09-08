<?php

namespace Rougin\Blueprint\Fixture\Commands;

use Rougin\Blueprint\Command;

/**
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class WordCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'words';

    /**
     * @var string
     */
    protected $description = 'Show a list of words';

    /**
     * Configures the current command.
     *
     * @return void
     */
    public function init()
    {
        $this->addArrayArgument('words', 'Words to be displayed');
    }

    /**
     * Executes the command.
     *
     * @return integer
     */
    public function run()
    {
        /** @var string[] */
        $words = $this->getArgument('words');

        $this->showText(implode(', ', $words));

        return self::RETURN_SUCCESS;
    }
}
