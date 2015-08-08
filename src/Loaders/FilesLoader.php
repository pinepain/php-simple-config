<?php


namespace Pinepain\SimpleConfig\Loaders;

use DirectoryIterator;

class FilesLoader implements LoaderInterface
{
    private $directory;

    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $loaded = [];

        foreach ($this->getConfigFiles($this->directory) as $basename => $file) {
            $loaded[$basename] = require $file;
        }

        return $loaded;
    }

    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Get config sections and correspondent files path
     *
     * @param $directory
     *
     * @return array
     */
    public function getConfigFiles($directory)
    {
        $iterator = new DirectoryIterator($directory);

        $files = [];

        foreach ($iterator as $file_info) {
            if ($file_info->getBasename()[0] === '.' || $file_info->isDir() || $file_info->getExtension() !== 'php') {
                continue;
            }

            $files[$file_info->getBasename('.php')] = $file_info->getPathname();
        }

        return $files;
    }
}
