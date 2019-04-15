<?php

namespace Rougin\Blueprint;

use Rougin\Slytherin\Container\Container;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Blueprint Application Test
 *
 * @package Blueprint
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
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
    public function setUp()
    {
        $file = __DIR__ . '/Fixture/SampleBlueprint.yml';

        $application = new Application($file);

        $application->container(new Container);

        $this->application = $application;
    }

    /**
     * Tests Application::run with GreetCommand.
     *
     * @return void
     */
    public function testRunMethodWithGreetCommand()
    {
        $console = $this->application->run(true);

        $command = $console->find('greet');

        $tester = new CommandTester($command);

        $input = array('name' => 'Rougin', '--yell' => true);

        $tester->execute($input);

        $expected = '/HELLO ROUGIN!/';

        $result = $tester->getDisplay();

        $this->assertRegExp($expected, $result);
    }

    /**
     * Tests ArrayAccess::offsetGetMethod.
     *
     * @return void
     */
    public function testOffsetGetMethod()
    {
        $expected = __DIR__ . '/Fixture/Commands';

        $this->application['commands'] = $expected;

        $result = $this->application['commands'];

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests ArrayAccess::offsetGetMethod with \InvalidArgumentException.
     *
     * @return void
     */
    public function testOffsetGetMethodWithInvalidArgumentException()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->application['test'] = 'Hello and this is a test';
    }

    /**
     * Tests ArrayAccess::offsetUnsetMethod.
     *
     * @return void
     */
    public function testOffsetUnsetMethod()
    {
        unset($this->application['templates']);

        $result = $this->application['templates'];

        $this->assertNull($result);
    }

    /**
     * Tests Application::__call.
     *
     * @return void
     */
    public function testCallMagicMethod()
    {
        $expected = 'Blueprint';

        $result = $this->application->getName();

        $this->assertEquals($expected, $result);
    }
}
