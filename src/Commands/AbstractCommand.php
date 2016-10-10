<?php

namespace Rougin\Blueprint\Commands;

use League\Flysystem\Filesystem;

/**
 * Abstract Command
 *
 * @package Blueprint
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
abstract class AbstractCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \League\Flysystem\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Twig_Environment
     */
    protected $renderer;

    /**
     * @param \League\Flysystem\Filesystem $filesystem
     * @param \Twig_Environment            $renderer
     */
    public function __construct(Filesystem $filesystem, \Twig_Environment $renderer = null)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
        $this->renderer   = $renderer;
    }
}
