<?php

require __DIR__ . '/../vendor/autoload.php';

use \Pinepain\SimpleConfig\Loaders\FilesLoader;
use Pinepain\SimpleConfig\Config;

// it's good idea to use some dotenv library here and load env-specific env variables to use them later
// in configs

// Ok, let's load our config. Note, that we load only files that do not starts with `.` (dot) and have
// .php extension
$loader = new FilesLoader();

$repeats = 1;

$t = microtime(true);
for ($i = 0; $i < $repeats; $i++) {
    $loader->load2(__DIR__ . '/config');
}
echo 'Load-2:', microtime(true) - $t, PHP_EOL;

$t = microtime(true);
for ($i = 0; $i < $repeats; $i++) {
    $loader->load(__DIR__ . '/config');
}
echo 'Load-1:', microtime(true) - $t, PHP_EOL;


$t = microtime(true);
for ($i = 0; $i < $repeats; $i++) {
    $loader->load2(__DIR__ . '/config');
}
echo 'Load-2:', microtime(true) - $t, PHP_EOL;


$t = microtime(true);
for ($i = 0; $i < $repeats; $i++) {
    $loader->load(__DIR__ . '/config');
}
echo 'Load-1:', microtime(true) - $t, PHP_EOL;


$t = microtime(true);
for ($i = 0; $i < $repeats; $i++) {
    $loader->load2(__DIR__ . '/config');
}
echo 'Load-2:', microtime(true) - $t, PHP_EOL;

$t = microtime(true);
for ($i = 0; $i < $repeats; $i++) {
    $loader->load(__DIR__ . '/config');
}
echo 'Load-1:', microtime(true) - $t, PHP_EOL;


$t = microtime(true);
for ($i = 0; $i < $repeats; $i++) {
    $loader->load2(__DIR__ . '/config');
}
echo 'Load-2:', microtime(true) - $t, PHP_EOL;
