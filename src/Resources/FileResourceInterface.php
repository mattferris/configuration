<?php

/**
 * configuration - A configuration manager
 * github.com/mattferris/configuration
 *
 * Resources/FileResourceInterface.php
 * @copyright Copyright (c) 2016 Matt Ferris
 * @author Matt Ferris <matt@bueller.ca>
 *
 * Licensed under BSD 2-clause license
 * github.com/mattferris/configuration/blog/master/LICENSE
 */

namespace MattFerris\Configuration\Resources;

use MattFerris\Configuration\ResourceInterface;

interface FileResourceInterface extends ResourceInterface
{
    /**
     * Get the path to the file.
     *
     * @return string The path to the file
     */
    public function getPath();
}

