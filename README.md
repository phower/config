Phower Config
=============

Implementation of simplified methods to handle configuration data.

Instalation
-----------

Add Phower Config to your [Composer](https://getcomposer.org) project:

    composer require phower/config

Basic Usage
-----------

Phower Config wraps an associative array as configuration data to be handled over 
any PHP application.

```php
<?php

use Phower\Config\Config;

// array of options
$options = [
    'host' => 'example.org',
    'email' => 'me@example.org',
    'password' => 'Secre7!#@',
];

// create config instance
$config = new Config($options);

// access a configuration key
$email = $config->get('email'); // 'me@example.org'
```