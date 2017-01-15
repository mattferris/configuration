<?php

/**
 * configuration - A configuration manager
 * github.com/mattferris/configuration
 *
 * Locators/FileLocator.php
 * @copyright Copyright (c) 2016 Matt Ferris
 * @author Matt Ferris <matt@bueller.ca>
 *
 * Licensed under BSD 2-clause license
 * github.com/mattferris/configuration/blog/master/LICENSE
 */

namespace MattFerris\Configuration\Locators;

use MattFerris\Configuration\LocatorInterface;
use MattFerris\Configuration\Resources\FileResource;
use \InvalidArgumentException;

class FileLocator implements LocatorInterface
{
    /**
     * @var array Directories in which to look for files
     */
    protected $directories = [];

    /**
     * @param array $directories Directories in which to look for files
     * @throws \InvalidArgumentException If a directory is invalid
     */
    public function __construct(array $directories)
    {
        foreach ($directories as $directory) {
            $directory = rtrim($directory, '/\\');
        }
        $this->directories = $directories;
    }

    /**
     * {@inheritDoc}
     */
    public function locate($resource)
    {
        if (!is_string($resource) || empty($resource)) {
            throw new InvalidArgumentException('resource must be a non-empty string');
        }

        $path = null;
        foreach ($this->directories as $dir) {
            if (file_exists($dir.DIRECTORY_SEPARATOR.$resource)) {
                $path = $dir.DIRECTORY_SEPARATOR.$resource;
                break;
            }
        }

        $instance = false;
        if (!is_null($path)) {
            $instance = new FileResource($path);
        }

        return $instance;
    }
}

