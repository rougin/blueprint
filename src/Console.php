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

        if ($file && $path)
        {
            $full = self::getFilePath($file, $path);

            if ($full !== null)
            {
                $parsed = substr($full, -4) === '.php'
                    ? self::parsePhp($full, $path)
                    : self::parseYml($full, $path);

                $item = array_merge($item, $parsed);
            }
        }

        $app = new Blueprint;

        $app->setCommandPath($item['scripts']);

        $namespace = $item['namespace'];

        $app->setCommandNamespace($namespace);

        $app->setName($item['name']);

        $app->setTemplatePath($item['plates']);

        $app->setVersion($item['version']);

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
     * Recursively replaces the placeholder in the specified data.
     *
     * @param array<string, mixed>|string $data
     * @param string                      $path
     *
     * @return array<string, mixed>|string
     */
    protected static function findDirectory($data, $path)
    {
        $search = '%%CURRENT_DIRECTORY%%';

        if (is_string($data))
        {
            return str_replace($search, $path, $data);
        }

        /** @phpstan-ignore-next-line */
        if (is_array($data))
        {
            foreach ($data as $key => $value)
            {
                /** @var array<string, mixed>|string $value */
                $data[$key] = self::findDirectory($value, $path);
            }
        }

        return $data;
    }

    /**
     * Returns the full filename to load, trying .php first then .yml.
     *
     * @param string $file
     * @param string $path
     *
     * @return string|null
     */
    protected static function getFilePath($file, $path)
    {
        $ext = pathinfo($file, PATHINFO_EXTENSION);

        if ($ext === '')
        {
            if (file_exists($path . '/' . $file . '.php'))
            {
                return $file . '.php';
            }

            if (file_exists($path . '/' . $file . '.yml'))
            {
                return $file . '.yml';
            }

            return null;
        }

        return file_exists($path . '/' . $file) ? $file : null;
    }

    /**
     * Returns details from the specified PHP file.
     *
     * @param string $file
     * @param string $path
     *
     * @return array<string, string>
     */
    protected static function parsePhp($file, $path)
    {
        /** @var array<string, mixed> */
        $parsed = require $path . '/' . $file;

        /** @var array<string, mixed> */
        $parsed = self::findDirectory($parsed, $path);

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

        /** @var array<string, string> */
        $namespaces = $parsed['namespaces'];

        $item['namespace'] = $namespaces['commands'];

        /** @var array<string, string> */
        $paths = $parsed['paths'];

        $item['plates'] = $paths['templates'];

        $item['scripts'] = $paths['commands'];

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
    protected static function parseYml($file, $path)
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

        /** @var array<string, string> */
        $namespaces = $parsed['namespaces'];

        $item['namespace'] = $namespaces['commands'];

        /** @var array<string, string> */
        $paths = $parsed['paths'];

        $item['plates'] = $paths['templates'];

        $item['scripts'] = $paths['commands'];

        return $item;
    }
}
