<?php

/**
 * configuration - A configuration manager
 * github.com/mattferris/configuration
 *
 * LocatorInterface.php
 * @copyright Copyright (c) 2016 Matt Ferris
 * @author Matt Ferris <matt@bueller.ca>
 *
 * Licensed under BSD 2-clause license
 * github.com/mattferris/configuration/blog/master/LICENSE
 */

namespace MattFerris\Configuration;

interface LocatorInterface
{
    /**
     * Locate a resource and return an instace of ResourceInterface.
     *
     * @param string $resource The resource to locate
     * @return ResourceInterface|false An instance of the resource, or false
     * @throws \InvalidArgumentException If $resource is invalid
     */
    public function locate($resource);
}

