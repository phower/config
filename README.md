Phower Config
=============

Implementation of simplified methods to handle configuration data.

Instalation
-----------

Add Phower Config to any [PHP](http://php.net/) project using 
[Composer](https://getcomposer.org/):

    composer require phower/config

Basic Usage
-----------

An associative array can be used as configuration data to be handled on
any application.

```php
// index.php
require('path/to/vendor/autoload.php');

use Phower\Config\Config;

// array of options
$options = [
    'host' => 'example.org',
    'email' => 'me@example.org',
    'user_name' => 'Pedro',
    'password' => 'Secre7!#@',
];

// create config instance
$config = new Config($options);

// access a configuration key
echo $config->get('email'); // 'me@example.org'
```

Optionally configuration data can be read from a plain PHP script
returning an array:

```php
// config.php
return [
    'host' => 'example.org',
    'email' => 'me@example.org',
    'password' => 'Secre7!#@',
];

// index.php
$config = new Config(include('config.php'));
```

Because we implemented [ArrayAccess Interface](http://php.net/manual/en/class.arrayaccess.php) 
and [Magic Methods](http://php.net/manual/en/language.oop5.magic.php) it's also possible 
to access configuration data in different styles:

```php
// equivalent methods
echo $config->get('email'); // 'me@example.org'
echo $config['email'];      // 'me@example.org'
echo $config->email;        // 'me@example.org'
echo $config->getEmail();   // 'me@example.org'
```

Since key names are normalized internally it's possible to relax on naming
conventions:

```php
// always refer same key
echo $config->get('user_name'); // 'Pedro'
echo $config->get('userName');  // 'Pedro'
echo $config->get('USER-NAME'); // 'Pedro'
echo $config->get('username');  // 'Pedro'

// same applies when using different methods of example above
```
