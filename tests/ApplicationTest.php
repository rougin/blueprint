<?php

namespace Rougin\Blueprint;

use Rougin\Slytherin\Container\ReflectionContainer;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Blueprint Application Test
 *
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ApplicationTest extends Testcase
{
    /**
     * @var \Rougin\Blueprint\Application
     */
    protected $application;

    /**
     * Sets up the application instance.
     *
     * @return void
     */
    public function doSetUp()
    {
        $file = __DIR__ . '/Fixture/SampleBlueprint.yml';

        $application = new Application($file);

        $application->container(new ReflectionContainer);

        $this->application = $application;
    }

    /**
     * @return void
     */
    public function test_run_with_sample_command()
    {
        // Search the specified command ------
        $console = $this->application->make();

        $command = $console->find('greet');

        $tester = new CommandTester($command);
        // -----------------------------------

        $input = array('name' => 'Rougin', '--yell' => true);

        $tester->execute($input);

        $expected = 'HELLO ROUGIN!';

        // Parses the result from the display -----
        $actual = $tester->getDisplay();

        $actual = str_replace("\r\n", '', $actual);

        $actual = str_replace("\n", '', $actual);
        // ----------------------------------------

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_command_path()
    {
        $expected = __DIR__ . '/Fixture/Commands';

        $this->application['commands'] = $expected;

        $result = $this->application['commands'];

        $this->assertEquals($expected, $result);
    }

    /**
     * @return void
     */
    public function test_getting_path_with_exception()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->application['test'] = 'Hello and this is a test';
    }

    /**
     * @return void
     */
    public function test_unsetting_a_path()
    {
        unset($this->application['templates']);

        $result = $this->application['templates'];

        $this->assertNull($result);
    }

    /**
     * @return void
     */
    public function test_calling_from_console()
    {
        $expected = 'Blueprint';

        $result = $this->application->getName();

        $this->assertEquals($expected, $result);
    }
}
