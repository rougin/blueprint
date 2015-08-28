<?php

namespace Rougin\Blueprint\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InitializationCommand extends Command
{
    /**
     * Set the configurations of the current command
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Create a ' . BLUEPRINT_FILENAME)
            ->addArgument(
                'path',
                InputArgument::OPTIONAL,
                'Path to place ' . BLUEPRINT_FILENAME
            )->addArgument(
                'template_path',
                InputArgument::OPTIONAL,
                'Path to the template directory'
            );
    }

    /**
     * Execute the current command
     * 
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $template = file_get_contents(__DIR__ . '/../Templates/Command.php');
        $blueprint = file_get_contents(__DIR__ . '/../Templates/Blueprint.yml');

        $file = fopen(BLUEPRINT_FILENAME, 'wb');
        file_put_contents(BLUEPRINT_FILENAME, $blueprint);
        fclose($file);

        return $output->writeln(
            '<info>"' . BLUEPRINT_FILENAME . '" has been created successfully!</info>'
        );
    }
}