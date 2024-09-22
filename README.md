# Blueprint

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Build Status][ico-build]][link-build]
[![Coverage Status][ico-coverage]][link-coverage]
[![Total Downloads][ico-downloads]][link-downloads]

Blueprint is a PHP package for bootstrapping console applications.

## Installation

Install `Blueprint` via [Composer](https://getcomposer.org/):

``` bash
$ composer require rougin/blueprint
```

## Basic Usage

### Creating a new `blueprint.yml`

Create a `blueprint.yml` file by running the `init` command:

``` bash
$ vendor/bin/blueprint init
```

``` yml
# blueprint.yml

name: Blueprint
version: 0.7.0

paths:
  templates: %%CURRENT_DIRECTORY%%/src/Templates
  commands: %%CURRENT_DIRECTORY%%/src/Commands

namespaces:
  commands: Rougin\Blueprint\Commands
```

> [!NOTE]
> * Replace the values specified in the `blueprint.yml` file.
> * Add commands and templates (if applicable) to their respective directories.

### Creating a command

Prior to creating a command, the `commands` property in `blueprint.yml` must be updated:

``` yml
# blueprint.yml

# ...

namespaces:
  commands: Acme\Commands
```

Then create the command (e.g., `TestCommand`) to the specified directory:

``` php
// src/Commands/TestCommand.php

namespace Acme\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    protected function configure()
    {
        $this->setName('test')->setDescription('Returns a "Test" string');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Test</info>');
    }
}
```

### Updating the `composer.json`

After creating the command (e.g., `TestCommand`), kindly check if its namespace is defined in `Composer`:

``` json
// composer.json

// ...

"autoload":
{
  "psr-4":
  {
    "Acme\\": "src"
  }
}

// ...
```

``` bash
$ composer dump-autoload
```

### Running the command

The created commands will be recognized automatically by Blueprint. With this, it could be executed in the same `blueprint` command:

``` bash
$ vendor/bin/blueprint test

Test
```

## Extending Blueprint

The `v0.7.0` version introduces a way to extend the `Blueprint` package to use it as the console application for specified projects.

### Initializing the instance

To initialize a console application, the `Blueprint` class must be created first:

``` php
// bin/app.php

use Rougin\Blueprint\Blueprint;

// Return the root directory of the project ------------
$vendor = (string) __DIR__ . '/../../../../';

$exists = file_exists($vendor . '/vendor/autoload.php');

$root = $exists ? $vendor : __DIR__ . '/../';
// -----------------------------------------------------

require $root . '/vendor/autoload.php';

$app = new Blueprint;
```

After creating the `Blueprint` class, the following details can now be updated:

``` php
// bin/app.php

// ...

// Set the name of the console application. ----
$app->setName('Acme');
// ---------------------------------------------

// Set the version of the console application. ----
$app->setVersion('0.1.0');
// ------------------------------------------------

// Set the directory for the defined commands. ----
$app->setCommandPath(__DIR__ . '/../src/Commands');
// ------------------------------------------------

// Set the directory for the templates. Might be useful ------
// if creating commands with template engines (e.g., Twig) ---
$app->setTemplatePath(__DIR__ . '/../src/Templates');
// -----------------------------------------------------------

// Set the namespace for the "commands" path. ----
$namespace = 'Acme\Simplest\Commands';
$app->setCommandNamespace($namespace);
// -----------------------------------------------

// Run the console application ---
$app->run();
// -------------------------------
```

> [!NOTE]
> Using this approach means the `blueprint.yml` can now be omitted. This approach is also applicable to create customized console applications without the `Blueprint` branding.

Then run the console application in the terminal:

``` bash
$ php bin\app.php

Acme 0.1.0

Usage:
  command [options] [arguments]

Options:
  -h, --help            Display help for the given command. When no command is given display help for the list command
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi|--no-ansi  Force (or disable --no-ansi) ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  completion  Dump the shell completion script
  help        Display help for a command
  list        List commands
```

### Customized `Command` class

`Blueprint` also provides an alternative `Command` class for creating commands with descriptive methods and less code:

``` php
// src/Commands/TestCommand.php

namespace Acme\Commands;

use Rougin\Blueprint\Command;

class TestCommand extends Command
{
    protected $name = 'test';

    protected $description = 'Returns a "Test" string';

    public function execute()
    {
        $this->showPass('Test');
    }
}
```

> [!NOTE]
> All of the functionalities for the `Command` class is derived from the [`Symfony's Console` component](https://symfony.com/doc/current/console.html#creating-a-command).

### Injecting dependencies

To perform [automagic resolutions](https://github.com/rougin/slytherin/wiki/Container) in each defined commands, the `addPackage` can be used with the additional functionality from the `Container` class from [`Slytherin`](https://roug.in/slytherin/):

``` php
// src/Sample.php

namespace Acme;

class Sample
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}
```

``` php
// src/Packages/SamplePackage.php

namespace Acme\Packages;

use Acme\Sample;
use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Integration\IntegrationInterface;

class SamplePackage implements IntegrationInterface
{
    public function define(ContainerInterface $container, Configuration $config)
    {
        return $container->set(Sample::class, new Sample('Blueprint'));
    }
}
```

``` php
// bin/app.php

use Acme\Packages\SamplePackage;
use Rougin\Slytherin\Container\Container;

// ...

// Add the specified integration (or package) to the container ---
$container = new Container;

$container->addPackage(new SamplePackage);
// ---------------------------------------------------------------

// Set the container to the console application ---
$app->setContainer($container);
// ------------------------------------------------

```

With the above-mentioned integration, for any command that uses the `Sample` class will get the `text` value as the `$name` property:

``` php
namespace Acme\Commands;

use Acme\Sample;
use Rougin\Blueprint\Command;

class TextCommand extends Command
{
    protected $name = 'text';

    protected $description = 'Shows a sample text';

    protected $sample;

    public function __construct(Sample $sample)
    {
        $this->sample = $sample;
    }

    public function run()
    {
        $this->showText('Hello, ' . $this->sample->getName() . '!');

        return self::RETURN_SUCCESS;
    }
}
```

``` bash
$ php bin/app.php text

Hello, Blueprint!
```

## Changelog

Please see [CHANGELOG][link-changelog] for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Credits

- [All contributors][link-contributors]

## License

The MIT License (MIT). Please see [LICENSE][link-license] for more information.

[ico-build]: https://img.shields.io/github/actions/workflow/status/rougin/blueprint/build.yml?style=flat-square
[ico-coverage]: https://img.shields.io/codecov/c/github/rougin/blueprint?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rougin/blueprint.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/rougin/blueprint.svg?style=flat-square

[link-build]: https://github.com/rougin/blueprint/actions
[link-changelog]: https://github.com/rougin/blueprint/blob/master/CHANGELOG.md
[link-contributors]: https://github.com/rougin/blueprint/contributors
[link-coverage]: https://app.codecov.io/gh/rougin/blueprint
[link-downloads]: https://packagist.org/packages/rougin/blueprint
[link-license]: https://github.com/rougin/blueprint/blob/master/LICENSE.md
[link-packagist]: https://packagist.org/packages/rougin/blueprint
[link-upgrading]: https://github.com/rougin/blueprint/blob/master/UPGRADING.md