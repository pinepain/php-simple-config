<?php

require __DIR__ . '/../vendor/autoload.php';

use \Pinepain\SimpleConfig\Loaders\FilesLoader;
use \Pinepain\SimpleConfig\Loaders\FileCacheLoader;
use Pinepain\SimpleConfig\Config;


$loader        = new FilesLoader(__DIR__ . '/config');
$cached_loader = new FileCacheLoader($loader, __DIR__ . '/config_cached.php');

$config_items = $cached_loader->load();

$config = new Config($config_items);

// work with config as usual
