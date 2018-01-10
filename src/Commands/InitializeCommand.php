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
     * Checks whether the command is enabled or not in the current environment.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return file_exists('blueprint.yml') === false;
    }

    /**
     * Sets the configurations of the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $message = 'Creates a blueprint.yml file';

        $this->setName('init')->setDescription($message);
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
        $filepath = __DIR__ . '/../Templates/blueprint.yml';

        $template = file_get_contents($filepath);

        $this->filesystem->write('blueprint.yml', $template);

        $text = '"blueprint.yml" has been created successfully!';

        return $output->writeln('<info>' . $text . '</info>');
    }
}
