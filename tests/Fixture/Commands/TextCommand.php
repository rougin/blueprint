<?php

namespace Rougin\Blueprint\Fixture\Commands;

use Rougin\Blueprint\Command;

/**
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class TextCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'text';

    /**
     * @var string
     */
    protected $description = 'Show some sample texts';

    /**
     * Executes the command.
     *
     * @return integer
     */
    public function run()
    {
        $this->showPass('This is a info text');

        $this->showFail('This is a error text');

        $this->showInfo('This is a question text');

        $this->showWarn('This is a comment text');

        return self::RETURN_SUCCESS;
    }
}
