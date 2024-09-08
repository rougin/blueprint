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
class Wrapper extends Symfony
{
    /**
     * @var \Rougin\Blueprint\Command
     */
    protected $command;

    /**
     * @param \Rougin\Blueprint\Command $command
     */
    public function __construct(Command $command)
    {
        $this->command = $command;

        $this->command->init();

        $name = $command->getName();

        parent::__construct($name);

        $aliases = $command->getAliases();

        if ($aliases)
        {
            $this->setAliases($aliases);
        }

        $description = $command->getDescription();

        if ($description)
        {
            $this->setDescription($description);
        }

        $help = $command->getHelp();

        if ($help)
        {
            $this->setHelp($help);
        }
    }

    /**
     * Checks whether the command is enabled or not in the current environment.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->command->isEnabled();
    }

    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $arguments = $this->command->getArguments();

        foreach ($arguments as $item)
        {
            /** @var string */
            $name = $item[0];

            /** @var string */
            $description = $item[1];

            /** @var string|null */
            $default = $item[2];

            /** @var integer */
            $mode = $item[3];

            $this->addArgument($name, $mode, $description, $default);
        }

        $options = $this->command->getOptions();

        foreach ($options as $item)
        {
            /** @var string */
            $name = $item[0];

            /** @var string */
            $description = $item[1];

            /** @var string|null */
            $default = $item[2];

            /** @var string|null */
            $shortcut = $item[2];

            /** @var integer */
            $mode = $item[4];

            // Compatibility with the InputOption::VALUE_NEGATABLE (Symfony 5.3) -----------------------
            if ($mode === Command::VALUE_NEGATABLE)
            {
                $this->addOption($name, $shortcut, Command::VALUE_NONE, $description, $default);

                $this->addOption('no-' . $name, $shortcut, Command::VALUE_NONE, $description, $default);

                continue;
            }
            // -----------------------------------------------------------------------------------------

            $this->addOption($name, $shortcut, $mode, $description, $default);
        }
    }

    /**
     * Executes the current command.
     *
     * @return integer
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->command->setInput($input);

        $this->command->setOutput($output);

        return $this->command->run();
    }
}
