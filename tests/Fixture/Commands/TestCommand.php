<?php

namespace Rougin\Blueprint\Fixture\Commands;

use Rougin\Blueprint\Command;

/**
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class TestCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'test';

    /**
     * @var string
     */
    protected $description = 'Run command in this command';

    /**
     * Executes the command.
     *
     * @return integer
     */
    public function run()
    {
        $data = array('name' => 'Rougin');

        $data['--yell'] = true;

        $this->runCommand('hello', $data);

        $this->showPass('runCommand returned okay!');

        return self::RETURN_SUCCESS;
    }
}
