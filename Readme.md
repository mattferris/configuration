Configuration
=============

[![Build Status](https://travis-ci.org/mattferris/configuration.svg?branch=master)](https://travis-ci.org/mattferris/configuration)

A configuration management library.

```
composer require mattferris/configuration
```

Configuration helps you load and manage runtime configuration. It's flexible
enough to let you load any type of configuration resource you want, but not so
complex as to be unwieldly for smaller projects.

```php
use MattFerris\Configuration\Configuration;
use MattFerris\Configuration\Locators\FileLocator;
use MattFerris\Configuration\Loader\PhpLoader;

// setup a file locator with one or more search paths
$locator = new FileLocator(['foo/path', 'bar/path']);

// setup a loader for the type of files you want to  load
$loader = new PhpLoader();

$config = new Configuration($locator, $loader);

// load a config file, the locator will search for the file using the search paths
$config->load('config.php');

// get a configuration value
$config->get('password');

// get a nested configuration value
$config->get('db.host');

// dump the configuration
$values = $config->get();

/*
 * $values contains:
 *
 * [
 *     'password' => 'banana',
 *     'db' => [
 *         'host' => 'localhost'
 *     ]
 * ]
 */
```

In the above example, `config.php` would look like;

```php
<?php

// php config files must store all configuration in the $config variable
$config = [
    'password' => 'banana',
    'db' => [
        'host' => 'localhost'
    ]
];
```

You can test if a key exists using `Configuration::has()`.

```php
if ($config->has('foo')) {
    // do stuff
}
```

It's good to use `has()` if you're unsure a key exists. Calls to `get()` for
non-existent keys will throw a `KeyDoesNotExistException`.

It's also possible to import data from other other instances of
`ConfigurationInterface`.

```php
$config->import($moduleConfig);
```

Lastly, both `load()` and `import()` accept a key as a second parameter under
which to load or import data.

```php
$config->load('database.php', 'db');
$config->import($moduleConfig, 'module');
```

