<?php

/**
 * Phower Config
 *
 * @link https://github.com/phower/config Public Git repository
 * @copyright (c) 2015-2016, Pedro Ferreira <https://phower.com>
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace Phower\Config\Exception;

use RuntimeException;

/**
 * Override exception interface.
 *
 * @author Pedro Ferreira <pedro@phower.com>
 */
class OverrideException extends RuntimeException implements ConfigExceptionInterface
{

    /**
     * Class constructor.
     *
     * @return void
     */
    public function __construct($key)
    {
        $message = sprintf('Config instance does not allow overrides and key "%s" already exists.', $key);
        parent::__construct($message);
    }
}
