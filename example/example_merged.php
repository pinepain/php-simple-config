<?php

require __DIR__ . '/../vendor/autoload.php';

use \Pinepain\SimpleConfig\Loaders\FilesLoader;
use \Pinepain\SimpleConfig\Loaders\MergedFilesLoader;
use Pinepain\SimpleConfig\Config;


$loader        = new FilesLoader(__DIR__ . '/config');
$cached_loader = new MergedFilesLoader($loader, __DIR__ . '/config_merged.php');

$config_items = $cached_loader->load();

$config = new Config($config_items);

// work with config as usual
