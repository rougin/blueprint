<?php

namespace Rougin\Blueprint;

use LegacyPHPUnit\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Blueprint Test
 *
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class BlueprintTest extends TestCase
{
    /**
     * Tests \Rougin\Blueprint\Blueprint::run.
     *
     * @return void
     */
    public function testRun()
    {
        $console = Console::boot(null, null, getcwd());

        $expected = 'Symfony\Component\Console\Application';

        $result = $console->run(true);

        $this->assertInstanceOf($expected, $result);
    }

    /**
     * Tests Rougin\Blueprint\Commands\InitializeCommand.
     *
     * @return void
     */
    public function testInitializeCommand()
    {
        $blueprint = Console::boot(null, null, __DIR__);

        $init = $blueprint->run(true)->find('init');

        $command = new CommandTester($init);

        $command->execute(array());

        $this->assertFileExists(__DIR__ . '/blueprint.yml');

        unlink(__DIR__ . '/blueprint.yml');
    }

    /**
     * Tests \Rougin\Blueprint\Blueprint::setTemplatePath.
     *
     * @return void
     */
    public function testSetTemplatePath()
    {
        $root = str_replace('tests', 'src', __DIR__);

        $expected = $root . '/Templates';

        $blueprint = Console::boot('blueprint.yml');

        $blueprint->setTemplatePath($expected);

        $result = $blueprint->getTemplatePath();

        $this->assertEquals($expected, $result);
    }
}
