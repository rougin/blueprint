<?php

namespace Rougin\Blueprint\Commands;

use Rougin\Blueprint\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Initialization Command
 *
 * Creates a blueprint.yml or a defined file name in the current directory
 * 
 * @package Blueprint
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class InitializationCommand extends AbstractCommand
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
        $blueprint = $this->renderer->render('Blueprint.yml');

        $file = fopen(BLUEPRINT_FILENAME, 'wb');
        file_put_contents(BLUEPRINT_FILENAME, $blueprint);
        fclose($file);

        $text = '"' . BLUEPRINT_FILENAME . '" has been created successfully!';

        return $output->writeln('<info>' . $text . '</info>');
    }
}
