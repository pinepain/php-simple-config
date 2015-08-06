# Php Simple Config

[![Build Status](https://travis-ci.org/pinepain/php-simple-config.svg)](https://travis-ci.org/pinepain/php-simple-config)

# About:

This is yet another library to deal with Config values. It designed to minimal, lightweight, fast and extensible.
It has no external dependencies (not a great plus nowadays, but no reinvented wheels here).   

In this library I  combined approaches used by well-known php frameworks.

# Features:

 - best practices from dealing with config experience
 - default values for missed items support
 - flexibility (yeah, someone may say that it is so flexible that you have to give it a support)
 - minimal and extensible codebase
 - designed to be lose coupled code (at least wannabe)
 - speed (one of fastest from all what you've ever seen, marketing guys may add "close to bare metal", but that is not true as all we know)

# Installation:

`composer require pinepain/simple-config 0.*`

# Example usage:

```php
    <?php
    
    require __DIR__ . '/../vendor/autoload.php';
    
    $loader = new \Pinepain\SimpleConfig\Loaders\FilesLoader();
    $config_items = $loader->load(__DIR__ . '/config');
    
    $config = new \Pinepain\SimpleConfig\Config($config_items);
    
    $config->has('some.value.you.are.looking.for');
    $config->get('some.value'); // will return all nested values under 'some.value' section
    $config->set('some.value', 'changed'); // change it
    // get item value or use default value if original item missed (and it is as we change it above)
    $config->get('some.value.you.are.looking.for', 'missed'); 
```
    
*Note: While this library is rather set of blocks it was built with mind and hope that you will use some IoC container,
but you can do all that manually.*

# Where to go from here:

Use provided ConfigInterface to inject config dependency and use it in your projects. Feels free to report any
issues or suggest new Loaders or some new cool ideas to improve this library. 

# Config interface:
```php
    <?php
    
    namespace Pinepain\SimpleConfig;
    
    interface ConfigInterface
    {
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

# Notes:

## Features that are not goal of this library:

 * Config items validation logic.
 * Dumping config items in fancy way with colors and formatting.

These features are not goals of this library and are subject of application- or framework-specific logic.

## Caching

Caching config items is also missed as application and framework-specific, though, it is welcomed to provide loader
or implementation that deals with caching.
