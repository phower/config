Phower Config
=============

Simplified methods to handle configuration data in PHP.

Requirements
------------

Phower Config requires:

-   [PHP 5.6](http://php.net/releases/5_6_0.php) or above; 
    version [7.0](http://php.net/releases/7_0_0.php) is recommended

Instalation
-----------

Add Phower Config to any PHP project using [Composer](https://getcomposer.org/):

```bash
composer require phower/config
```

Getting Started
---------------

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

### Include configuration

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

### Interface methods

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

### Normalized key names

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

> Note that normalized key names can ride to duplicates which should be avoided by
> using some sort of naming convention when Phower Config instances are created. E.g
> always use snake-case or camel case on array element keys.

### Read-only mode

By default instances are created read-only which means they can't be changed after creation.
However this behaviour can be changed in two ways:

```php
// 1. create instance with read-only mode set to false
$config = new Config(include('config.php'), false);
echo $config->readOnly(); // FALSE

// 2. optionally set read-only mode after creation
$config = new Config(include('config.php'));
echo $config->readOnly(); // TRUE

$config->readOnly(false);
echo $config->readOnly(); // FALSE
```

> Note the argument of `readOnly` method is optional. When omitted the method
> returns the state of read-only mode; otherwise it sets its state to TRUE or FALSE.

### Allow-override mode

Like in read-only mode it's also possible to control overrides in Phower Config instances.
Initially overrides are not allowed but this can be changed like previously:

```php
// 1. create instance with allow-override mode set to true
$config = new Config(include('config.php'), false, true);
echo $config->allowOverride(); // TRUE

// 2. optionally set allow-override mode after creation
$config = new Config(include('config.php'));
echo $config->allowOverride(); // FALSE

$config->allowOverride(true);
echo $config->allowOverride(); // TRUE
```

> Note the argument of `allowOverride` method is optional. When omitted the method
> returns the state of allow-override mode; otherwise it sets its state to TRUE or FALSE.

> Obviously overrides can only be done with read-only mode set to FALSE.

### Changing configuration

When required to change configuration after creation that can be done by setting or
removing keys:

```php
// create empty config instance with read-only set to FALSE
$config = new Config([], false);

// set some keys
$config->set('host', 'example.org')
       ->set('email', 'me@example.org')
       ->set('user_name', 'Pedro')
       ->set('password', 'Secre7!#@');

// remove one key
$config->remove('user_name');
```

Like in getting key values from configuration array access interface and magic methods are
also available:

```php
// setting keys
$config['host'] = 'example.org';
$config->host = 'example.org';
$config->setHost('example.org');

// removing keys
unset($config['host']);
unset($config->host);
$config->removeHost();
```

### Checking configuration

To check if a given key exists in configuration another method is available:

```php
// checking 'host' key
echo $config->has('host'); // returns TRUE if exists otherwise FALSE

// alternative interfaces
isset($config['host']);
isset($config->host);
$config->hasHost();
```

Advanced Usage
--------------

In some situations may be required to export a configuration instance to array or merge
another config object into the current instance. To provide these needs methods `toArray` 
and `merge` are available:

```php
// exporting configuration
$config = new Config($options);
$config->toArray(); // returns $options array

// merging configurations
$config1 = new Config($someOptions);
$config2 = new Config($otherOptions);

$config1->merge($config2); // $otherOptions are merged with $someOptions internally
```

Running Tests
-------------

Tests are available in a separated namespace and can run with [PHPUnit](http://phpunit.de/)
in the command line:

```bash
vendor/bin/phpunit
```

Coding Standards
----------------

Phower code is written under [PSR-2](http://www.php-fig.org/psr/psr-2/) coding style standard.
To enforce that [CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) tools are also 
provided and can run as:

```bash
vendor/bin/phpcs
```

Reporting Issues
----------------

In case you find issues with this code please open a ticket in Github Issues at
[https://github.com/phower/config/issues](https://github.com/phower/config/issues).

Contributors
------------

Open Source is made of contribuition. If you want to contribute to Phower please
follow these steps:

1.  Fork latest version into your own repository.
2.  Write your changes or additions and commit them.
3.  Follow PSR-2 coding style standard.
4.  Make sure you have unit tests with full coverage to your changes.
5.  Go to Github Pull Requests at [https://github.com/phower/config/pulls](https://github.com/phower/config/pulls)
    and create a new request.

Thank you!

Changes and Versioning
----------------------

All relevant changes on this code are logged in a separated [log](CHANGELOG.md) file.

Version numbers follow recommendations from [Semantic Versioning](http://semver.org/).

License
-------

Phower code is maintained under [The MIT License](https://opensource.org/licenses/MIT).