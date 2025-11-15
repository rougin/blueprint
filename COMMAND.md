`Command` provides a declarative way to create console commands by extending it from [Symfony Console](https://symfony.com/doc/current/components/console.html):

``` php
// src/Commands/GreetCommand.php

namespace Acme\Commands;

use Rougin\Blueprint\Command;

class GreetCommand extends Command
{
    protected $name = 'greet';

    protected $description = 'Greets the user';

    public function run()
    {
        $this->showText('Hello, World!');

        return self::RETURN_SUCCESS;
    }
}
```

``` bash
$ vendor/bin/blueprint test
```

``` bash
Hello, World!
```

## Basic structure

Its structure is defined by the following properties and methods:

``` php
/**
 * The name of the command.
 *
 * @var string
 */
protected $name;
```

``` php
/**
 * A short description of what the command does.
 *
 * @var string|null
 */
protected $description = null;
```

``` php
/**
 * Configures the current command with arguments and options.
 *
 * @return void
 */
public function init()
```

``` php
/**
 * Executes the command.
 *
 * @return integer
 */
public function run()
```

## Using arguments

Arguments can be defined in the `init` method. Its values are retrieved in `run` method using `getArgument`:

``` php
// src/Commands/GreetCommand.php

namespace Acme\Commands;

use Rougin\Blueprint\Command;

class GreetCommand extends Command
{
    protected $name = 'greet';

    // ...

    public function init()
    {
        $this->addArgument('name', 'Name of user');
    }

    public function run()
    {
        $name = $this->getArgument('name');

        $this->showText('Hello, ' . $name '!');

        // ...
    }
}
```

``` bash
$ vendor/bin/blueprint greet "Bluey"
```

``` bash
Hello, Bluey!
```

Below are the available methods related to arguments:

``` php
/**
 * Adds an argument.
 *
 * @param string     $name
 * @param string     $description
 * @param mixed|null $default
 * @param integer    $mode
 *
 * @return self
 */
protected function addArgument($name, $description, $default = null, $mode = self::INPUT_REQUIRED)
```

``` php
/**
 * Adds an optional argument.
 *
 * @param string     $name
 * @param string     $description
 * @param mixed|null $default
 *
 * @return self
 */
protected function addOptionalArgument($name, $description, $default = null)
```

``` php
/**
 * Adds a required argument as an array.
 *
 * @param string     $name
 * @param string     $description
 * @param mixed|null $default
 *
 * @return self
 */
protected function addArrayArgument($name, $description, $default = null)
```

``` php
/**
 * Adds an optional argument as an array.
 *
 * @param string     $name
 * @param string     $description
 * @param mixed|null $default
 *
 * @return self
 */
protected function addOptionalArrayArgument($name, $description, $default = null)
{
    return $this->addArgument($name, $description, $default, self::INPUT_OPTIONAL | self::INPUT_IS_ARRAY);
}
```

## Using options

Options (flags) can also be defined in the `init` method and can be retrieved with `getOption`:

``` php
// src/Commands/GreetCommand.php

namespace Acme\Commands;

use Rougin\Blueprint\Command;

class GreetCommand extends Command
{
    protected $name = 'greet';

    // ...

    public function init()
    {
        // ...

        $this->addValueOption('age', 'Age of user', 23);
    }

    public function run()
    {
        // ...

        $age = $this->getOption('age');

        $this->showText('Your age is ' . $age);

        // ...
    }
}
```

``` bash
$ vendor/bin/blueprint greet "Bluey" --age=30
```

``` bash
Hello, Bluey!
Your age is 30
```

Below are the available methods for adding options:

``` php
/**
 * Adds an option.
 *
 * @param string      $name
 * @param string      $description
 * @param mixed|null  $default
 * @param string|null $shortcut
 * @param integer     $mode
 *
 * @return self
 */
protected function addOption($name, $description, $default = null, $shortcut = null, $mode = self::VALUE_NONE)
```

``` php
/**
 * Adds an option with a value (e.g., --yell or --yell=loud).
 *
 * @param string      $name
 * @param string      $description
 * @param mixed|null  $default
 * @param string|null $shortcut
 *
 * @return self
 */
protected function addValueOption($name, $description, $default = null, $shortcut = null)
```

``` php
/**
 * Adds a required option with a value (e.g., --yell or --yell=loud).
 *
 * @param string      $name
 * @param string      $description
 * @param mixed|null  $default
 * @param string|null $shortcut
 *
 * @return self
 */
protected function addRequiredOption($name, $description, $default = null, $shortcut = null)
```

``` php
/**
 * Adds an option with a value as an array.
 *
 * @param string      $name
 * @param string      $description
 * @param mixed|null  $default
 * @param string|null $shortcut
 *
 * @return self
 */
protected function addValueArrayOption($name, $description, $default = null, $shortcut = null)
```

``` php
/**
 * Adds a negatable option (e.g., --yell and --no-yell).
 *
 * @param string      $name
 * @param string      $description
 * @param string|null $shortcut
 *
 * @return self
 */
protected function addNegatableOption($name, $description, $shortcut = null)
```

## Handling input, output

Argument and option values can be accessed in `run` method using `getArgument` and `getOption`:

``` php
/**
 * Returns the value for the specified argument.
 *
 * @param string $name
 *
 * @return mixed
 */
protected function getArgument($name)
```

``` php
/**
 * Returns the value for the specified option.
 *
 * @param string $name
 *
 * @return mixed
 */
protected function getOption($name)
```

While styled output is written using the following methods:

``` php
/**
 * Writes a text to the console.
 *
 * @param string       $text
 * @param integer|null $type
 *
 * @return mixed
 */
protected function showText($text, $type = null)
```

``` php
/**
 * Shows a text with "[PASS]" prefix.
 *
 * @param string $text
 *
 * @return mixed
 */
protected function showPass($text)
```

``` php
/**
 * Shows a text with "[FAIL]" prefix.
 *
 * @param string $text
 *
 * @return mixed
 */
protected function showFail($text)
```

``` php
/**
 * Shows a text with "[INFO]" prefix.
 *
 * @param string $text
 *
 * @return mixed
 */
protected function showInfo($text)
```

``` php
/**
 * Shows a text with "[WARN]" prefix.
 *
 * @param string $text
 *
 * @return mixed
 */
protected function showWarn($text)
```

## Running commands

Other commands can be ran using `runCommand` method:

``` php
$config = array('arg' => 'value', '--option' => true);

$this->runCommand('other:command', $config);
```

## Dependency injection

If a [PSR-11](https://www.php-fig.org/psr/psr-11/) container is used, dependencies are automatically injected into the command's `__construct` method:

``` php
// src/Commands/GreetCommand.php

namespace Acme\Commands;

use Acme\Services\MyService;
use Rougin\Blueprint\Command;

class GreetCommand extends Command
{
    // ...

    public function __construct(MyService $service)
    {
        $this->service = $service;
    }

    public function run()
    {
        // ...
    }
}
```
