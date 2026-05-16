<?php

namespace Rougin\Blueprint\Symfony;

/**
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Interop
{
    /**
     * @return boolean
     */
    public static function isVersion6()
    {
        $class = 'Symfony\Component\Console\Command\Command';

        $method = 'execute';

        $class = new \ReflectionMethod($class, $method);

        return method_exists($class, 'hasReturnType')
            && $class->hasReturnType();
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public static function register($name)
    {
        $class = 'Rougin\Blueprint';

        $num = self::isVersion6() ? '6' : '5';

        $orig = $class . '\Symfony\V' . $num . '\\' . $name;

        $temp = '\Symfony' . $name;

        class_alias($orig, $class . $temp);
    }
}
