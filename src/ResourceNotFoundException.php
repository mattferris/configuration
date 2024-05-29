<?php

/**
 * configuration - A configuration manager
 * github.com/mattferris/configuration
 *
 * ResourceNotFoundException.php
 * @copyright Copyright (c) 2016 Matt Ferris
 * @author Matt Ferris <matt@bueller.ca>
 *
 * Licensed under BSD 2-clause license
 * github.com/mattferris/configuration/blog/master/LICENSE
 */

namespace MattFerris\Configuration;

use \Exception;

class ResourceNotFoundException extends Exception
{
    /**
     * @var array The resources that weren't found
     */
    protected $resources;

    /**
     * @param array $resources The resources that weren't found
     */
    public function __construct($resources)
    {
        $this->resources = $resources;
        $msg = 'resource(s) "'.implode(', ', $resources).'" could not be found';
        parent::__construct($msg);
    }

    /**
     * Return the resources that weren't found.
     *
     * @return array The resourcse that weren't found
     */
    public function getResources()
    {
        return $this->resources;
    }
}

