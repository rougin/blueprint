# Blueprint

[![Latest Stable Version](https://poser.pugx.org/rougin/blueprint/v/stable)](https://packagist.org/packages/rougin/blueprint) [![Total Downloads](https://poser.pugx.org/rougin/blueprint/downloads)](https://packagist.org/packages/rougin/blueprint) [![Latest Unstable Version](https://poser.pugx.org/rougin/blueprint/v/unstable)](https://packagist.org/packages/rougin/blueprint) [![License](https://poser.pugx.org/rougin/blueprint/license)](https://packagist.org/packages/rougin/blueprint) [![endorse](https://api.coderwall.com/rougin/endorsecount.png)](https://coderwall.com/rougin)

[Blueprint](http://rougin.github.io/blueprint/) is a tool for generating files for your PHP projects.

# Installation

Install ```Blueprint``` via [Composer](https://getcomposer.org):

```$ composer require rougin/blueprint```

```Blueprint``` can be executed using:

```$ php vendor/bin/blueprint```

Initialize a ```blueprint.yml``` to the current working directory by:

```$ php vendor/bin/blueprint init```

# Usage

Before doing something, you must specify the ```blueprint.yml``` where to find your commands and templates and its namespaces

```yml
paths:
    templates: %%CURRENT_DIRECTORY%%/templates
    commands: %%CURRENT_DIRECTORY%%/commands

namespaces:
    commands: Acme\Console\Commands
```

First, let's create a command that will create a simple PHP class. With that, let's create a template for that:

**templates/NewClass.php**

```php
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

**commands/CreateClassCommand.php**

If your familiar with [Symfony's Console Component](http://symfony.com/doc/current/components/console/introduction.html) (which I used in this library), take note that we use an ```AbstractCommand``` in the example below. This class extends to a ```Command``` class and includes a [Twig](http://twig.sensiolabs.org/) template engine for helping you to generate files (if you want).

```php
namespace Acme\Console\Commands;

use Rougin\Blueprint\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
                'Description of the class'
            )->addArgument(
                'author',
                InputArgument::OPTIONAL,
                'Author of the class'
            )->addArgument(
                'path',
                InputArgument::OPTIONAL,
                'Path where to save the created class'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        $description = ($input->getArgument('description'))
            ? $input->getArgument('description')
            : 'A simple class';

        $author = ($input->getArgument('author'))
            ? $input->getArgument('author')
            : 'John Doe';

        $path = ($input->getArgument('path'))
            ? $input->getArgument('path')
            : __DIR__;

        $data = [
            'name' => $name,
            'description' => $description,
            'author' => $author
        ];

        // Gets the "NewClass.php" file from "templates" directory
        $class = $this->renderer->render('NewClass.php', $data);

        $file = fopen($path . '/' . $name, 'wb');
        file_put_contents($path . '/' . $name, $class);
        fclose($file);

        $text = '"' . $path . '/' . $name . '" has been created successfully!';

        return $output->writeln('<info>' . $text . '</info>');
    }
}
```

Lastly, you can now create a new simple class using:

```php vendor/bin/blueprint create:class HelloWorld.php```

# Extensibility

You can also create a new generator by extending ```Blueprint``` to your console application:

**NOTE:** The example below is based from [Combustor](https://github.com/rougin/combustor), a tool for speeding up web development in [CodeIgniter](codeigniter.com).

```php
...

$filePath = realpath(__DIR__ . '/../combustor.yml');
$directory = str_replace('/combustor.yml', '', $filePath);

define('BLUEPRINT_FILENAME', $filePath);
define('BLUEPRINT_DIRECTORY', $directory);

...

// Load the Blueprint library
$blueprint = include($vendor . '/rougin/blueprint/bin/blueprint.php');

if ($blueprint->hasError) {
    exit($blueprint->showError());
}

$blueprint->console->setName('Combustor');
$blueprint->console->setVersion('1.1.3');

// Run the Combustor console application
$blueprint->console->run();
```

If you want to use other file names other than ```blueprint.yml```, you can specify it in ```BLUEPRINT_FILENAME``` constant. Same for the working directory of your application, use ```BLUEPRINT_DIRECTORY``` for that.

If you define a ```BLUEPRINT_FILENAME```, make sure that the specified file exists or ```Blueprint``` will return an error.

You can also change the properties (like name and version) of your console application using the ```$blueprint->console``` variable with the help of [Symfony's Console Component](http://symfony.com/doc/current/components/console/introduction.html).

# References

* [Auryn](https://github.com/rdlowrey/Auryn) - used to include and resolve the dependencies of the commands easier
* [Colors](https://github.com/kevinlebrun/colors.php) - responsible for having colors in error messages
* [Symfony's Console Component](http://symfony.com/doc/current/components/console/introduction.html) - the "brain" of this library
* [Symfony's YAML Component](http://symfony.com/doc/current/components/yaml/introduction.html) - gets data from .yml files
* [Twig](http://twig.sensiolabs.org/) - the "hand" of this library