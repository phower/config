<?php

/**
 * Phower Config
 *
 * @version 1.0.0
 * @link https://github.com/phower/config Public Git repository
 * @copyright (c) 2015-2016, Pedro Ferreira <https://phower.com>
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace Phower\Config\Exception;

use InvalidArgumentException;

/**
 * Invalid key type exception interface.
 *
 * @author Pedro Ferreira <pedro@phower.com>
 */
class InvalidKeyTypeException extends InvalidArgumentException implements ConfigExceptionInterface
{

    /**
     * Class constructor.
     *
     * @return void
     */
    public function __construct($key)
    {
        $type = is_object($key) ? get_class($key) : gettype($key);
        $message = sprintf('Config keys must be of type "string" or "integer"; "%s" was given.', $type);
        parent::__construct($message);
    }
}
