<?php

require __DIR__ . '/../vendor/autoload.php';

use \Pinepain\SimpleConfig\Loaders\FilesLoader;
use Pinepain\SimpleConfig\Config;

// it's good idea to use some dotenv library here and load env-specific env variables to use them later
// in configs

// Ok, let's load our config. Note, that we load only files that do not starts with `.` (dot) and have
// .php extension
$loader = new FilesLoader();
$config_items = $loader->load(__DIR__ . '/config');
// No we have all config items stored as array in $config_items

// In fact, Config is just tinny wrapper for array dot notation, so no magic here, just sugar
$config = new Config($config_items);

// Hey, do we have default database connection config section?

var_export($config->has('db.connections.default'));

// Output:
//true

echo PHP_EOL;
echo PHP_EOL;

var_export($config->get('db.connections.default'));
// Output:
//array(
//    'driver' => 'mysql',
//    'host' => 'localhost',
//    'port' => 3306,
//    'user' => 'guest',
//    'password' => 'secret',
//)

echo PHP_EOL;
echo PHP_EOL;

// If you want to get all items - just call `$config->all()`

// Let's update some values, say, change db connection host
$config->set('db.connections.default.host', 'remotehost');

var_export($config->get('db.connections.default'));
// Output:
//array(
//    'driver' => 'mysql',
//    'host' => 'remotehost',
//    'port' => 3306,
//    'user' => 'guest',
//    'password' => 'secret',
//)

echo PHP_EOL;
echo PHP_EOL;

// Sometimes we have default values for missed config items

// Let's update some values, say, change db connection host
var_export($config->get('db.connections.clustered', ['here will be dragons']));

// Output:
//array (
//    0 => 'here will be dragons',
//)

echo PHP_EOL;
echo PHP_EOL;