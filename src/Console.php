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
     * @param  string               $filename
     * @param  \Auryn\Injector|null $injector
     * @param  string|null          $directory
     * @return \Rougin\Blueprint\Blueprint
     */
    public static function boot($filename, \Auryn\Injector $injector = null, $directory = null)
    {
        list($directory, $injector) = self::prepareArguments($directory, $injector);

        // Add League's Flysystem to injector
        $folder = new \League\Flysystem\Adapter\Local($directory);
        $system = new \League\Flysystem\Filesystem($folder);

        $injector->share($system);

        // Define the Blueprint instance
        $application = new \Symfony\Component\Console\Application(self::$name, self::$version);
        $blueprint   = new \Rougin\Blueprint\Blueprint($application, $injector);

        // Sets the path to default in Blueprint
        if (! file_exists($filename)) {
            $blueprint->setCommandPath(__DIR__ . '/Commands');
            $blueprint->setCommandNamespace('Rougin\Blueprint\Commands');

            return $blueprint;
        }

        return self::preparePaths($blueprint, $filename);
    }

    /**
     * Prepares the injector and the directory to be used.
     *
     * @param  string|null          $directory
     * @param  \Auryn\Injector|null $injector
     * @return array
     */
    private static function prepareArguments($directory = null, \Auryn\Injector $injector = null)
    {
        $arguments = [ getcwd(), new \Auryn\Injector ];

        if (! is_null($directory)) {
            $arguments[0] = $directory;
        }

        if (! is_null($injector)) {
            $arguments[1] = $injector;
        }

        return $arguments;
    }

    /**
     * Prepares the paths that are defined from a YAML file.
     *
     * @param  \Rougin\Blueprint\Blueprint $blueprint
     * @param  string                      $filename
     * @return \Rougin\Blueprint\Blueprint
     */
    private static function preparePaths(\Rougin\Blueprint\Blueprint $blueprint, $filename)
    {
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
