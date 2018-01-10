# Blueprint

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Bootstraps your file-generating console applications.

## Install

Via Composer

``` bash
$ composer require rougin/blueprint
```

## Usage

### Creating new `blueprint.yml`

``` bash
$ vendor/bin/phpunit init
```

**blueprint.yml**

``` yml
paths:
    templates: %%CURRENT_DIRECTORY%%/src/Templates
    commands: %%CURRENT_DIRECTORY%%/src/Commands

namespaces:
    commands: Rougin\Blueprint\Commands
```

* Replace the values specified in the `blueprint.yml` file
* Add your console commands and templates (if required) to their respective directories

#### Sample console command

**blueprint.yml**

``` yml
...

namespaces:
    commands: Acme\Console\Commands
```

``` php
namespace Acme\Console\Commands;

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

``` json
{
    // ..
    "autoload": {
        "psr-4": {
            "Acme\\Console\\": "src"
        }
    }
    // ..
}
```

``` bash
$ composer dump-project
```

#### Run the "test" command

``` bash
$ vendor/bin/blueprint test
Test
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email rougingutib@gmail.com instead of using the issue tracker.

## Credits

- [Rougin Royce Gutib][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/rougin/blueprint.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/rougin/blueprint/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/rougin/blueprint.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/rougin/blueprint.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rougin/blueprint.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/rougin/blueprint
[link-travis]: https://travis-ci.org/rougin/blueprint
[link-scrutinizer]: https://scrutinizer-ci.com/g/rougin/blueprint/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/rougin/blueprint
[link-downloads]: https://packagist.org/packages/rougin/blueprint
[link-author]: https://github.com/rougin
[link-contributors]: ../../contributors