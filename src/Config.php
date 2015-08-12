<?php


namespace Pinepain\SimpleConfig;


class Config implements ConfigInterface
{
    private $items = [];

    /**
     * {@inheritdoc}
     */
    public function __construct($items)
    {
        $this->items = $items;
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
