<?php

{% if namespace %}
namespace {{ namespace }};
{% endif %}

use Rougin\Blueprint\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class {{ name | title }}Command extends AbstractCommand
{
    /**
     * Set the configurations of the specified command
     */
    protected function configure()
    {
        $this
            ->setName('{{ name }}')
            ->setDescription('{{ description }}');
    {% for argument in arguments %}
        $this->addArgument(
            '{{ argument.name }}',
            InputArgument::{{ argument.mode }},
            '{{ argument.description }}'
        );
    {% endfor %}
    }

    /**
     * Execute the command
     * 
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Insert your awesome code here
    }
}