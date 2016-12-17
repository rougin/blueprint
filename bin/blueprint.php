<?php

// Includes the Composer Autoloader
require 'vendor/autoload.php';

// Prepares the console and runs the application
Rougin\Blueprint\Console::boot('blueprint.yml')->run();
