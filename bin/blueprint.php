<?php

require 'vendor/autoload.php';

use Rougin\Blueprint\Console;

$file = getcwd() . '/blueprint.yml';

Console::boot($file)->run();
