<?php

namespace Sajadsdi\ArrayDotNotation\Traits;

use Closure;
use Sajadsdi\ArrayDotNotation\Exceptions\ArrayKeyNotFoundException;

/**
 * Trait MultiDotNotationTrait
 *
 * A trait extending DotNotationTrait, providing methods for working with multiple dot notation keys.
 *
 * @package Sajadsdi\ArrayDotNotation
 */
trait MultiDotNotationTrait
{
    use DotNotationTrait;

    /**
     * Get multiple values from an array using dot notation.
     *
     * @param array $array The input array.
     * @param array $keys An array of dot-separated keys.
     * @param mixed|null $default returned if not null and If the key is not found in the array
     * @param Closure|null $callbackDefault called before set the default value for each key
     * @param Closure|null $callback called before get value of each key
     * @return mixed The values found in the array.
     *
     * @throws ArrayKeyNotFoundException If a key is not found in the array.
     */
    public function getByDotMulti(array $array, array $keys = [], mixed $default = null, Closure $callbackDefault = null, Closure $callback = null): mixed
    {
        if (!$keys) {
            return $array;
        }

        $result      = [];
        $first       = null;
        $resultCount = 0;

        foreach ($keys as $i => $key) {
            if (is_array($key)) {
                $j          = is_numeric($i) ? $resultCount : $i;
                $result[$j] = $this->getByDotMulti($array, $key, $this->getDefault($default, $resultCount), $callbackDefault, $callback);
            } else {
                $j = !is_numeric($i) ? $i : $this->getItem($key, $resultCount);
                if (isset($result[$j])) {
                    $j .= '_' . $resultCount;
                }
                $result[$j] = $this->getByDot($array, $key, $this->getDefault($default, $resultCount), $callbackDefault, $callback);
            }
            if (!$first) {
                $first = $j;
            }
            $resultCount++;
        }

        return $resultCount == 1 ? $result[$first] : $result;
    }

    /**
     * Set multiple values in an array using dot notation.
     *
     * @param array $array The input array.
     * @param array $keyValue An associative array of dot-separated keys and values.
     * @param Closure|null $callback called before set
     * @return array The modified array.
     */
    public function setByDotMulti(array &$array, array $keyValue, Closure $callback = null): array
    {
        foreach ($keyValue as $key => $value) {
            $this->setByDot($array, $key, $value, $callback);
        }

        return $array;
    }

    /**
     * Delete multiple keys in an array using dot notation.
     *
     * @param array $array The input array.
     * @param array $keys array of dot-separated keys.
     * @param bool $throw exception thrown if true for each key not found
     * @param Closure|null $callback called after delete
     * @return array The modified array.
     * @throws ArrayKeyNotFoundException
     */
    public function deleteByDotMulti(array &$array, array $keys, bool $throw = false, Closure $callback = null): array
    {
        foreach ($keys as $key) {
            $this->deleteByDot($array, $key, $throw, $callback);
        }

        return $array;
    }

    /**
     * @param string $keys
     * @param int $i
     * @return mixed
     */
    private function getItem(string $keys, int $i): mixed
    {
        $items   = $this->dotToArray($keys);
        $endItem = end($items);
        return !is_numeric($endItem) ? $endItem : $i;
    }

    /**
     * @param mixed $default
     * @param int $i
     * @return mixed|null
     */
    private function getDefault(mixed $default, int $i): mixed
    {
        if (is_array($default)) {
            return $default[$i] ?? null;
        }

        return $default;
    }

    /**
     * Check if all keys exists in the array using dot notation.
     *
     * @param array $array The input array.
     * @param array $keys The key to check for existence.
     * @return bool True if the key exists, false otherwise.
     */
    public function issetAll(array $array, array $keys): bool
    {
        foreach ($keys as $key) {
            if (!$this->isset($array, $key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if a key of keys exists in the array using dot notation.
     *
     * @param array $array The input array.
     * @param array $keys The key to check for existence.
     * @return bool True if the key exists, false otherwise.
     */
    public function issetOne(array $array, array $keys): bool
    {
        foreach ($keys as $key) {
            if ($this->isset($array, $key)) {
                return true;
            }
        }

        return false;
    }
}
