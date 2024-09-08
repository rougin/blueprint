# Blueprint

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Build Status][ico-build]][link-build]
[![Coverage Status][ico-coverage]][link-coverage]
[![Total Downloads][ico-downloads]][link-downloads]

Blueprint is a PHP package for bootstrapping file-generating console applications.

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

// Return the root directory of the project ---------
$bin = (string) realpath(__DIR__ . '/../../');

$exists = file_exists($bin . '/vendor/autoload.php');

/** @var string */
$root = realpath($exists ? $bin : __DIR__ . '/../');
// --------------------------------------------------

require $root . '/vendor/autoload.php';

$app = new Blueprint;
```

After creating the `Blueprint` class, the following details can now be updated:

``` php
// bin/app.php

// ...

// Sets the name of the console application. ---
$app->setName('Acme');
// ---------------------------------------------

// Sets the version of the console application. ---
$app->setVersion('0.1.0');
// ------------------------------------------------

// Sets the directory for the defined commands. ---
$app->setCommandPath($item['scripts']);
// ------------------------------------------------

// Sets the directory for the templates. Might be useful ------
// if creaeting commands with template engines (e.g., Twig) ---
$app->setTemplatePath($item['plates']);
// ------------------------------------------------------------

// Sets the namespace for the "commands" path. ---
$namespace = 'Acme\Simplest\Commands';
$app->setCommandNamespace($namespace);
// -----------------------------------------------
```

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