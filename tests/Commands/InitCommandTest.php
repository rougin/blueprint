<?php

namespace Rougin\Blueprint\Commands;

use Rougin\Blueprint\Console;
use Rougin\Blueprint\Testcase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class InitCommandTest extends Testcase
{
    /**
     * @return void
     */
    public function test_file_create()
    {
        $app = Console::boot('blueprint.yml');

        $init = $app->make()->find('init');

        $command = new CommandTester($init);

        $command->execute(array());

        $file = __DIR__ . '/../../blueprint.yml';

        $this->assertFileExists($file);
    }

    /**
     * @return void
     */
    public function test_file_already_exists()
    {
        $exception = 'Symfony\Component\Console\Exception\CommandNotFoundException';

        $this->setExpectedException($exception);

        $app = Console::boot('blueprint.yml')->make();

        unlink(__DIR__ . '/../../blueprint.yml');

        $init = $app->find('init');

        $command = new CommandTester($init);

        $command->execute(array());
    }
}
