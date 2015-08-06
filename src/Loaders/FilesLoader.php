<?php


namespace Pinepain\SimpleConfig\Loaders;

use DirectoryIterator;

class FilesLoader
{
    /**
     * @param string $directory
     * @return array
     */
    public function load($directory)
    {
        $loaded = [];

        $iterator = new DirectoryIterator($directory);

        foreach ($iterator as $file_info) {
            if ($file_info->getBasename()[0] === '.' || $file_info->isDir() || $file_info->getExtension() !== 'php') {
                continue;
            }

            $loaded[$file_info->getBasename('.php')] = require $file_info->getPathname();
        }

        return $loaded;
    }

    public function load2($directory)
    {
        $loaded = [];

        foreach ($this->getConfigFiles($directory) as $basename => $file) {
            $loaded[$basename] = require $file;
        }

        return $loaded;
    }

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
