<?php

namespace Rougin\Blueprint;

use Auryn\Injector;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Application as Symfony;
use Symfony\Component\Yaml\Yaml;

/**
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
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
     * @param string|null          $filename
     * @param \Auryn\Injector|null $injector
     * @param string|null          $directory
     *
     * @return \Rougin\Blueprint\Blueprint
     */
    public static function boot($filename = null, Injector $injector = null, $directory = null)
    {
        $directory = $directory ? $directory : (string) getcwd();

        $injector = $injector ? $injector : new Injector;

        $system = new Filesystem(new Local($directory));

        $injector->share($system);

        $console = new Symfony(self::$name, self::$version);

        $blueprint = new Blueprint($console, $injector);

        return self::paths($blueprint, $directory, $filename);
    }

    /**
     * Returns an array of default values.
     *
     * @return array<string, array<string, string>>
     */
    public static function defaults()
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
     * @param \Rougin\Blueprint\Blueprint $blueprint
     * @param string                      $directory
     * @param string|null                 $filename
     *
     * @return \Rougin\Blueprint\Blueprint
     */
    protected static function paths(Blueprint $blueprint, $directory, $filename = null)
    {
        $slash = DIRECTORY_SEPARATOR;

        $yaml = '';

        if ($filename && file_exists($filename))
        {
            /** @var string */
            $yaml = file_get_contents($filename);
        }

        $yaml = str_replace(array('\\', '/'), $slash, $yaml);

        $search = '%%CURRENT_DIRECTORY%%';

        $yaml = str_replace($search, $directory, $yaml);

        $result = self::defaults();

        if (Yaml::parse($yaml))
        {
            /** @var array<string, array<string, string>> */
            $result = Yaml::parse($yaml);
        }

        $blueprint->setTemplatePath($result['paths']['templates']);

        $blueprint->setCommandPath($result['paths']['commands']);

        $blueprint->setCommandNamespace($result['namespaces']['commands']);

        return $blueprint;
    }
}
