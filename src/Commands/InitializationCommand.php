<?php

namespace Rougin\Blueprint\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Initialization Command
 *
 * Creates a blueprint.yml or a defined file name in the specified directory.
 * 
 * @package Blueprint
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class InitializationCommand extends Command
{
    /**
     * Sets the configurations of the current command.
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
     * Executes the current command.
     * 
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return void|string
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $blueprint = file_get_contents(__DIR__ . '/../Templates/blueprint.yml');

        $file = fopen(BLUEPRINT_FILENAME, 'wb');
        file_put_contents(BLUEPRINT_FILENAME, $blueprint);
        fclose($file);

        $text = '"' . BLUEPRINT_FILENAME . '" has been created successfully!';

        return $output->writeln('<info>' . $text . '</info>');
    }
}
