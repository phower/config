<?php

/**
 * Phower Config
 *
 * @version 1.0.0
 * @link https://github.com/phower/config Public Git repository
 * @copyright (c) 2015-2016, Pedro Ferreira <https://phower.com>
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace Phower\Config;

/**
 * Config interface
 *
 * @author Pedro Ferreira <pedro@phower.com>
 */
interface ConfigInterface
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
     * Returns current options as an array.
     *
     * @return array
     */
    public function toArray();

    /**
     * Merges another Config instance into this one.
     *
     * @param \Phower\Config\ConfigInterface $config
     * @return \Phower\Config\ConfigInterface
     */
    public function merge(ConfigInterface $config);

    /**
     * Sets or checks weither instance is read-only
     *
     * @param bool|null $readOnly
     * @return \Phower\Config\ConfigInterface|bool
     */
    public function readOnly($readOnly = null);

    /**
     * Sets or checks weither instance allows override
     *
     * @param bool|null $allowOverride
     * @return \Phower\ConfigInterface\Config|bool
     */
    public function allowOverride($allowOverride = null);
}
