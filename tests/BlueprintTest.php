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
    public function test_passed_if_no_config_uses_defaults()
    {
        $expected = 'Symfony\Component\Console\Application';

        $root = __DIR__ . '/Fixture';

        $actual = Console::boot('nonexistent', $root);

        $actual = $actual->make();

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_php_config_loaded()
    {
        $expected = 'Symfony\Component\Console\Application';

        $root = __DIR__ . '/Fixture';

        $actual = Console::boot('blueprint', $root);

        $actual = $actual->make();

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_template_path_set()
    {
        $root = __DIR__ . '/Fixture';

        $expected = $root . '/Templates';

        $blueprint = Console::boot('blueprint.yml', $root);

        $blueprint->setTemplatePath($expected);

        $actual = $blueprint->getTemplatePath();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_yml_config_loaded()
    {
        $expected = 'Symfony\Component\Console\Application';

        $root = __DIR__ . '/Fixture';

        $actual = Console::boot('blueprint.yml', $root);

        $actual = $actual->make();

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_yml_fallback_works()
    {
        $expected = 'Symfony\Component\Console\Application';

        $root = __DIR__ . '/Fixture';

        $phpFile = $root . '/blueprint.php';
        $bakFile = $root . '/blueprint.php.bak';

        rename($phpFile, $bakFile);

        $actual = Console::boot('blueprint', $root);

        $actual = $actual->make();

        rename($bakFile, $phpFile);

        $this->assertInstanceOf($expected, $actual);
    }
}
