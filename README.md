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
> * Add console commands and templates (if required) to their respective directories.

#### Sample console command

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

### Add specified namespace to `composer.json`

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

#### Run the created command

The created commands will be recognized automatically by Blueprint. With this, it could be executed in the same `blueprint` command:

``` bash
$ vendor/bin/blueprint test

Test
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