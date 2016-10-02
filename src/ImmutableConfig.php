<?php


namespace Pinepain\SimpleConfig;


class ImmutableConfig implements ConfigInterface
{
    private $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function all()
    {
        return $this->config->all();
    }

    public function has($key)
    {
        return $this->config->has($key);
    }

    public function get($key, $default = null)
    {
        return $this->config->get($key, $default);
    }

    public function set($key, $value = null)
    {
        throw new \RuntimeException("Can't set key '{$key}' on immutable config");
    }
}