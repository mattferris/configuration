<?php

/**
 * configuration - A configuration manager
 * github.com/mattferris/configuration
 *
 * LoaderInterface.php
 * @copyright Copyright (c) 2016 Matt Ferris
 * @author Matt Ferris <matt@bueller.ca>
 *
 * Licensed under BSD 2-clause license
 * github.com/mattferris/configuration/blog/master/LICENSE
 */

namespace MattFerris\Configuration;

interface LoaderInterface
{
    /**
     * Load a resource, returning an array of data.
     *
     * @param ResourceInterface $resource The resource to load
     * @return array The data loaded by the resource
     */
    public function load(ResourceInterface $resource);
}

