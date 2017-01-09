<?php

/**
 * configuration - A configuration manager
 * github.com/mattferris/configuration
 *
 * KeyDoesNotExistException.php
 * @copyright Copyright (c) 2016 Matt Ferris
 * @author Matt Ferris <matt@bueller.ca>
 *
 * Licensed under BSD 2-clause license
 * github.com/mattferris/configuration/blog/master/LICENSE
 */

namespace MattFerris\Configuration;

use \Exception;

class KeyDoesNotExistException extends Exception
{
    /**
     * @var string The key that doesn't exist
     */
    protected $key;

    /**
     * @param string $key The key that doesn't exist
     */
    public function __construct($key)
    {
        $this->key = $key;
        $msg = 'key "'.$key.'" does not exist';
        parent::__construct($msg);
    }

    /**
     * Return the key that doesn't exist.
     *
     * @return string The key that doesn't exist
     */
    public function getKey()
    {
        return $this->key;
    }
}

