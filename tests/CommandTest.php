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
        $root = __DIR__ . '/Fixture';

        $app = Console::boot('blueprint.yml', $root);

        $container = new Container;

        $container->addPackage(new SamplePackage);

        $this->app = $app->setContainer($container);
    }

    /**
     * @return void
     */
    public function test_passed_if_argument_as_array_works()
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
    public function test_passed_if_colored_texts_displayed()
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
    public function test_passed_if_negatable_option_works()
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
    public function test_passed_if_nested_command_runs()
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
    public function test_passed_if_optional_argument_works()
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
    public function test_passed_if_optional_array_arg_works()
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
    public function test_passed_if_option_default_value_used()
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
    public function test_passed_if_option_with_array_value()
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
    public function test_passed_if_required_option_works()
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
    public function test_passed_if_simple_command_runs()
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
    public function test_passed_if_yell_option_works()
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
