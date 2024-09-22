<?php

namespace Rougin\Blueprint;

use Symfony\Component\Console\Command\Command as Symfony;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Command
{
    /**
     * @see \Symfony\Component\Console\Input\InputArgument::IS_ARRAY
     */
    const INPUT_IS_ARRAY = 4;

    /**
     * @see \Symfony\Component\Console\Input\InputArgument::OPTIONAL
     */
    const INPUT_OPTIONAL = 2;

    /**
     * @see \Symfony\Component\Console\Input\InputArgument::REQUIRED
     */
    const INPUT_REQUIRED = 1;

    /**
     * @see \Symfony\Component\Console\Command\Command::FAILURE
     */
    const RETURN_FAILURE = 1;

    /**
     * @see \Symfony\Component\Console\Command\Command::INVALID
     */
    const RETURN_INVALID = 2;

    /**
     * @see \Symfony\Component\Console\Command\Command::SUCCESS
     */
    const RETURN_SUCCESS = 0;

    /**
     * @see \Symfony\Component\Console\Input\InputOption::VALUE_IS_ARRAY
     */
    const VALUE_IS_ARRAY = 8;

    /**
     * @see \Symfony\Component\Console\Input\InputOption::VALUE_NEGATABLE
     */
    const VALUE_NEGATABLE = 16;

    /**
     * @see \Symfony\Component\Console\Input\InputOption::VALUE_NONE
     */
    const VALUE_NONE = 1;

    /**
     * @see \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL
     */
    const VALUE_OPTIONAL = 4;

    /**
     * @see \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED
     */
    const VALUE_REQUIRED = 2;

    const TEXT_PASS = 0;

    const TEXT_INFO = 1;

    const TEXT_WARN = 2;

    const TEXT_FAIL = 3;

    /**
     * @var string[]
     */
    protected $aliases = array();

    /**
     * @var mixed[][]
     */
    protected $arguments = array();

    /**
     * @var string|null
     */
    protected $description = null;

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * @var string|null
     */
    protected $help = null;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed[][]
     */
    protected $options = array();

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * Returns the aliases for the command.
     *
     * @return string[]
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * Returns the defined arguments.
     *
     * @return mixed[][]
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Returns the description for the command.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the help for the command.
     *
     * @return string|null
     */
    public function getHelp()
    {
        return $this->help;
    }

    /**
     * Returns the command name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the defined options.
     *
     * @return mixed[][]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @codeCoverageIgnore
     *
     * Configures the current command.
     *
     * @return void
     */
    public function init()
    {
    }

    /**
     * Checks whether the command is enabled or not in the current environment.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * @codeCoverageIgnore
     *
     * Executes the command.
     *
     * @return integer
     */
    public function run()
    {
        return self::RETURN_SUCCESS;
    }

    /**
     * Sets the Input from Symfony Console.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return self
     */
    public function setInput(InputInterface $input)
    {
        $this->input = $input;

        return $this;
    }

    /**
     * Sets the Output from Symfony Console.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return self
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * Adds an argument.
     *
     * @param string     $name
     * @param string     $description
     * @param mixed|null $default
     * @param integer    $mode
     *
     * @return self
     */
    protected function addArgument($name, $description, $default = null, $mode = self::INPUT_REQUIRED)
    {
        $this->arguments[] = array($name, $description, $default, $mode);

        return $this;
    }

    /**
     * Adds a required argument as an array.
     *
     * @param string     $name
     * @param string     $description
     * @param mixed|null $default
     *
     * @return self
     */
    protected function addArrayArgument($name, $description, $default = null)
    {
        return $this->addArgument($name, $description, $default, self::INPUT_REQUIRED | self::INPUT_IS_ARRAY);
    }

    /**
     * Adds a negatable option (e.g., --yell and --no-yell).
     *
     * @param string      $name
     * @param string      $description
     * @param string|null $shortcut
     *
     * @return self
     */
    protected function addNegatableOption($name, $description, $shortcut = null)
    {
        return $this->addOption($name, $description, null, $shortcut, self::VALUE_NEGATABLE);
    }

    /**
     * Adds an option.
     *
     * @param string      $name
     * @param string      $description
     * @param mixed|null  $default
     * @param string|null $shortcut
     * @param integer     $mode
     *
     * @return self
     */
    protected function addOption($name, $description, $default = null, $shortcut = null, $mode = self::VALUE_NONE)
    {
        $this->options[] = array($name, $description, $default, $shortcut, $mode);

        return $this;
    }

    /**
     * Adds an optional argument.
     *
     * @param string     $name
     * @param string     $description
     * @param mixed|null $default
     *
     * @return self
     */
    protected function addOptionalArgument($name, $description, $default = null)
    {
        return $this->addArgument($name, $description, $default, self::INPUT_OPTIONAL);
    }

    /**
     * Adds an optional argument as an array.
     *
     * @param string     $name
     * @param string     $description
     * @param mixed|null $default
     *
     * @return self
     */
    protected function addOptionalArrayArgument($name, $description, $default = null)
    {
        return $this->addArgument($name, $description, $default, self::INPUT_OPTIONAL | self::INPUT_IS_ARRAY);
    }

    /**
     * Adds a required option with a value as an array.
     *
     * @param string      $name
     * @param string      $description
     * @param mixed|null  $default
     * @param string|null $shortcut
     *
     * @return self
     */
    protected function addRequiredArrayOption($name, $description, $default = null, $shortcut = null)
    {
        return $this->addOption($name, $description, $default, $shortcut, self::VALUE_REQUIRED | self::VALUE_IS_ARRAY);
    }

    /**
     * Adds a required option with a value (e.g., --yell or --yell=loud).
     *
     * @param string      $name
     * @param string      $description
     * @param mixed|null  $default
     * @param string|null $shortcut
     *
     * @return self
     */
    protected function addRequiredOption($name, $description, $default = null, $shortcut = null)
    {
        return $this->addOption($name, $description, $default, $shortcut, self::VALUE_REQUIRED);
    }

    /**
     * Adds an option with a value as an array.
     *
     * @param string      $name
     * @param string      $description
     * @param mixed|null  $default
     * @param string|null $shortcut
     *
     * @return self
     */
    protected function addValueArrayOption($name, $description, $default = null, $shortcut = null)
    {
        return $this->addOption($name, $description, $default, $shortcut, self::VALUE_OPTIONAL | self::VALUE_IS_ARRAY);
    }

    /**
     * Adds an option with a value (e.g., --yell or --yell=loud).
     *
     * @param string      $name
     * @param string      $description
     * @param mixed|null  $default
     * @param string|null $shortcut
     *
     * @return self
     */
    protected function addValueOption($name, $description, $default = null, $shortcut = null)
    {
        return $this->addOption($name, $description, $default, $shortcut, self::VALUE_OPTIONAL);
    }

    /**
     * Returns the value for the specified argument.
     *
     * @param string $name
     *
     * @return mixed
     */
    protected function getArgument($name)
    {
        return $this->input->getArgument($name);
    }

    /**
     * Returns the value for the specified option.
     *
     * @param string $name
     *
     * @return mixed
     */
    protected function getOption($name)
    {
        return $this->input->getOption($name);
    }

    /**
     * Shows a text with [FAIL] prefix.
     *
     * @param string $text
     *
     * @return mixed
     */
    protected function showFail($text)
    {
        return $this->showText($text, self::TEXT_FAIL);
    }

    /**
     * Shows a text with [INFO] prefix.
     *
     * @param string $text
     *
     * @return mixed
     */
    protected function showInfo($text)
    {
        return $this->showText($text, self::TEXT_INFO);
    }

    /**
     * Shows a text with [PASS] prefix.
     *
     * @param string $text
     *
     * @return mixed
     */
    protected function showPass($text)
    {
        return $this->showText($text, self::TEXT_PASS);
    }

    /**
     * Writes a text to the console.
     *
     * @param string       $text
     * @param integer|null $type
     *
     * @return mixed
     */
    protected function showText($text, $type = null)
    {
        if ($type === null)
        {
            return $this->output->writeln($text);
        }

        $types = array(self::TEXT_PASS => 'info');
        $types[self::TEXT_FAIL] = 'error';
        $types[self::TEXT_INFO] = 'question';
        $types[self::TEXT_WARN] = 'comment';

        $texts = array(self::TEXT_PASS => '[PASS]');
        $texts[self::TEXT_FAIL] = '[FAIL]';
        $texts[self::TEXT_INFO] = '[INFO]';
        $texts[self::TEXT_WARN] = '[WARN]';

        $code = $types[$type];

        $text = "<$code>{$texts[$type]} $text</$code>";

        return $this->output->writeln($text);
    }

    /**
     * Shows a text with [WARN] prefix.
     *
     * @param string $text
     *
     * @return mixed
     */
    protected function showWarn($text)
    {
        return $this->showText($text, self::TEXT_WARN);
    }
}
