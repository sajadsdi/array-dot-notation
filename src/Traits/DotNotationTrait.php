<?php

namespace Sajadsdi\ArrayDotNotation\Traits;

use Sajadsdi\ArrayDotNotation\Exceptions\ArrayKeyNotFoundException;

/**
 * Trait DotNotationTrait
 *
 * A trait providing methods for working with dot notation in arrays.
 *
 * @package Sajadsdi\ArrayDotNotation
 */
trait DotNotationTrait
{

    /**
     * Get a value from an array using dot notation.
     *
     * @param array $array The input array.
     * @param string $keys The dot-separated keys.
     * @param mixed|null $default returned if not null and If the key is not found in the array
     * @param \Closure|null $callback called before return default value
     * @return mixed The value found in the array.
     *
     * @throws ArrayKeyNotFoundException If the key is not found in the array.
     */
    private function getByDot(array $array, string $keys = '', mixed $default = null, \Closure $callback = null): mixed
    {
        if ($keys === '') {
            return $array;
        }

        $items    = $this->dotToArray($keys);
        $result   = $array;
        $keysPath = '';
        foreach ($items as $i => $item) {
            if (isset($result[$item])) {
                $result   = $result[$item];
                $keysPath .= $item;
            } else {
                if ($default !== null) {
                    //before return the $default value callback called
                    $this->callClosure($callback, $keys, $default, $item);
                    return $default;
                }
                throw new ArrayKeyNotFoundException($item, $keysPath);
            }
            $keysPath .= ".";
        }
        return $result;
    }

    /**
     * Set a value in an array using dot notation.
     *
     * @param array $array The input array.
     * @param string $keys The dot-separated keys.
     * @param mixed $value The value to set.
     * @param \Closure|null $callback called after set
     * @return array The modified array.
     */
    private function setByDot(array $array, string $keys, mixed $value, \Closure $callback = null): array
    {
        $items   = $this->dotToArray($keys);
        $current = &$array;
        foreach ($items as $item) {
            if (!is_array($current)) {
                $current = []; // Remove data if current address is not an array
            }
            $current = &$current[$item];
        }
        $current = $value;
        $this->callClosure($callback, $keys, $value);
        return $array;
    }

    /**
     * Convert dot notation string to an array of keys.
     *
     * @param string $items The dot-separated keys.
     *
     * @return array An array of keys.
     */
    private function dotToArray(string $items): array
    {
        return explode('.', $items);
    }

    /**
     * Check if a key exists in the array using dot notation.
     *
     * @param string|int $key The key to check for existence.
     * @return bool True if the key exists, false otherwise.
     */
    public function isset(string|int $key): bool
    {
        try {
            $this->getByDot($this->array, $key);
            return true;
        } catch (ArrayKeyNotFoundException $e) {
            return false;
        }
    }

    /**
     * @param \Closure|null $closure
     * @param mixed ...$params
     * @return void
     */
    private function callClosure(?\Closure $closure, mixed ...$params): void
    {
        if ($closure instanceof \Closure) {
            $closure(...$params);
        }
    }
}
