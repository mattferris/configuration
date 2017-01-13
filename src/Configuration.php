<?php

/**
 * configuration - A configuration manager
 * github.com/mattferris/configuration
 *
 * Configuration.php
 * @copyright Copyright (c) 2016 Matt Ferris
 * @author Matt Ferris <matt@bueller.ca>
 *
 * Licensed under BSD 2-clause license
 * github.com/mattferris/configuration/blog/master/LICENSE
 */

namespace MattFerris\Configuration;

use InvalidArgumentException;

class Configuration implements ConfigurationInterface
{
    /**
     * @var array The configuration key/values
     */
    protected $config = [];

    /**
     * @var array Resource locator
     */
    protected $locator;

    /**
     * @var array Resource loader
     */
    protected $loader;

    /**
     * @param LocatorInterface $locator Resource locator
     * @param LoaderInterface $resolver Resource loader
     */
    public function __construct(LocatorInterface $locator, LoaderInterface $loader)
    {
        $this->locator = $locator;
        $this->loader = $loader;
    }

    /**
     * {@inheritDoc}
     */
    public function load($resources, $key = null)
    {
        if (is_string($resources)) {
            $resources = [$resources];
        }

        if (!is_array($resources)) {
            throw new InvalidArgumentException('invalid resource(s) specified');
        }

        // attempt to locate the resources
        $resource = null;
        foreach ($resources as $r) {
            if (($result = $this->locator->locate($r)) !== false) {
                $resource = $result;
                break;
            }
        }

        if ($resource === null) {
            throw new ResourceNotFoundException($resources);
        }

        // create key if it doesn't exist
        if (!is_null($key) && !$this->has($key)) {
            if (strpos($key, '.') === false) {
                $this->config[$key] = [];
            } else {
                list($parent, $child) = $this->getParentChildKeys($key);
                $config = $this->resolveKey($parent);
                $config[$child] = [];
            }
        }

        // parse the resource and merge the result
        $result = $this->loader->load($resource);
        $this->merge($key, $result);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function has($key)
    {
        try {
            $this->resolveKey($key);
            return true;
        } catch (KeyDoesNotExistException $e) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function get($key = null)
    {
        if (is_null($key)) {
            return $this->config;
        } else {
            return $this->resolveKey($key);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function import(ConfigurationInterface $importer, $key = null)
    {
        // create key if it doesn't exist
        if (!is_null($key) && !$this->has($key)) {
            if (strpos($key, '.') === false) {
                $this->config[$key] = [];
            } else {
                list($parent, $child) = $this->getParentChildKeys($key);
                $config = $this->resolveKey($parent);
                $config[$child] = [];
            }
        }

        $this->merge($key, $importer->get());
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function newInstance()
    {
        $new = clone $this;
        $this->config = [];
        return $new;
    }

    /**
     * Get the locator used by the instance.
     *
     * @return LocatorInterface The loader used by the instance
     */
    public function getLocator()
    {
        return $this->locator;
    }

    /**
     * Resolve a key to it's value.
     *
     * @param string $key The key to resolve
     * @return A reference to the key's value
     * @throws KeyDoesNotExistException If the key doesn't exist
     */
    protected function &resolveKey($key)
    {
        // check if the key is a compound key
        if (strpos($key, '.') !== false) {

            // key is compound, so split it into parts
            $parts = explode('.', $key);
            $config = &$this->config;

            // recurse into the config array using the key parts
            while (is_array($config) && ($part = array_shift($parts)) !== null) {
                if (!isset($config[$part]) && array_key_exists($part, $config)) {
                    throw new KeyDoesNotExistException($key);
                }
                $config = &$config[$part];
            }

            // if there are still key parts left, then the key doesn't exist
            if (count($parts) > 0) {
                throw new KeyDoesNotExistException($key);
            }

        } else {

            // key is not compound, so just check if it exists and get value
            if (!isset($this->config[$key]) && !array_key_exists($key, $this->config)) {
                throw new KeyDoesNotExistException($key);
            }
            $config = &$this->config[$key];

        }

        return $config;
    }

    /**
     * Get the parent key, and the child part of the key.
     *
     * @param string $key The key to get the parent and child part from
     * @return array The parent and child part
     */
    protected function getParentChildKeys($key)
    {
        $parts = explode('.', $key);
        $child = array_pop($parts);
        return [implode('.', $parts), $child];
    }

    /**
     * Merge values into a key.
     *
     * @param string $key The key to merge into
     * @param mixed $result The data to merge
     * @throws KeyDoesNotExistException If the key doesn't exist
     */
    protected function merge($key, $result)
    {
        if (is_null($key)) {

            // key is null, so just merge with root
            $this->config = array_merge($this->config, $result);

        } elseif (strpos($key, '.') === false) {

            // non-compound key, just merge key with incoming data
            if (!isset($this->config[$key]) && !array_key_exists($key, $this->config)) {
                throw new KeyDoesNotExistException($key);
            }

            // if $this->config[$key] is not an array, we convert it one before merging
            if (!is_array($this->config[$key])) {
                $this->config[$key] = [];
            }

            $this->config[$key] = array_merge($this->config[$key], $result);

        } else {

            // compound key, so merge with parent reference
            list($parent, $child) = $this->getParentChildKeys($key);
            $config = $this->resolveKey($parent);
            $config[$child] = array_merge($config[$child], $result);

        }
    }
}

