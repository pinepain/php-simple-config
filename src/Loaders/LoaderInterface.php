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
