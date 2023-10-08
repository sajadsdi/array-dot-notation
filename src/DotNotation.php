<?php

namespace Sajadsdi\ArrayDotNotation;

use Sajadsdi\ArrayDotNotation\Exceptions\ArrayKeyNotFoundException;
use Sajadsdi\ArrayDotNotation\Traits\MultiDotNotationTrait;

/**
 * Class DotNotation
 *
 * A Class providing methods for set and get data with dot notation in arrays.
 *
 * @package Sajadsdi\ArrayDotNotation
 */
class DotNotation
{
    use MultiDotNotationTrait;

    /**
     * @param array $array input array
     */
    public function __construct(public array $array = [])
    {

    }

    /**
     * Get multiple values from input array using dot notation.
     *
     * @param mixed $keys eg: ['user.profile.id','user.profile.pic']
     * @param mixed|null $default returned if not null and If the key is not found in the array
     * @param \Closure|null $callback called before return default value for each key
     * @return mixed eg: ['id' => 625 ,'pic' => '652.png']
     * @throws ArrayKeyNotFoundException
     */
    public function get(mixed $keys, mixed $default = null, \Closure $callback = null): mixed
    {
        return $this->getByDotMulti($this->array, is_array($keys) ? $keys : [$keys], $default, $callback);
    }

    /**
     * Set multiple values in input array using dot notation.
     *
     * @param array $KeyValue eg: ['user.profile.id' => 625 , 'user.profile.pic' => '625.png']
     * @param \Closure|null $callback called after set
     * @return $this
     */
    public function set(array $KeyValue, \Closure $callback = null): static
    {
        $this->array = $this->setByDotMulti($this->array, $KeyValue, $callback);
        return $this;
    }

    /**
     * Check if one or more key(s) exists in the array using dot notation.
     *
     * @param mixed $key The key(s) to check for existence.
     * @return bool True if the key(s) exists, false otherwise.
     */
    public function has(mixed $key): bool
    {
        if ($key !== null) {
            if (is_array($key)) {
                return $this->issetAll($key);
            }
            return $this->isset($key);
        }
        return false;
    }

    /**
     * Check if one key of keys exists in the array using dot notation.
     *
     * @param mixed $keys The key(s) to check for existence.
     * @return bool True if the key(s) exists, false otherwise.
     */
    public function hasOne(array $keys): bool
    {
        return $this->issetOne($keys);
    }
}