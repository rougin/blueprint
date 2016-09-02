<?php

namespace Rougin\Blueprint\Commands;

use Twig_Environment;
use Symfony\Component\Console\Command\Command;

/**
 * Abstract Command
 *
 * Extends the Symfony\Console\Command class with Twig's renderer.
 *
 * @package Blueprint
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
abstract class AbstractCommand extends Command
{
    /**
     * @var \Twig_Environment
     */
    protected $renderer;

    /**
     * @param \Twig_Environment $renderer
     */
    public function __construct(Twig_Environment $renderer)
    {
        parent::__construct();

        $this->renderer = $renderer;
    }
}
