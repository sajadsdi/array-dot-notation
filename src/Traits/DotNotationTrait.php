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
     *
     * @return mixed The value found in the array.
     *
     * @throws ArrayKeyNotFoundException If the key is not found in the array.
     */
    private function getByDot(array $array, string $keys = ''): mixed
    {
        if (!$keys) {
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
                return throw new ArrayKeyNotFoundException($item, $keysPath);
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
     *
     * @return array The modified array.
     */
    private function setByDot(array $array, string $keys, mixed $value): array
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
}
