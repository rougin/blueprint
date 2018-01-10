<?php

// Includes the Composer Autoloader
require 'vendor/autoload.php';

use Rougin\Blueprint\Console;

$file = __DIR__ . '/../src/Templates/blueprint.yml';

Console::boot($file)->run();
