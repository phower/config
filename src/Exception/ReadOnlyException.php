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
 * Read-only exception interface.
 *
 * @author Pedro Ferreira <pedro@phower.com>
 */
class ReadOnlyException extends RuntimeException implements ConfigExceptionInterface
{

    /**
     * Class constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $message = 'Config instance is read-only and can\'t be modified.';
        parent::__construct($message);
    }
}
