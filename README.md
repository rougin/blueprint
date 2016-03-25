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

#### Creating a new ```blueprint.yml```

``` bash
$ php vendor/bin/blueprint init
```

#### Example

Before doing something, you must specify the ```blueprint.yml``` where to find your commands and templates and its namespace

``` yml
paths:
    templates: %%CURRENT_DIRECTORY%%/Templates
    commands: %%CURRENT_DIRECTORY%%/Commands

namespaces:
    commands: Acme\Console\Commands
```

In this example, let's create a command that will create a simple PHP class:

``` php
<?php
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
?>
```

Then, let's create a command that will generate that said template into a file:

``` php
namespace Acme\Console\Commands;

use Rougin\Blueprint\Commands\AbstractCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateClassCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('create:class')
            ->setDescription('Create a new class')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Name of the class'
            )->addArgument(
                'description',
                InputArgument::OPTIONAL,
                'Description of the class',
                'A simple class'
            )->addArgument(
                'author',
                InputArgument::OPTIONAL,
                'Author of the class',
                'John Doe'
            )->addArgument(
                'path',
                InputArgument::OPTIONAL,
                'Path where to save the created class',
                __DIR__
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = [
            'name' => $input->getArgument('name'),
            'description' => $input->getArgument('description'),
            'author' => $input->getArgument('author')
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

#### Extensibility

You can also create a new generator by extending ```Blueprint``` to your console application:

**NOTE:** The example below is based from [Combustor](https://github.com/rougin/combustor), a tool for speeding up web development in [CodeIgniter](codeigniter.com).

``` php
$injector = new Auryn\Injector;
$console = new Symfony\Component\Console\Application;
$app = new Rougin\Blueprint\Blueprint($console, $injector);

$app->console->setName('Combustor');
$app->console->setVersion('1.2.0');

$app
    ->setTemplatePath(__DIR__ . '/../src/Templates')
    ->setCommandPath(__DIR__ . '/../src/Commands')
    ->setCommandNamespace('Rougin\Combustor\Commands');

$app->injector->delegate('CI_Controller', function () {
    return Rougin\SparkPlug\Instance::create();
});

$app->injector->delegate('Rougin\Describe\Describe', function () {
    ...

    return new Rougin\Describe\Describe;
});

$app->run();
```

You can also change the properties (like name and version) of your console application using the ```$combustor->console``` variable with the help of [Symfony's Console Component](http://symfony.com/doc/current/components/console/introduction.html).

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
