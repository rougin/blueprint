<?php

namespace Rougin\Blueprint;

use Symfony\Component\Yaml\Parser as SymfonyParser;

/**
 * Parser
 *
 * Parses YAML strings to convert them to PHP arrays.
 * 
 * @package Blueprint
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Parser
{
    protected $parser;

    /**
     * @param SymfonyParser $parser
     */
    public function __construct(SymfonyParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Parses a YAML string to a PHP value.
     * 
     * @param  string $file
     * @param  string $directory
     * @return array
     */
    public function parse($file, $directory = __DIR__)
    {
        if ( ! file_exists($file)) {
            return [];
        }

        $content = file_get_contents($file);
        $content = str_replace('%%CURRENT_DIRECTORY%%', $directory, $content);

        return $this->parser->parse($content);
    }
}