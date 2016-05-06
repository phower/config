<?php

/**
 * Phower Config
 *
 * @link https://github.com/phower/config Public Git repository
 * @copyright (c) 2015-2016, Pedro Ferreira <https://phower.com>
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace Phower\Config;

use ArrayAccess;
use Countable;
use Iterator;

/**
 * Config interface
 *
 * @author Pedro Ferreira <pedro@phower.com>
 */
interface ConfigInterface extends ArrayAccess, Countable, Iterator
{

    /**
     * Checks weither a key exists.
     *
     * @param string|int $key
     * @return bool
     */
    public function has($key);

    /**
     * Sets value for a given key.
     *
     * @param string|int|null $key
     * @param mixed $value
     * @return \Phower\Config\ConfigInterface
     * @throws Exception\ReadOnlyException
     * @throws Exception\OverrideException
     * @throws Exception\InvalidKeyTypeException
     */
    public function set($key, $value);

    /**
     * Gets value of a given existing key; otherwise returns provided default value.
     *
     * @param string|int $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Removes an existing key.
     *
     * @param string|int $key
     * @return \Phower\Config\ConfigInterface
     * @throws Exception\InvalidKeyTypeException
     */
    public function remove($key);

    /**
     * Merges another Config instance into this one.
     *
     * @param \Phower\Config\ConfigInterface $config
     * @return \Phower\Config\ConfigInterface
     */
    public function merge(ConfigInterface $config);

    /**
     * Returns current options as an array.
     *
     * @return array
     */
    public function toArray();

    /**
     * Checks weither the config instance is read-only.
     *
     * @return bool
     */
    public function isReadOnly();

    /**
     * Set config instance read-only.
     *
     * @param bool $readOnly
     * @return \Phower\Config\ConfigInterface
     */
    public function setReadOnly($readOnly);

    /**
     * Checks weither the config instance allows overrides.
     *
     * @return bool
     */
    public function allowOverride();

    /**
     * Set config instance to allow override.
     *
     * @param bool $allowOverride
     * @return \Phower\Config\ConfigInterface
     */
    public function setAllowOverride($allowOverride);
}
