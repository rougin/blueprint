<?php

namespace Rougin\Blueprint;

use Symfony\Component\Yaml\Yaml;

/**
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Console
{
    /**
     * Prepares the Console application.
     *
     * @param string|null $file
     * @param string|null $path
     *
     * @return \Rougin\Blueprint\Blueprint
     */
    public static function boot($file = null, $path = null)
    {
        $item = self::defaults();

        if ($file && $path && file_exists($path . '/' . $file))
        {
            $parsed = self::parse($file, $path);

            $item = array_merge($item, $parsed);
        }

        $app = new Blueprint;

        $app->setName($item['name']);
        $app->setVersion($item['version']);

        $app->setCommandNamespace($item['namespace']);

        $app->setCommandPath($item['scripts']);
        $app->setTemplatePath($item['plates']);

        return $app;
    }

    /**
     * Returns the default variables.
     *
     * @return array<string, string>
     */
    protected static function defaults()
    {
        $item = array('name' => 'Blueprint');

        $item['version'] = Blueprint::VERSION;

        $item['namespace'] = 'Rougin\Blueprint\Commands';

        $item['plates'] = __DIR__ . '/Templates';
        $item['scripts'] = __DIR__ . '/Commands';

        return $item;
    }

    /**
     * Returns details from the specified file.
     *
     * @param string $file
     * @param string $path
     *
     * @return array<string, string>
     */
    protected static function parse($file, $path)
    {
        /** @var string */
        $file = file_get_contents($path . '/' . $file);

        // Replace the constant with root path ----
        $search = '%%CURRENT_DIRECTORY%%';

        $file = str_replace($search, $path, $file);
        // ----------------------------------------

        /** @var array<string, array<string, string>> */
        $parsed = Yaml::parse($file);

        $item = array();

        if (array_key_exists('name', $parsed))
        {
            /** @var string */
            $name = $parsed['name'];
            $item['name'] = $name;
        }

        if (array_key_exists('version', $parsed))
        {
            /** @var string */
            $version = $parsed['version'];
            $item['version'] = $version;
        }

        $item['namespace'] = $parsed['namespaces']['commands'];

        $item['plates'] = $parsed['paths']['templates'];
        $item['scripts'] = $parsed['paths']['commands'];

        return $item;
    }
}
