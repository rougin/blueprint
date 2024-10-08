<?php

namespace Rougin\Blueprint;

use Rougin\Blueprint\Fixture\Packages\SamplePackage;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class CommandTest extends Testcase
{
    /**
     * @var \Rougin\Blueprint\Blueprint
     */
    protected $app;

    /**
     * @return void
     */
    public function doSetUp()
    {
        /** @var string */
        $root = realpath(__DIR__ . '/Fixture');

        $app = Console::boot('blueprint.yml', $root);

        $container = new Container;

        $container->addPackage(new SamplePackage);

        $this->app = $app->setContainer($container);
    }

    /**
     * @return void
     */
    public function test_argument_as_array()
    {
        $command = $this->findCommand('words');

        $input = array('words' => array('hello', 'world'));

        $command->execute($input);

        $expected = 'hello, world';

        $actual = $this->getActualDisplay($command);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_argument_as_optional()
    {
        $command = $this->findCommand('hello');

        $input = array('name' => 'rougin', 'surname' => 'Gutib');

        $command->execute($input);

        $expected = 'Hello rougin Gutib! You\'re age is 23.';

        $actual = $this->getActualDisplay($command);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_argument_as_optional_array()
    {
        $command = $this->findCommand('greet');

        $input = array('name' => 'rougin');
        $input['aliases'] = array('royce', 'blueprint');

        $command->execute($input);

        $expected = 'Hello rougin alias royce, blueprint!';

        $actual = $this->getActualDisplay($command);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_colored_texts()
    {
        $command = $this->findCommand('text');

        $command->execute(array());

        $pass = '[PASS] This is a info text';
        $fail = '[FAIL] This is a error text';
        $info = '[INFO] This is a question text';
        $warn = '[WARN] This is a comment text';

        $expected = $pass . $fail . $info . $warn;

        $actual = $this->getActualDisplay($command);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_negatable_option()
    {
        $command = $this->findCommand('greet');

        $input = array('name' => 'rougin', '--no-yell' => true);

        $command->execute($input);

        $expected = 'Hello rougin!';

        $actual = $this->getActualDisplay($command);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_option_with_array_value()
    {
        $command = $this->findCommand('text');

        $input = array('--names' => array('rougin', 'blueprints'));
        $input['--texts'] = array('hello', 'world');

        $command->execute((array) $input);

        $names = 'Names: rougin, blueprints';
        $texts = 'Texts: hello, world';
        $pass = '[PASS] This is a info text';
        $fail = '[FAIL] This is a error text';
        $info = '[INFO] This is a question text';
        $warn = '[WARN] This is a comment text';

        $expected = $names . $texts . $pass . $fail . $info . $warn;

        $actual = $this->getActualDisplay($command);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_option_with_default_value()
    {
        $command = $this->findCommand('hello');

        $input = array('name' => 'rougin');

        $command->execute($input);

        $expected = 'Hello rougin! You\'re age is 23.';

        $actual = $this->getActualDisplay($command);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_option_with_required_value()
    {
        $command = $this->findCommand('hello');

        $input = array('name' => 'rougin', '--yell' => 'loud');

        $command->execute($input);

        $expected = 'HELLO ROUGIN! YOU\'RE AGE IS 23.';

        $actual = $this->getActualDisplay($command);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_run_command_inside_command()
    {
        $command = $this->findCommand('test');

        $command->execute(array());

        $expected = 'Hello Rougin! You\'re age is 23.';

        $expected .= '[PASS] runCommand returned okay!';

        $actual = $this->getActualDisplay($command);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_simple_command()
    {
        $command = $this->findCommand('greet');

        $input = array('name' => 'Rougin');

        $command->execute($input);

        $expected = 'Hello Rougin!';

        $actual = $this->getActualDisplay($command);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_yell_option()
    {
        $command = $this->findCommand('greet');

        $input = array('name' => 'Rougin', '--yell' => true);

        $command->execute($input);

        $expected = 'HELLO ROUGIN!';

        $actual = $this->getActualDisplay($command);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @param string $name
     *
     * @return \Symfony\Component\Console\Tester\CommandTester
     */
    protected function findCommand($name)
    {
        return new CommandTester($this->app->make()->find($name));
    }

    /**
     * @param \Symfony\Component\Console\Tester\CommandTester $tester
     *
     * @return string
     */
    protected function getActualDisplay(CommandTester $tester)
    {
        $actual = $tester->getDisplay();

        $actual = str_replace("\r\n", '', $actual);

        return str_replace("\n", '', $actual);
    }
}
