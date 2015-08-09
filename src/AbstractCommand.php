<?php

namespace Rougin\Blueprint;

use Symfony\Component\Console\Command\Command;
use Twig_Environment;

abstract class AbstractCommand extends Command
{
    protected $renderer;

    public function __construct(Twig_Environment $renderer)
    {
        parent::__construct();

        $this->renderer = $renderer;
    }
}