<?php declare(strict_types=1);

namespace DiePHP;

use ArrayAccess;
use ArrayObject;
use Closure;
use JsonSerializable;
use LogicException;

/**
 * Class DataObject
 * This class represents a data object that can be initialized with an associative array and provides various methods
 * to manipulate and access the data. Implements \JsonSerializable and ArrayAccess interfaces.
 */
class DataObject implements JsonSerializable, ArrayAccess
{

    const STRICT        = true;

    const SOFT          = false;

    const MAP_USE_VALUE = 1;

    const MAP_USE_BOTH  = 2;

    private array $container = [];

    /**
     * Class constructor.
     * @param array|object|\DiePHP\DataObject|\ArrayAccess $assocArray Optional. An associative array to initialize the
     *                                                                 object. Default is an empty array.
     */
    function __construct($assocArray = [])
    {
        $this->container = $this->convertToArray($assocArray);

        if ($this->container && (array_keys($this->container) === range(0, count($this->container) - 1))) {
            throw new LogicException("DataObject should be associative");
        }
    }

    /**
     * Convert the given data to an array.
     * @param mixed $data The data to be converted.
     * @return array The converted data as an array.
     */
    private function convertToArray($data) : array
    {
        if ($data instanceof DataObject) {
            return $data->toArray();
        } else if (is_object($data) && method_exists($data, 'toArray')) {
            return $data->toArray();
        } else if ($data instanceof ArrayObject) {
            return [...$data->getArrayCopy()];
        } else if (is_null($data)) {
            return [];
        }

        return (array) $data;
    }

    /**
     * Convert the object to an array.
     * @return array The converted array.
     */
    public function toArray() : array
    {
        $result = [];
        foreach ($this->container as $k => $v) {
            if (is_object($v) && method_exists($v, 'toArray')) {
                $result[$k] = $v->toArray();
            } else if (is_object($v)) {
                foreach ($v as $_k => $_v) {
                    $result[$k][$_k] = $_v;
                }
            } else {
                $result[$k] = $v;
            }
        }

        return $result;
    }

    /**
     * @param string|int $key
     * @return bool|float|int|mixed|string|null
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * @param $key
     * @param $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Retrieves a value from the container using dot notation.
     * @param string|int $key     The key to retrieve the value for in dot notation.
     * @param mixed      $default The default value to return if the key is not found (default: null).
     * @param bool       $mode    The retrieval mode to use: strict or soft (default: strict).
     * @return mixed The retrieved value or the default value if the key is not found.
     */
    public function get($key, $default = null, bool $mode = self::STRICT)
    {
        $array = $this->container;

        $keys = explode('.', $key);
        if (empty($keys)) {
            return $default;
        }

        $value = $default;

        foreach ($keys as $key) {
            $array = is_object($array) ? $this->convertToArray($array) : $array;
            if (is_array($array) && array_key_exists($key, $array)) {
                $value = $array = $array[$key];
            } else {
                return $default;
            }
        }

        if ($mode === self::SOFT && (is_scalar($value) && !mb_strlen($value) || (!is_scalar($value) && !$value))) {
            $value = $default;
        }

        return $value;
    }

    /**
     * Sets a value in the container using a given key.
     * @param string|int $key   The key to set the value for.
     * @param mixed      $value The value to set for the key.
     * @return self Returns an instance of the current class.
     */
    public function set($key, $value) : self
    {
        if (false !== (strpos($key, "."))) {
            $ref =& $this->container;
            $skipUpdate = false;
            $deep = explode('.', $key);
            foreach ($deep as $_k => $vv) {
                isset($ref[$vv]) or $ref[$vv] = [];
                if ($ref instanceof self) {
                    $ref->set(implode('.', array_slice($deep, $_k)), $value);
                    $skipUpdate = true;
                } else {
                    $ref =& $ref[$vv];
                }
            }

            if (!$skipUpdate) {
                $ref = $value;
            }

            return $this;
        }

        $this->container[$key] = $value;

        return $this;
    }

    /**
     * @param $key
     * @return bool
     */
    public function __isset($key)
    {
        return $this->has($key);
    }

    /**
     * Checks if the given key exists in the container.
     * @param string|int $key The key to check for existence.
     * @return bool Returns true if the key exists, false otherwise.
     */
    public function has($key) : bool
    {
        if (!$key && !strlen($key)) {
            return false;
        }

        if (array_key_exists($key, $this->container)) {
            return true;
        }

        $result = false;
        $array = $this->container;
        foreach (explode('.', $key) as $key_part) {
            $result = array_key_exists($key_part, $array);
            if (!$result) {
                break;
            }
            $array = $array[$key_part];
        }

        return $result;
    }

    /**
     * @param $key
     * @return void
     */
    public function __unset($key)
    {
        $this->remove($key);
    }

    /**
     * Removes a value from the container.
     * @param string|array|int $keys The key of the value to remove.
     * @return self
     */
    public function remove($keys) : self
    {
        $original = &$this->container;

        $keys = (array) $keys;

        if (count($keys) === 0) {
            return $this;
        }

        foreach ($keys as $key) {
            // if the exact key exists in the top-level, remove it
            if (array_key_exists($key, $this->container)) {
                unset($this->container[$key]);

                continue;
            }

            $parts = explode('.', $key);

            $this->container = &$original;

            while (count($parts) > 1) {
                $part = array_shift($parts);

                if (isset($this->container[$part]) && is_array($this->container[$part])) {
                    $this->container = &$this->container[$part];
                } else {
                    continue 2;
                }
            }

            unset($this->container[array_shift($parts)]);
        }

        return $this;
    }

    /**
     * @param $offset
     * @param $value
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * @param $offset
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset) : bool
    {
        return $this->has($offset);
    }

    /**
     * @param $offset
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * @param $offset
     * @return bool|float|int|mixed|string|null
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->get($offset, null, self::STRICT);
    }

    /**
     * @return array
     */
    public function jsonSerialize() : array
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->toArray());
    }

    /**
     * Merge the given data into the object.
     * @param array|object|\DiePHP\DataObject $data The data to be merged (optional, default empty array).
     * @return self The current object after merging the data.
     */
    public function merge($data = []) : self
    {
        foreach ($this->convertToArray($data) as $k => $v) {
            if (is_array($v) || is_object($v)) {
                if ($array = $this->_flatten((array) $v, $k.'.')) {
                    foreach ($array as $_k => $_v) {
                        $this->set($_k, $_v);
                    }
                }
            } else {
                $this->set($k, $v);
            }
        }

        return $this;
    }

    /**
     * @param array $array
     * @param       $prefix
     * @return array
     */
    private function _flatten(array $array, $prefix) : array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_replace($result, $this->_flatten($value, $prefix.$key.'.'));
            } else {
                $result[$prefix.$key] = $value;
            }
        }

        return $result;
    }

    /**
     * Returns the first non-null value from the specified keys or the default value if none of the keys have a
     * non-null value.
     * @param array $keys    An array of keys to check.
     * @param mixed $default The default value to return if none of the keys have a non-null value. Defaults to null.
     * @return mixed The first non-null value from the specified keys or the default value if none of the keys have a
     *                       non-null value.
     */
    public function either(array $keys, $default = null)
    {
        foreach ($keys as $key) {
            if ($value = $this->get($key)) {
                return $value;
            }
        }

        return $default;
    }

    /**
     * Find the key associated with a given value or function.
     * @param mixed | callable $valueOrFunction The value to be searched for or a callback function.
     * @return int|string|null The key associated with the value, or null if the value is not found.
     */
    public function findKey($valueOrFunction)
    {
        if (is_callable($valueOrFunction)) {
            foreach ($this->toArray() as $k => $v) {
                if (call_user_func($valueOrFunction, $v, $k)) {
                    return $k;
                }
            }

            return null;
        }

        foreach ($this->toArray() as $k => $v) {
            if ($v === $valueOrFunction) {
                return $k;
            }
        }

        return null;
    }

    /**
     * Maps the container values using the specified keys and function.
     * @param mixed    $keys     The keys to match for mapping. Accepts an array of keys,
     *                           a string pattern, or null to match all keys.
     * @param callable $function The function to apply to each value in the container.
     * @param int      $mode     The mode for mapping. Optional. Defaults to MAP_USE_VALUE.
     *                           - MAP_USE_VALUE: Apply the function to the value only.
     *                           - MAP_USE_BOTH: Apply the function to both value and key.
     * @return self The new object with the mapped values.
     */
    public function map($keys, callable $function, int $mode = self::MAP_USE_VALUE) : self
    {
        $newObject = static::of([]);
        foreach ($this->container as $key => $value) {

            if ((is_array($keys) && $keys[0] === '*') || is_null($keys) || $keys === '*') {
                $newValue = call_user_func_array($function, $mode === self::MAP_USE_BOTH ? [$value, $key] : [$value]);
            } else if (is_array($keys) && in_array($key, $keys)) {
                $newValue = call_user_func_array($function, $mode === self::MAP_USE_BOTH ? [$value, $key] : [$value]);
            } else if (is_string($keys) && preg_match('/'.$keys.'/i', (string) $key)) {
                $newValue = call_user_func_array($function, $mode === self::MAP_USE_BOTH ? [$value, $key] : [$value]);
            } else {
                $newValue = $value;
            }

            $newObject->set($key, $newValue);
        }

        return $newObject;
    }

    /**
     * Create a new instance of the class based on an associative array.
     * @param array|object|\DiePHP\DataObject|\ArrayAccess $assocArray The associative array to initialize the class
     *                                                                 with.
     * @return self Returns a new instance of the class.
     */
    public static function of($assocArray) : self
    {
        return new static($assocArray);
    }

    /**
     * Calculates and returns the MD5 hash of the string representation of the object.
     * @return string The MD5 hash of the object.
     */
    public function hash() : string
    {
        return md5((string) $this);
    }

    /**
     * Filters the elements in the container based on a given callback function.
     * @param callable|null $function The callback function to use for filtering. The function should accept three
     *                                parameters:
     *                                - $value: The value of the element
     *                                - $key: The key of the element
     *                                - $container: The current container object
     *                                The function should return a boolean value. If it returns true, the element will
     *                                be removed from the container, otherwise it will be kept. If no callback function
     *                                is provided, the default behavior is to remove empty elements except for
     *                                instances of the same container.
     * @return self The filtered container.
     */
    public function filter(callable $function = null) : self
    {
        foreach ($this->container as $key => $value) {
            $removeKey = true;
            if ($function) {
                $removeKey = call_user_func_array($function, [$value, $key, $this]);
            } else {
                if ($value instanceof self) {
                    $value->filter($function);
                } else {
                    $removeKey = !empty($value);
                }
            }

            if (!$removeKey) {
                $this->remove($key);
            }
        }

        return $this;
    }

    /**
     * Checks if the object is empty.
     * @return bool Returns true if the object is empty, false otherwise.
     */
    public function isEmpty() : bool
    {
        return $this->count() === 0;
    }

    /**
     * Returns the number of elements in the container.
     * @return int The number of elements in the container.
     */
    public function count() : int
    {
        return count($this->container);
    }

    /**
     * Flatten the container.
     * @param string|null $prefix The prefix to be added to the flattened keys.
     * @return self
     */
    public function flatten(?string $prefix = '') : self
    {
        return static::of($this->_flatten($this->toArray(), $prefix));
    }

    /**
     * Transforms the data using the provided callable.
     * @param callable|string|array $callableData The callable data to be used for transforming the data.
     *                                            It can be a string representing a class name, an object implementing
     *                                            a 'transform' method, a closure, or an array containing a class name
     *                                            or function name and its corresponding method name.
     * @return self The current instance of the object after transformation.
     */
    public function transform($callableData) : self
    {
        if (is_string($callableData) && class_exists($callableData)) {
            $callableData = new $callableData;
        }

        if (is_object($callableData) && method_exists($callableData, 'transform')) {
            $this->container = $callableData->transform($this->toArray());
        } else if ($callableData instanceof Closure) {
            $this->container = $callableData($this->toArray());
        } else if (
            is_array($callableData) && method_exists($callableData[0], $callableData[1])
            || (is_string($callableData) && function_exists($callableData))
        ) {
            $this->container = call_user_func($callableData, $this->toArray());
        }

        return $this;
    }

    /**
     * Filters the current object by keeping only the specified keys.
     * @param array $keys The keys to keep
     * @return self Returns a new instance of the class that contains only the specified keys
     */
    public function only(array $keys) : self
    {
        return self::of(array_intersect_key($this->toArray(), array_flip($keys)));
    }

    /**
     * Retrieves all the keys from the container
     * @return array An array of keys
     */
    public function getKeys() : array
    {
        return array_keys($this->container);
    }

    public function __serialize() : array
    {
        return $this->container;
    }

    public function __unserialize(array $data) : void
    {
        $this->container = $data;
    }

    /**
     * Collapse the values in the current instance.
     * @return self
     */
    public function collapse() : self
    {
        $container = [];
        foreach ($this->getValues() as $values) {
            if (is_array($values)) {
                $container = array_merge($container, $values);
            } else if ($values instanceof static) {
                $container[] = $values->toArray();
            } else {
                $container[] = $values;
            }
        }

        return static::of($container);
    }

    /**
     * Retrieves the values from the container.
     * @return array The values from the container.
     */
    public function getValues() : array
    {
        return array_values($this->container);
    }

    /**
     * Clones the current object.
     * @return self The cloned object.
     */
    public function clone() : self
    {
        return self::of($this->toArray());
    }

}
