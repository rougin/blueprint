<?php

namespace Rougin\Blueprint;

use Symfony\Component\Console\Tester\CommandTester;

/**
 * Blueprint Application Test
 *
 * @package Blueprint
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
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
        $this->application = new Application;
    }

    /**
     * Tests adding GreetCommand to application.
     *
     * @return void
     */
    public function testGreetCommand()
    {
        $greet = new Fixture\GreetCommand;

        $this->application->add($greet);
    }
}
