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
     *
     * @return bool
     */
    public function has($key);

    /**
     * Get the specified configuration value.
     *
     * @param  string $key
     * @param  mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Set a given configuration value.
     *
     * @param  string $key
     * @param  mixed  $value
     *
     * @return void
     */
    public function set($key, $value = null);
}
