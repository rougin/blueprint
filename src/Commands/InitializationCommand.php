<?php

namespace Rougin\Blueprint\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rougin\Blueprint\Common\File;

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
            ->setDescription('Creates a ' . BLUEPRINT_FILENAME);
    }

    /**
     * Executes the current command.
     * 
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Input\OutputInterface $output
     * @return void|string
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $template = new File(__DIR__ . '/../Templates/blueprint.yml', 'r');
        $yml = new File(BLUEPRINT_FILENAME);

        $yml->putContents($template->getContents());
        $yml->chmod(0777);
        $yml->close();

        $text = '"' . BLUEPRINT_FILENAME . '" has been created successfully!';

        return $output->writeln('<info>' . $text . '</info>');
    }
}
