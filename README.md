# Blueprint

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Creates file-generating commands for your PHP applications.

## Install

Via Composer

``` bash
$ composer require rougin/blueprint
```

## Usage

### Creating a new ```blueprint.yml```

``` bash
$ php vendor/bin/blueprint init
```

Before doing something, you must specify the ```blueprint.yml``` where to find your commands and templates and its namespace:

``` yml
paths:
    templates: %%CURRENT_DIRECTORY%%/Templates
    commands: %%CURRENT_DIRECTORY%%/Commands

namespaces:
    commands: Acme\Console\Commands
```

Also set the PSR-4 autoloader in composer.json and run `composer dump-autoload` after:

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

### Example

In this example, let's create a command that will create a simple PHP class:

**%%CURRENT_DIRECTORY%%/Templates/NewClass.php**

``` php
/**
 * {{ name }}
 *
 * {{ description }}
 *
 * @author {{ author }}
 */
class {{ name | title }}
{
    public function greet()
    {
        return 'Hello world!';
    }
}
```

Then, let's create a command that will generate that said template into a file:

**%%CURRENT_DIRECTORY%%/Commands/NewClass.php**

``` php
namespace Acme\Console\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateClassCommand extends \Rougin\Blueprint\Commands\AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('create:class')
            ->setDescription('Create a new class')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the class')
            ->addArgument('description', InputArgument::OPTIONAL, 'Description of the class', 'A simple class')
            ->addArgument('author', InputArgument::OPTIONAL, 'Author of the class', 'John Doe')
            ->addArgument('path', InputArgument::OPTIONAL, 'Path where to save the created class', __DIR__);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = [
            'author'      => $input->getArgument('author'),
            'description' => $input->getArgument('description'),
            'name'        => $input->getArgument('name'),
        ];

        // Gets the "NewClass.php" file from "templates" directory
        $class = $this->renderer->render('NewClass.php', $data);

        $file = fopen($input->getArgument('path') . '/' . $name, 'wb');
        file_put_contents($input->getArgument('path') . '/' . $name, $class);
        fclose($file);

        $text = '"' . $path . '/' . $name . '" has been created successfully!';

        return $output->writeln('<info>' . $text . '</info>');
    }
}
```

You can now create a new simple class using:

``` bash
$ php vendor/bin/blueprint create:class HelloWorld.php
```

### Extensibility

You can also create a new generator by extending ```Blueprint``` to your console application:

**NOTE:** The example below is based from [Combustor](https://github.com/rougin/combustor), a tool for speeding up web development in [CodeIgniter](codeigniter.com).

``` php
$injector  = new Auryn\Injector;
$console   = new Symfony\Component\Console\Application;
$combustor = new Rougin\Blueprint\Blueprint($console, $injector);

$combustor->console->setName('Combustor');
$combustor->console->setVersion('1.2.3');

$combustor
    ->setTemplatePath(__DIR__ . '/../src/Templates')
    ->setCommandPath(__DIR__ . '/../src/Commands')
    ->setCommandNamespace('Rougin\Combustor\Commands');

$combustor->injector->delegate('CI_Controller', function () {
    return Rougin\SparkPlug\Instance::create();
});

$combustor->injector->delegate('Rougin\Describe\Describe', function () {
    ...

    return new Rougin\Describe\Describe;
});

$combustor->run();
```

You can also change the properties (like name and version) of your console application using the ```$combustor->console``` variable with the help of [Symfony's Console Component](http://symfony.com/doc/current/components/console/introduction.html).

Another example is using the `Console::boot` method (as of `v0.4.0`):

``` php
$injector = new Auryn\Injector;

// Sets the dependencies here using the injector
$injector->delegate('CI_Controller', function () {
    return Rougin\SparkPlug\Instance::create();
});

$injector->delegate('Rougin\Describe\Describe', function () {
    return new Rougin\Describe\Describe;
});

// Checks the data from combustor.yml
$combustor = Rougin\Blueprint\Console::boot('combustor.yml', $injector);

$combustor->console->setName('Combustor');
$combustor->console->setVersion('1.2.3');

$combustor->run();
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

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
