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
class InitializeCommandTest extends Testcase
{
    /**
     * @return void
     */
    public function test_failed_if_file_already_exists()
    {
        $exception = 'Symfony\Component\Console\Exception\CommandNotFoundException';

        $this->doExpectException($exception);

        $root = __DIR__ . '/../../';

        $file = $root . 'blueprint.php';

        if (! file_exists($file))
        {
            file_put_contents($file, '');
        }

        $app = Console::boot('blueprint.yml')->make();

        unlink($file);

        $init = $app->find('init');

        $command = new CommandTester($init);

        $command->execute(array());
    }

    /**
     * @return void
     */
    public function test_passed_if_php_file_created()
    {
        $app = Console::boot('blueprint.yml');

        $init = $app->make()->find('init');

        $command = new CommandTester($init);

        $command->execute(array());

        $file = __DIR__ . '/../../blueprint.php';

        $this->assertFileExists($file);
    }

    /**
     * @return void
     */
    public function test_passed_if_yml_file_created()
    {
        $app = Console::boot('blueprint.yml');

        $init = $app->make()->find('init');

        $command = new CommandTester($init);

        $command->execute(array('--format' => 'yml'));

        $file = __DIR__ . '/../../blueprint.yml';

        $this->assertFileExists($file);

        unlink($file);
    }

    /**
     * @return void
     */
    public function doTearDown()
    {
        $phpFile = __DIR__ . '/../../blueprint.php';

        if (file_exists($phpFile))
        {
            unlink($phpFile);
        }

        $ymlFile = __DIR__ . '/../../blueprint.yml';

        if (file_exists($ymlFile))
        {
            unlink($ymlFile);
        }
    }
}
