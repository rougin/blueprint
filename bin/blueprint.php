<?php

// Includes the Composer Autoloader
require realpath('vendor') . '/autoload.php';

if (! defined('BLUEPRINT_FILENAME')) {
    define('BLUEPRINT_FILENAME', 'blueprint.yml');
}

// Prepares the console and runs the application
Rougin\Blueprint\Console::boot(BLUEPRINT_FILENAME)->run();
