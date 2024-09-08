<?php

namespace Rougin\Blueprint;

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
        $expected = 'Symfony\Component\Console\Application';

        /** @var string */
        $root = realpath(__DIR__ . '/Fixture');

        $actual = Console::boot('blueprint.yml', $root);

        $actual = $actual->make();

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_setting_template_path()
    {
        /** @var string */
        $root = realpath(__DIR__ . '/Fixture');

        $expected = $root . '/Templates';

        $blueprint = Console::boot('blueprint.yml', $root);

        $blueprint->setTemplatePath($expected);

        $actual = $blueprint->getTemplatePath();

        $this->assertEquals($expected, $actual);
    }
}
