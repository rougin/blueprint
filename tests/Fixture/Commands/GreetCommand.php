<?php

namespace Rougin\Blueprint\Fixture\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Greet Command
 *
 * A sample of a greet command.
 *
 * @package Blueprint
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class GreetCommand extends Command
{
    /**
     * Sets the configurations of the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('greet')->setDescription('Greet someone');

        $description = 'Who do you want to greet?';

        $this->addArgument('name', InputArgument::REQUIRED, $description);

        $description = 'If set, the task will yell in uppercase letters';

        $this->addOption('yell', null, InputOption::VALUE_NONE, $description);
    }

    /**
     * Executes the current command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Input\OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = sprintf('Hello %s!', $input->getArgument('name'));

        $input->getOption('yell') && $text = strtoupper($text);

        $output->writeln((string) '<info>' . $text . '</info>');

        return 0;
    }
}
