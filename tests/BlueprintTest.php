<?php

namespace Rougin\Blueprint;

use Symfony\Component\Console\Tester\CommandTester;

/**
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class BlueprintTest extends Testcase
{
    /**
     * @return void
     */
    public function test_console_application()
    {
        $path = (string) getcwd();

        $console = Console::boot(null, null, $path);

        $expected = 'Symfony\Component\Console\Application';

        $result = $console->make();

        $this->assertInstanceOf($expected, $result);
    }

    /**
     * @return void
     */
    public function test_initialize_command()
    {
        $app = Console::boot(null, null, __DIR__);

        $init = $app->make()->find('init');

        $command = new CommandTester($init);

        $command->execute(array());

        $this->assertFileExists(__DIR__ . '/blueprint.yml');

        unlink(__DIR__ . '/blueprint.yml');
    }

    /**
     * @return void
     */
    public function test_setting_template_path()
    {
        $root = str_replace('tests', 'src', __DIR__);

        $expected = $root . '/Templates';

        $blueprint = Console::boot('blueprint.yml');

        $blueprint->setTemplatePath($expected);

        $result = $blueprint->getTemplatePath();

        $this->assertEquals($expected, $result);
    }
}
