<?php 

namespace Rougin\Blueprint;

use Auryn\Injector;
use Rougin\Blueprint\Blueprint;
use League\Flysystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use League\Flysystem\Adapter\Local;
use Symfony\Component\Console\Application;

/**
 * Blueprint Console
 *
 * A tool for generating files or templates for your PHP projects.
 *
 * @package Blueprint
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Console
{
    /**
     * @var string
     */
    protected static $name = 'Blueprint';

    /**
     * @var string
     */
    protected static $version = '0.4.0';

    /**
     * Prepares the console application.
     *
     * @param  string          $filename
     * @param  \Auryn\Injector $injector
     * @param  string|null     $directory
     * @return \Rougin\Blueprint\Blueprint
     */
    public static function boot($filename = null, Injector $injector = null, $directory = null)
    {
        if (is_null($injector)) {
            $injector = new Injector;
        }

        if (is_null($directory)) {
            $directory = getcwd();
        }

        // Add League's Flysystem to injector
        $injector->share(new Filesystem(new Local($directory)));

        $symfony   = new Application(self::$name, self::$version);
        $blueprint = new Blueprint($symfony, $injector);

        // Sets the path to default in Blueprint
        if (! file_exists($filename)) {
            $blueprint->setCommandPath(__DIR__ . '/Commands');
            $blueprint->setCommandNamespace('Rougin\Blueprint\Commands');

            return $blueprint;
        }

        // Parses the data from a YAML format
        $contents = file_get_contents($filename);
        $contents = str_replace([ '\\', '/' ], DIRECTORY_SEPARATOR, $contents);
        $contents = str_replace('%%CURRENT_DIRECTORY%%', getcwd(), $contents);

        extract(Yaml::parse($contents));

        // Set paths from the parsed result
        $blueprint->setTemplatePath($paths['templates']);
        $blueprint->setCommandPath($paths['commands']);
        $blueprint->setCommandNamespace($namespaces['commands']);

        return $blueprint;
    }
}
