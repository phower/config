<?php

/**
 * Phower Config
 *
 * @link https://github.com/phower/config Public Git repository
 * @copyright (c) 2015-2016, Pedro Ferreira <https://phower.com>
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace Phower\Config\Exception;

use BadMethodCallException;

/**
 * Invalid key type exception interface.
 *
 * @author Pedro Ferreira <pedro@phower.com>
 */
class InvalidMethodNameException extends BadMethodCallException implements ConfigExceptionInterface
{

    /**
     * Class constructor.
     *
     * @return void
     */
    public function __construct($name)
    {
        $message = sprintf('Config can not resolve method "%s" to any valid method.', $name);
        parent::__construct($message);
    }
}
