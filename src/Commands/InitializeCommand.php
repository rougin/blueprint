<?php

namespace Rougin\Blueprint\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Initialize Command
 *
 * Creates a blueprint.yml or a defined file name in the specified directory.
 *
 * @package Blueprint
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class InitializeCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $filename = 'blueprint.yml';

    /**
     * Checks whether the command is enabled or not in the current environment.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return file_exists($this->filename) === false;
    }

    /**
     * Sets the configurations of the current command.
     *
     * @return void
     */
    protected function configure()
    {
        defined('BLUEPRINT_FILENAME') && $this->filename = BLUEPRINT_FILENAME;

        $this->setName('init')->setDescription('Creates a ' . $this->filename);
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
        $template = file_get_contents(__DIR__ . '/../Templates/blueprint.yml');

        $this->filesystem->write($this->filename, $template);

        $text = '"' . $this->filename . '" has been created successfully!';

        return $output->writeln('<info>' . $text . '</info>');
    }
}
