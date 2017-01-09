<?php

/**
 * configuration - A configuration manager
 * github.com/mattferris/configuration
 *
 * Loaders/YamlLoader.php
 * @copyright Copyright (c) 2016 Matt Ferris
 * @author Matt Ferris <matt@bueller.ca>
 *
 * Licensed under BSD 2-clause license
 * github.com/mattferris/configuration/blog/master/LICENSE
 */

namespace MattFerris\Configuration\Loaders;

use MattFerris\Configuration\LoaderInterface;
use MattFerris\Configuration\ResourceInterface;
use Symfony\Component\Yaml\Yaml;

class YamlLoader implements Loaderinterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ResourceInterface $resource)
    {
        return Yaml::parse(file_get_contents($resource->getPath()));
    }
}

