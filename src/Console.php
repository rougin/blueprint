<?php 

namespace Rougin\Blueprint;

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
    protected static $version = '0.5.0';

    /**
     * Prepares the console application.
     *
     * @param  string          $filename
     * @param  \Auryn\Injector $injector
     * @param  string|null     $directory
     * @return \Rougin\Blueprint\Blueprint
     */
    public static function boot($filename, \Auryn\Injector $injector, $directory = null)
    {
        if (is_null($directory) || empty($directory)) {
            $directory = getcwd();
        }

        // Add League's Flysystem to injector
        $adapter = new \League\Flysystem\Adapter\Local($directory);

        $injector->share(new \League\Flysystem\Filesystem($adapter));

        $symfony   = new \Symfony\Component\Console\Application(self::$name, self::$version);
        $blueprint = new \Rougin\Blueprint\Blueprint($symfony, $injector);

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

        $result = \Symfony\Component\Yaml\Yaml::parse($contents);

        // Set paths from the parsed result
        $blueprint->setTemplatePath($result['paths']['templates']);
        $blueprint->setCommandPath($result['paths']['commands']);
        $blueprint->setCommandNamespace($result['namespaces']['commands']);

        return $blueprint;
    }
}
