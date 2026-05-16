<?php

$file = array();

// Details for the console app ---
$file['name'] = 'Blueprint';

$file['version'] = '0.7.1';
// -------------------------------

// Path for the commands and templates ----
$paths = array();

$root = '%%CURRENT_DIRECTORY%%';

$paths['templates'] = $root . '/Templates';

$paths['commands'] = $root . '/Commands';

$file['paths'] = $paths;
// ----------------------------------------

$namespace = 'Rougin\Blueprint\Fixture\Commands';

// Namespaces for the commands ---------
$data = array('commands' => $namespace);

$file['namespaces'] = $data;
// -------------------------------------

return $file;
