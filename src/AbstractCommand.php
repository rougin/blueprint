<?php

namespace Rougin\Blueprint;

use Symfony\Component\Console\Command\Command;
use Twig_Environment;

/**
 * Abstract Command
 *
 * Extends Command class with Twig's renderer
 * 
 * @package Blueprint
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
abstract class AbstractCommand extends Command
{
    protected $renderer;

    /**
     * @param Twig_Environment $renderer
     */
    public function __construct(Twig_Environment $renderer)
    {
        parent::__construct();

        $this->renderer = $renderer;
    }
}
