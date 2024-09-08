<?php

namespace Rougin\Blueprint\Fixture\Packages;

use Rougin\Blueprint\Fixture\Sample;
use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Integration\IntegrationInterface;

/**
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class SamplePackage implements IntegrationInterface
{
    /**
     * Defines the specified integration.
     *
     * @param \Rougin\Slytherin\Container\ContainerInterface $container
     * @param \Rougin\Slytherin\Integration\Configuration    $config
     *
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(ContainerInterface $container, Configuration $config)
    {
        return $container->set('Rougin\Blueprint\Fixture\Sample', new Sample('text'));
    }
}
