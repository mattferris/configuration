<?php

/**
 * configuration - A configuration manager
 * github.com/mattferris/configuration
 *
 * Resources/FileResource.php
 * @copyright Copyright (c) 2016 Matt Ferris
 * @author Matt Ferris <matt@bueller.ca>
 *
 * Licensed under BSD 2-clause license
 * github.com/mattferris/configuration/blog/master/LICENSE
 */

namespace MattFerris\Configuration\Resources;

use MattFerris\Configuration\ResourceInterface;
use InvalidArgumentException;

class FileResource implements ResourceInterface
{
    /**
     * @var string The path to the file
     */
    protected $path;

    /**
     * @param string $path The path to the file
     * @throws \InvalidArgumentException
     */
    public function __construct($path)
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException('path "'.$path.'" does not exist');
        }
        $this->path = $path;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath()
    {
        return $this->path;
    }
}

