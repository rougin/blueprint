<?php

use Rougin\Blueprint\Console;

// Return the root directory of the project ---------
$bin = (string) realpath(__DIR__ . '/../../');

$exists = file_exists($bin . '/vendor/autoload.php');

/** @var string */
$root = realpath($exists ? $bin : __DIR__ . '/../');
// --------------------------------------------------

require $root . '/vendor/autoload.php';

Console::boot('blueprint.yml', $root)->run();