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
     * Configures the current command.
     *
     * @return void
     */
    public function init()
    {
        $this->addValueArrayOption('names', 'Names to be displayed');

        $this->addRequiredArrayOption('texts', 'Texts to be displayed');
    }

    /**
     * Executes the command.
     *
     * @return integer
     */
    public function run()
    {
        /** @var string[]|null */
        $names = $this->getOption('names');

        if ($names)
        {
            $this->showText('Names: ' . implode(', ', $names));
        }

        /** @var string[]|null */
        $texts = $this->getOption('texts');

        if ($texts)
        {
            $this->showText('Texts: ' . implode(', ', $texts));
        }

        $this->showPass('This is a info ' . $this->sample->getName());

        $this->showFail('This is a error ' . $this->sample->getName());

        $this->showInfo('This is a question ' . $this->sample->getName());

        $this->showWarn('This is a comment ' . $this->sample->getName());

        return self::RETURN_SUCCESS;
    }
}
