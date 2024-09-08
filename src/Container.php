<?php

namespace Rougin\Blueprint;

use Rougin\Slytherin\Container\Container as Slytherin;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Integration\IntegrationInterface;

/**
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Container extends Slytherin
{
    /**
     * @var \Rougin\Slytherin\Integration\Configuration
     */
    protected $config;

    /**
     * @param \Rougin\Slytherin\Integration\Configuration|null $config
     */
    public function __construct(Configuration $config = null)
    {
        $this->config = $config ? $config : new Configuration;
    }

    /**
     * Adds a package to the application.
     *
     * @param \Rougin\Slytherin\Integration\IntegrationInterface $package
     *
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function addPackage(IntegrationInterface $package)
    {
        return $package->define($this, $this->config);
    }
}
