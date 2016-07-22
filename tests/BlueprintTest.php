<?php

namespace Rougin\Blueprint;

use Auryn\Injector;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

use Rougin\Blueprint\Blueprint;
use Rougin\Blueprint\Commands\InitializationCommand;

use PHPUnit_Framework_TestCase;

class BlueprintTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Blueprint\Blueprint
     */
    protected $blueprint;

    /**
     * Sets up Blueprint application
     *
     * @return void
     */
    public function setUp()
    {
        $this->blueprint = new Blueprint(new Application, new Injector);
    }

    /**
     * Tests Blueprint::run.
     * 
     * @return void
     */
    public function testRun()
    {
        $commands = __DIR__ . '/TestApp/Commands';
        $templates = __DIR__ . '/TestApp/Templates';
        $namespace = 'Rougin\Blueprint\TestApp\Commands';
        $consoleApp = 'Symfony\Component\Console\Application';

        $this->blueprint
            ->setCommandPath($commands)
            ->setCommandNamespace($namespace)
            ->setTemplatePath($templates);

        $this->assertEquals($commands, $this->blueprint->getCommandPath());
        $this->assertEquals($namespace, $this->blueprint->getCommandNamespace());
        $this->assertEquals($templates, $this->blueprint->getTemplatePath());

        $console = $this->blueprint->run(true);

        $this->assertInstanceOf($consoleApp, $console);
    }

    /**
     * Tests Rougin\Blueprint\Commands\InitializationCommand.
     * 
     * @return void
     */
    public function testInitializationCommand()
    {
        define('BLUEPRINT_FILENAME', 'blueprint.yml');

        $command = new CommandTester(new InitializationCommand);
        $command->execute([]);

        $this->assertFileExists(BLUEPRINT_FILENAME);

        unlink(BLUEPRINT_FILENAME);
    }
}
