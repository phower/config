<?php

/**
 * Phower Config
 *
 * @link https://github.com/phower/config Public Git repository
 * @copyright (c) 2015-2016, Pedro Ferreira <https://phower.com>
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace Phower\Config;

/**
 * Base implementation of Config interface.
 *
 * @author Pedro Ferreira <pedro@phower.com>
 */
class Config implements ConfigInterface
{

    /**
     * @var bool
     */
    protected $readOnly = false;

    /**
     * @var bool
     */
    protected $allowOverride = false;

    /**
     * @var array
     */
    protected $keys = [];

    /**
     * @var array
     */
    protected $values = [];

    /**
     * Class constructor
     *
     * @param array $options
     * @param bool $readOnly
     * @param bool $allowOverride
     */
    public function __construct(array $options = [], $readOnly = true, $allowOverride = false)
    {
        foreach ($options as $key => $value) {
            $this->set($key, $value);
        }

        $this->readOnly = (bool) $readOnly;
        $this->allowOverride = (bool) $allowOverride;
    }

    /**
     * Checks weither a key exists.
     *
     * @param string|int $key
     * @return bool
     * @throws Exception\InvalidKeyTypeException
     */
    public function has($key)
    {
        if (!is_string($key) && !is_int($key)) {
            throw new Exception\InvalidKeyTypeException($key);
        }

        $normalized = $this->normalize($key);
        return isset($this->keys[$normalized]);
    }

    /**
     * Sets value for a given key.
     *
     * @param string|int|null $key
     * @param mixed $value
     * @return \Phower\Config\Config
     * @throws Exception\ReadOnlyException
     * @throws Exception\OverrideException
     * @throws Exception\InvalidKeyTypeException
     */
    public function set($key, $value)
    {
        if (!is_string($key) && !is_int($key) && !is_null($key)) {
            throw new Exception\InvalidKeyTypeException($key);
        }

        if ($this->readOnly) {
            throw new Exception\ReadOnlyException();
        }

        if (is_null($key)) {
            $this->keys[] = null;
            $normalized = $key = key($this->keys);
        } else {
            $normalized = $this->normalize($key);

            if (!$this->allowOverride && isset($this->keys[$normalized])) {
                throw new Exception\OverrideException($key);
            }
        }

        if (is_array($value)) {
            $value = new Config($value, $this->readOnly, $this->allowOverride);
        }

        $this->keys[$normalized] = $key;
        $this->values[$normalized] = $value;

        return $this;
    }

    /**
     * Gets value of a given existing key; otherwise returns provided default value.
     *
     * @param string|int $key
     * @param mixed $default
     * @return mixed
     * @throws Exception\InvalidKeyTypeException
     */
    public function get($key, $default = null)
    {
        if (!is_string($key) && !is_int($key)) {
            throw new Exception\InvalidKeyTypeException($key);
        }

        $normalized = $this->normalize($key);

        if (!isset($this->keys[$normalized])) {
            return $default;
        }

        return $this->values[$normalized];
    }

    /**
     * Removes an existing key.
     *
     * @param string|int $key
     * @return \Phower\Config\Config
     * @throws Exception\InvalidKeyTypeException
     */
    public function remove($key)
    {
        if (!is_string($key) && !is_int($key)) {
            throw new Exception\InvalidKeyTypeException($key);
        }

        if ($this->readOnly) {
            throw new Exception\ReadOnlyException();
        }

        $normalized = $this->normalize($key);

        if (isset($this->keys[$normalized])) {
            unset($this->keys[$normalized]);
            unset($this->values[$normalized]);
        }

        return $this;
    }

    /**
     * Returns current options as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $options = [];

        foreach ($this->keys as $normalized => $key) {
            if ($this->values[$normalized] instanceof ConfigInterface) {
                $options[$key] = $this->values[$normalized]->toArray();
            } else {
                $options[$key] = $this->values[$normalized];
            }
        }

        return $options;
    }

    /**
     * Merges another Config instance into this one.
     *
     * @param \Phower\Config\ConfigInterface $config
     * @return \Phower\Config\Config
     */
    public function merge(ConfigInterface $config)
    {
        foreach ($config->toArray() as $key => $value) {
            if (is_int($key)) {
                $key = null;
            }

            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * Checks weither the config instance is read-only.
     *
     * @return bool
     */
    public function isReadOnly()
    {
        return $this->readOnly;
    }

    /**
     * Set config instance read-only.
     *
     * @param bool $readOnly
     * @return \Phower\Config\Config
     */
    public function setReadOnly($readOnly)
    {
        $this->readOnly = (bool) $readOnly;
        return $this;
    }

    /**
     * Checks weither the config instance allows overrides.
     *
     * @return bool
     */
    public function allowOverride()
    {
        return $this->allowOverride;
    }

    /**
     * Set config instance to allow override.
     *
     * @param bool $allowOverride
     * @return \Phower\Config\Config
     */
    public function setAllowOverride($allowOverride)
    {
        $this->allowOverride = (bool) $allowOverride;
        return $this;
    }

    /**
     * Normalize key as it should only contain letters and numbers.
     *
     * @param string|int|null $key
     * @return string|int|null
     */
    protected function normalize($key)
    {
        if (is_int($key) || is_null($key)) {
            return $key;
        }

        return trim(strtolower(preg_replace("/[^a-zA-Z0-9]+/", '', $key)));
    }

    /**
     * Checks whether an offset exists.
     *
     * @param string|int $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Returns value for a given offset or null.
     *
     * @param string|int $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Sets the value for a given offset.
     *
     * @param string|int $offset
     * @param mixed $value
     * @return \Phower\Config\Config
     */
    public function offsetSet($offset, $value)
    {
        return $this->set($offset, $value);
    }

    /**
     * Removes an existing offset.
     *
     * @param type $offset
     * @return \Phower\Config\Config
     */
    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }

    /**
     * Counts the number of options on this instance.
     *
     * @return int
     */
    public function count()
    {
        return count($this->keys);
    }

    /**
     * Returns the current value.
     *
     * @return mixed
     */
    public function current()
    {
        $key = key($this->keys);
        return $this->values[$key];
    }

    /**
     * Moves forward to next element.
     *
     * @return void
     */
    public function next()
    {
        next($this->keys);
    }

    /**
     * Returns current key.
     *
     * @return string|int
     */
    public function key()
    {
        return current($this->keys);
    }

    /**
     * Checks if current position is valid.
     *
     * @return bool
     */
    public function valid()
    {
        return key($this->keys) !== null;
    }

    /**
     * Rewinds the Iterator to the first element.
     *
     * @return void
     */
    public function rewind()
    {
        reset($this->keys);
    }
}
