<?php

/**
 * configuration - A configuration manager
 * github.com/mattferris/configuration
 *
 * ConfigurationInterface.php
 * @copyright Copyright (c) 2016 Matt Ferris
 * @author Matt Ferris <matt@bueller.ca>
 *
 * Licensed under BSD 2-clause license
 * github.com/mattferris/configuration/blog/master/LICENSE
 */

namespace MattFerris\Configuration;

interface ConfigurationInterface
{
    /**
     * Load a resource. Multiple alternative resources can be specified. If a
     * resource can't be loaded, then the next resource is in the list if tried.
     * Resource configuration is merged with any existing configuration, where
     * any duplicates are encountered, they are overwritten.
     *
     * @param mixed $resources A single resource, or list of resources to load
     * @return self
     * @throws \InvalidArgumentException If an invalid resource is specified
     * @throws ResourceNotFoundException If the resource(s) can't be located
     */
    public function load($resources, $key = null);

    /**
     * Check if a key exists
     *
     * @param string $key The key to check
     * @return bool
     */
    public function has($key);

    /**
     * Get the value of $key, or if no key specified, all values.
     *
     * @param string $key The key to get
     * @return mixed The value of the key
     */
    public function get($key = null);

    /**
     * Import configuration from another instance, optionally into a specify key.
     *
     * @param ConfigurationInterface $importer The instance to import from
     * @param string $key An optional key to import to
     */
    public function import(ConfigurationInterface $importer, $key = null);

    /**
     * Get a new empty instance of the current configuration object.
     *
     * @return ConfigurationInterface The new instance
     */
    public function newInstance();
}

