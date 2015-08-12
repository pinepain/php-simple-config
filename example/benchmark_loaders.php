<?php

require __DIR__ . '/../vendor/autoload.php';

use \Pinepain\SimpleConfig\Loaders\FilesLoader;
use Pinepain\SimpleConfig\Config;

// it's good idea to use some dotenv library here and load env-specific env variables to use them later
// in configs

// Ok, let's load our config. Note, that we load only files that do not starts with `.` (dot) and have
// .php extension


$repeats = 1;
function bench($repeats)
{
    echo 'Running bench with ', $repeats, ' repeats', PHP_EOL;

    $configs_dir = __DIR__ . '/config/';
    $merged_file = __DIR__ . '/config_merged.php';
    $cached_file = __DIR__ . '/config_cached.php';

    $simple_loader = new FilesLoader($configs_dir);
    $merged_loader = new \Pinepain\SimpleConfig\Loaders\MergedFilesLoader($simple_loader, $merged_file);
    $cached_loader = new \Pinepain\SimpleConfig\Loaders\FileCacheLoader($simple_loader, $cached_file);

    $t = microtime(true);
    for ($i = 0; $i < $repeats; $i++) {
        $simple_loader->load();
    }
    echo '    Simple loading: ', microtime(true) - $t, PHP_EOL;

    $t = microtime(true);
    for ($i = 0; $i < $repeats; $i++) {
        $merged_loader->load();
    }
    echo '    Merged loading: ', microtime(true) - $t, PHP_EOL;


    $t = microtime(true);
    for ($i = 0; $i < $repeats; $i++) {
        $cached_loader->load();
    }
    echo '    Cached loading: ', microtime(true) - $t, PHP_EOL;

    echo PHP_EOL;
}

function cleanup()
{
    $merged_file = __DIR__ . '/config_merged.php';
    $cached_file = __DIR__ . '/config_cached.php';

    if (file_exists($merged_file)) {
        unlink($merged_file);
    }

    if (file_exists($cached_file)) {
        unlink($cached_file);
    }
}

cleanup();
bench(1);

cleanup();
bench(100);
cleanup();
bench(1000);
cleanup();
bench(10000);
cleanup();
bench(100000);
