<?php

namespace Rougin\Blueprint\Fixture\Commands;

use Rougin\Blueprint\Command;
use Rougin\Blueprint\Fixture\Sample;

/**
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class TextCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'text';

    /**
     * @var string
     */
    protected $description = 'Show some sample texts';

    /**
     * @var \Rougin\Blueprint\Fixture\Sample
     */
    protected $sample;

    /**
     * @param \Rougin\Blueprint\Fixture\Sample $sample
     */
    public function __construct(Sample $sample)
    {
        $this->sample = $sample;
    }

    /**
     * Executes the command.
     *
     * @return integer
     */
    public function run()
    {
        $this->showPass('This is a info ' . $this->sample->getName());

        $this->showFail('This is a error ' . $this->sample->getName());

        $this->showInfo('This is a question ' . $this->sample->getName());

        $this->showWarn('This is a comment ' . $this->sample->getName());

        return self::RETURN_SUCCESS;
    }
}
