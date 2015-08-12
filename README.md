# Php Simple Config

[![Build Status](https://travis-ci.org/pinepain/php-simple-config.svg)](https://travis-ci.org/pinepain/php-simple-config)

# About

This is yet another library to deal with Config values. It designed to be obvious, minimal, lightweight, fast
and extensible.

This library combines approaches used by well-known and recognizable PHP (and not limited to) frameworks.

## Features:

 - obvious and simple
 - dealing with config best practices
 - default values for missed items support
 - flexibility (yeah, someone may say that it is so flexible that you have to give it a support)
 - minimal and extensible codebase
 - designed to have lose coupled code (at least wannabe)
 - speed (one of fastest from all what you've ever seen, marketing guys may add "close to bare metal", but that is not true as all we know)

## Installation:

`composer require pinepain/simple-config 0.*`

## Configs loading:

As this library tries to keep things simple, `Config` class requires only array of config items, so you may have any
configs loading logic you want and your application needs.

Right out of the box there are `FilesLoader` configs loade which simply `require`'s all files in single directory and
store returned items in config array under file's basename (file name without path to it and its extension) key.

Limitation:
 - Nested configs loading (that one that reside in nested directories) are not supported.
 - Dot-file (that one that starts with dot(`.`) character) and files that have different than `.php` extension are ignored.
 - Files with dots in base name (name without path and extension) are not supported (see note below why).

Assume, we have such directory tree:

```
example/config
├── .dotfile.php
├── app.php
├── cache.php
├── db.php
├── mail.php.old
└── somedir/
    └── somefile.php
```

then loading configs from `config` directory will load only `app.php`, `cache.php` and `db.php` files and __will ignore__
`.dotfile.php` and `mail.php.old` files and will skip `somedir/` content completely.

### Note:

Files with dots in it names (not counting dot between basename and extension) will leads to undiscoverable config
section, e.g. config file `db.old.php` will be interpreted as `[ 'db.old' => ... ]` entry which can't be accessed via
dot notation while we assume that each dot means one nested level.

While having such config section is ambiguous such naming is not supported by this library.  

## Example:

```php
    <?php
    
    require __DIR__ . '/../vendor/autoload.php';

    use Pinepain\SimpleConfig\Loaders\FilesLoader;
    use Pinepain\SimpleConfig\Config;

    $loader = new FilesLoader(__DIR__ . '/config');
    $config_items = $loader->load();
    
    $config = new Config($config_items);
    
    $config->has('some.value.you.are.looking.for');
    $config->get('some.value'); // will return all nested values under 'some.value' section
    $config->set('some.value', 'changed'); // change it
    // get item value or use default value if original item missed (and it is as we change it above)
    $config->get('some.value.you.are.looking.for', 'missed'); 
```
    
*Note: While this library is rather set of blocks it was built with mind and hope that you will use some IoC container,
but you can do all that manually.*


# Optimizations and caching

As this library tries to keep things simple, `Config` class requires only array of config items, so you may have any
configs loading logic you want and your application needs.

In addition to `FilesLoader` loader there are `FileCacheLoader` and `MergedFilesLoader` loaders which designed to optimize
config loading time by storing all config items in one file to reduce file operations. The main difference between them
is that `FileCacheLoader` loader store already calculated config items and `MergedFilesLoader` combine config files content
as is into one file. If you are in hurry - see example below to see the difference between them.

## Caching:
If you want to cache loaded config use `FileCacheLoader` which requires any config loader that implements `LoaderInterface`
interface and file path to store cached config items to. It has very simple logic: it looks for cached file and if it 
exists than retun it`requre`d result, if no cached file found, config items loaded with passed loader and then cached to
file and after that returned to user.

## Optimization:
Out of the box we have `FilesLoader` loader and `FileCacheLoader` caching loader which perfectly works together in most cases.
But if you follow [12-factor app methodology](http://12factor.net/) and [store config in the environment](http://12factor.net/config)
and what more, relies on them in your config files, you have to clean cache on every env variable change. In case doing
some dynamic calculus in config files you can't use caching at all.

Here `MergedFilesLoader` loader comes. It requires `FilesLoader` and file where to store merged config files to. 

The loader logic is to merge all config file into one under appropriate section so then only 1 file operation required, 
but actual code in configs are still executed on `require`.
 
`MergedFilesLoader` is limited to config files parsing and built with hope that it will not be used in situations where
configs used for programming, so it supposed that you have simple config with `return` statement that return config items
on `require`.

`MergedFilesLoader` built using [nikic/php-parser](https://github.com/nikic/PHP-Parser).

## Example:

Assume we have two file:

```php
    // app.php
    return [
        'env' => getenv('APP_ENV') ?: 'prod',
    ];
   
    // db.php
    return [
        'connections' => [
            'default' => [
                'driver'   => 'mysql',
                'host'     => getenv('DB_HOST') ?: 'localhost',
                'port'     => 3306,
                'user'     => getenv('DB_USER') ?: 'guest',
                'password' => getenv('DB_PASSWORD') ?: 'secret',
            ]
        ]
    ];
```

and assume we have not env variables set.

Caching wil lead to following content:
```php

    // app.php
    return [
        'app' => [
            'env' => 'prod',
        ],
        'db' => [
            'connections' => [
                'default' => [
                    'driver'   => 'mysql',
                    'host'     => 'localhost',
                    'port'     => 3306,
                    'user'     => 'guest',
                    'password' => 'secret',
                ]
            ]
        ]
    ];
```

Merging will leads to:
```php
    // app.php
    return [
        'app' [
            'env' => getenv('APP_ENV') ?: 'prod',
        ],
        'db' =>[
            'connections' => [
                'default' => [
                    'driver'   => 'mysql',
                    'host'     => getenv('DB_HOST') ?: 'localhost',
                    'port'     => 3306,
                    'user'     => getenv('DB_USER') ?: 'guest',
                    'password' => getenv('DB_PASSWORD') ?: 'secret',
                ]
            ]
        ]
    ];
```

If you change environment variables value and will not invalidate cache then you are likely to run into troubles.
With merging you are unlikely to run into such issue. Performance for both `FileCacheLoader` and `MergedFilesLoader` are
almost the same (run `php example/benchmark_loaders.php` to see exact results in your environment).

## Performance:

You can run benchmarking in your environment with `php example/benchmark_loaders.php` command. 

Here are common results on ssd drive:

```
   Running bench with 1 repeats
       Simple loading: 0.00033092498779297
       Merged loading: 0.026033163070679
       Cached loading: 0.00065708160400391
   
   Running bench with 100 repeats
       Simple loading: 0.030107975006104
       Merged loading: 0.015130996704102
       Cached loading: 0.0095160007476807
   
   Running bench with 1000 repeats
       Simple loading: 0.24485111236572
       Merged loading: 0.068234920501709
       Cached loading: 0.058425903320312
   
   Running bench with 10000 repeats
       Simple loading: 2.4374539852142
       Merged loading: 0.61219501495361
       Cached loading: 0.52797985076904
   
   Running bench with 100000 repeats
       Simple loading: 22.758585929871
       Merged loading: 5.8093478679657
       Cached loading: 5.6891360282898
```

This bench run at the end of every travis build, so you can check results by seeing
[builds result](https://travis-ci.org/pinepain/php-simple-config/builds).

## Where to go from here:

Use provided `ConfigInterface` to inject config dependency and use it in your projects. Feels free to report any
issues or suggest new Loaders or some new cool ideas to improve this library. 

### Config interface:
```php
    <?php
    
    namespace Pinepain\SimpleConfig;
    
    interface ConfigInterface
    {
        /**
         * @param array | \ArrayObject $items
         */
        public function __construct($items);

        /**
         * Get all of the configuration items.
         *
         * @return array
         */
        public function all();
    
        /**
         * Determine if the given configuration value exists.
         *
         * @param  string $key
         * @return bool
         */
        public function has($key);
    
        /**
         * Get the specified configuration value.
         *
         * @param  string $key
         * @param  mixed $default
         * @return mixed
         */
        public function get($key, $default = null);
    
        /**
         * Set a given configuration value.
         *
         * @param  string $key
         * @param  mixed $value
         * @return void
         */
        public function set($key, $value = null);
    }
```

### Configs loader interface:
```php
    <?php
    
    namespace Pinepain\SimpleConfig\Loaders;
    
    interface LoaderInterface
    {
        /**
         * Load config items
         *
         * @return array | \ArrayObject
         */
        public function load();
    }
```

# Notes

## Features that are not goal of this library:

 * Config items validation logic.
 * Dumping config items in fancy way with colors and formatting.

These features are not goals of this library and are subject of application- or framework-specific logic.
