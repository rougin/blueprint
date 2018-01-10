<?php 

namespace Rougin\Blueprint;

use Auryn\Injector;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Application as Symfony;
use Symfony\Component\Yaml\Yaml;

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
    protected static $version = '0.6.0';

    /**
     * Prepares the console application.
     *
     * @param  string|null          $filename
     * @param  \Auryn\Injector|null $injector
     * @param  string|null          $directory
     * @return \Rougin\Blueprint\Blueprint
     */
    public static function boot($filename = null, Injector $injector = null, $directory = null)
    {
        $injector ?: $injector = new Injector;

        $directory ?: $directory = getcwd();

        $system = new Filesystem(new Local($directory));

        $injector->share($system);

        $console = new Symfony(self::$name, self::$version);

        $blueprint = new Blueprint($console, $injector);

        return self::paths($blueprint, $directory, $filename);
    }

    /**
     * Returns an array of default values.
     *
     * @return array
     */
    protected static function defaults()
    {
        $defaults = array('paths' => array(), 'namespaces' => array());

        $defaults['paths']['templates'] = __DIR__ . '/Templates';

        $defaults['paths']['commands'] = __DIR__ . '/Commands';

        $defaults['namespaces']['commands'] = 'Rougin\Blueprint\Commands';

        return $defaults;
    }

    /**
     * Prepares the paths that are defined from a YAML file.
     *
     * @param  \Rougin\Blueprint\Blueprint $blueprint
     * @param  string                      $directory
     * @param  string|null                 $filename
     * @return \Rougin\Blueprint\Blueprint
     */
    protected static function paths(Blueprint $blueprint, $directory, $filename = null)
    {
        $yaml = file_exists($filename) ? file_get_contents($filename) : '';

        $yaml = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $yaml);

        $yaml = str_replace('%%CURRENT_DIRECTORY%%', $directory, $yaml);

        $result = Yaml::parse($yaml) ?: self::defaults();

        $blueprint->setTemplatePath($result['paths']['templates']);

        $blueprint->setCommandPath($result['paths']['commands']);

        $blueprint->setCommandNamespace($result['namespaces']['commands']);

        return $blueprint;
    }
}
