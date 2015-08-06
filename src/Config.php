<?php


namespace Pinepain\SimpleConfig;


class Config implements ConfigInterface
{
    private $items = [];

    public function __construct($items)
    {
        $this->items = $items;
    }

    /**
     * Get all of the configuration items.
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Determine if the given configuration value exists.
     *
     * @param  string $key
     * @return bool
     */
    public function has($key)
    {
        $items = $this->items;
        $keys = explode('.', $key);

        foreach ($keys as $key) {
            if (!is_array($items) || !array_key_exists($key, $items)) {
                return false;
            }

            $items = $items[$key];
        }

        return true;
    }

    /**
     * Get the specified configuration value.
     *
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $items = $this->items;
        $keys = explode('.', $key);

        foreach ($keys as $key) {
            if (!is_array($items) || !array_key_exists($key, $items)) {
                return $default;
            }

            $items = $items[$key];
        }

        return $items;
    }

    /**
     * Set a given configuration value.
     *
     * @param  string $key
     * @param  mixed $value
     */
    public function set($key, $value = null)
    {
        $items = &$this->items;
        $keys = explode('.', $key);

        foreach ($keys as $id => $key) {
            if (!is_array($items)) {
                $items = []; // yes, we will override scalar values
            }

            $items = &$items[$key];
        }

        $items = $value;
    }
}
