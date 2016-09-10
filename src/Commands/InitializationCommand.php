<?php

namespace Rougin\Blueprint\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Initialization Command
 *
 * Creates a blueprint.yml or a defined file name in the specified directory.
 *
 * @package Blueprint
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class InitializationCommand extends AbstractCommand
{
    protected $filename = 'blueprint.yml';

    /**
     * Checks whether the command is enabled or not in the current environment.
     *
     * Override this to check for x or y and return false if the command can not
     * run properly under the current conditions.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return ! file_exists($this->filename);
    }

    /**
     * Sets the configurations of the current command.
     *
     * @return void
     */
    protected function configure()
    {
        if (defined('BLUEPRINT_FILENAME')) {
            $this->filename = BLUEPRINT_FILENAME;
        }

        $this
            ->setName('init')
            ->setDescription('Creates a ' . $this->filename);
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
        $filename = defined('BLUEPRINT_FILENAME') ? BLUEPRINT_FILENAME : $this->filename;
        $template = file_get_contents(__DIR__ . '/../Templates/blueprint.yml');

        $this->filesystem->write($filename, $template);

        $text = '"' . $filename . '" has been created successfully!';

        return $output->writeln('<info>' . $text . '</info>');
    }
}
