<?php

namespace Rougin\Blueprint\TestApp\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create Class Command
 *
 * @package TestApp
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class CreateClassCommand extends AbstractCommand
{
    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('create:class')
            ->setDescription('Create a new class')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Name of the class'
            )->addArgument(
                'description',
                InputArgument::OPTIONAL,
                'Description of the class',
                'A simple class'
            )->addArgument(
                'author',
                InputArgument::OPTIONAL,
                'Author of the class',
                'John Doe'
            )->addArgument(
                'path',
                InputArgument::OPTIONAL,
                'Path where to save the created class',
                __DIR__
            )
        ;
    }

    /**
     * Executes the current command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Input\OutputInterface $output
     * @return boolean|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = [
            'name' => $input->getArgument('name'),
            'description' => $input->getArgument('description'),
            'author' => $input->getArgument('author')
        ];

        // Gets the "NewClass.php" file from "templates" directory
        $class = $this->renderer->render('NewClass.php', $data);

        $file = fopen($input->getArgument('path') . '/' . $name, 'wb');
        file_put_contents($input->getArgument('path') . '/' . $name, $class);
        fclose($file);

        $text = '"' . $path . '/' . $name . '" has been created successfully!';

        return $output->writeln('<info>' . $text . '</info>');
    }
}
