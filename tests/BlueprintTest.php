<?php

namespace Rougin\Blueprint;

use Symfony\Component\Console\Tester\CommandTester;

use Rougin\Blueprint\Console;

/**
 * Blueprint Test
 *
 * @package Blueprint
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class BlueprintTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Sets up the filename to be used.
     *
     * @return void
     */
    public function setUp()
    {
        if (! defined('BLUEPRINT_FILENAME')) {
            define('BLUEPRINT_FILENAME', 'blueprint.yml');
        }
    }

    /**
     * Tests \Rougin\Blueprint\Blueprint::run.
     *
     * @return void
     */
    public function testRun()
    {
        $console = Console::boot(BLUEPRINT_FILENAME, new \Auryn\Injector)->run(true);

        $this->assertInstanceOf('Symfony\Component\Console\Application', $console);
    }

    /**
     * Tests Rougin\Blueprint\Commands\InitializeCommand.
     *
     * @return void
     */
    public function testInitializeCommand()
    {
        $blueprint = Console::boot(BLUEPRINT_FILENAME, new \Auryn\Injector);
        $className = 'Rougin\Blueprint\Commands\InitializeCommand';
        $instance  = $blueprint->injector->make($className);

        $command = new CommandTester($instance);
        $command->execute([]);

        $this->assertFileExists(BLUEPRINT_FILENAME);

        unlink(BLUEPRINT_FILENAME);
    }

    /**
     * Tests \Rougin\Blueprint\Blueprint::setTemplatePath.
     *
     * @return void
     */
    public function testSetTemplatePath()
    {
        $separator = DIRECTORY_SEPARATOR;
        $templates = str_replace('tests', 'src', __DIR__) . $separator . 'Templates';

        $blueprint = Console::boot(BLUEPRINT_FILENAME, new \Auryn\Injector);

        $blueprint->setTemplatePath($templates);

        $this->assertEquals($templates, $blueprint->getTemplatePath());
    }

    /**
     * Tests Rougin\Blueprint\Commands\GreetCommand.
     *
     * @return void
     */
    public function testGreetCommand()
    {
        $blueprint = Console::boot(__DIR__ . '/blueprint.yml', new \Auryn\Injector);

        $className = 'Rougin\Blueprint\Commands\InitializeCommand';
        $instance  = $blueprint->injector->make($className);

        $command = new CommandTester($instance);
        $command->execute([]);

        $className = 'Rougin\Blueprint\Commands\GreetCommand';
        $instance  = $blueprint->injector->make($className);

        $command = new CommandTester($instance);
        $command->execute([ '--yell' => true ]);

        $this->assertRegExp('/HELLO STRANGER!/', $command->getDisplay());

        unlink(BLUEPRINT_FILENAME);
    }
}
