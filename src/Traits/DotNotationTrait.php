<?php

namespace Sajadsdi\ArrayDotNotation\Traits;

use Closure;
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
     * @param Closure|null $callbackDefault called before set default value
     * @param Closure|null $callback called if keys set and before return result
     * @return mixed The value found in the array.
     *
     * @throws ArrayKeyNotFoundException If the key is not found in the array.
     */
    public function getByDot(array $array, string $keys = '', mixed $default = null, Closure $callbackDefault = null, Closure $callback = null): mixed
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
                    $this->callClosure($callbackDefault, $default, $keys, $item);
                    $result = $default;
                } else {
                    throw new ArrayKeyNotFoundException($item, $keysPath);
                }
            }
            $keysPath .= ".";
        }
        //before return the $result callback called
        $this->callClosure($callback, $result, $keys);

        return $result;
    }

    /**
     * Set a value in an array using dot notation.
     *
     * @param array $array The input array.
     * @param string $keys The dot-separated keys.
     * @param mixed $value The value to set.
     * @param Closure|null $callback called before set
     * @return array The modified array.
     */
    public function setByDot(array &$array, string $keys, mixed $value, Closure $callback = null): array
    {
        $items   = $this->dotToArray($keys);
        $current = &$array;

        foreach ($items as $item) {
            if (!is_array($current)) {
                $current = []; // Remove data if current address is not an array
            }
            $current = &$current[$item];
        }

        if ($current !== $value) {
            // callback called before set
            $this->callClosure($callback, $value, $keys);

            $current = $value;
        }

        return $array;
    }

    /**
     * delete a key in an array using dot notation.
     *
     * @param array $array The input array.
     * @param string $keys The dot-separated keys.
     * @param bool $throw exception thrown if true for key not found
     * @param Closure|null $callback called after delete
     * @return array The modified array.
     * @throws ArrayKeyNotFoundException
     */
    public function deleteByDot(array &$array, string $keys, bool $throw = false, Closure $callback = null): array
    {
        $items   = $this->dotToArray($keys);
        $current = &$array;
        $error   = false;
        $count   = count($items);
        foreach ($items as $i => $item) {
            if (!is_array($current) || !isset($current[$item])) {
                $error = true;// item address not found
                break;
            }
            if ($count - 1 != $i) {
                $current = &$current[$item];
            }
        }

        if ($error) {
            if ($throw) {
                throw new ArrayKeyNotFoundException($keys);
            }
        } else {
            $item     = $items[$count - 1];
            $oldValue = $current[$item];
            unset($current[$item]);
            $this->callClosure($callback, $keys, $oldValue);
        }

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
     * @param Closure|null $closure
     * @param mixed &$reference
     * @param mixed ...$params
     * @return void
     */
    private function callClosure(?Closure $closure, mixed &$reference, mixed...$params): void
    {
        if ($closure instanceof Closure) {
            $closure($reference, ...$params);
        }
    }
}
