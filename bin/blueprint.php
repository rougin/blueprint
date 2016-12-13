<?php

// Includes the Composer Autoloader
require 'vendor/autoload.php';

if (! defined('BLUEPRINT_FILENAME')) {
    define('BLUEPRINT_FILENAME', 'blueprint.yml');
}

// Prepares the console and runs the application
Rougin\Blueprint\Console::boot(BLUEPRINT_FILENAME, new Auryn\Injector)->run();
