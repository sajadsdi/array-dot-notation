<?php

namespace Sajadsdi\ArrayDotNotation\Traits;

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
     * @return mixed The values found in the array.
     *
     * @throws ArrayKeyNotFoundException If a key is not found in the array.
     */
    private function getByDotMulti(array $array, array $keys = [], mixed $default = null): mixed
    {
        if ($keys) {
            $result      = [];
            $first       = null;
            $resultCount = 0;
            foreach ($keys as $i => $key) {
                if (is_array($key)) {
                    $j          = is_numeric($i) ? $resultCount : $i;
                    $result[$j] = $this->getByDotMulti($array, $key, $this->getDefault($default, $resultCount));
                } else {
                    $j = !is_numeric($i) ? $i : $this->getItem($key, $resultCount);
                    if (isset($result[$j])) {
                        $j .= '_' . $resultCount;
                    }
                    $result[$j] = $this->getByDot($array, $key, $this->getDefault($default, $resultCount));
                }
                if (!$first) {
                    $first = $j;
                }
                $resultCount++;
            }
            return $resultCount == 1 ? $result[$first] : $result;
        } else {
            return $array;
        }
    }

    /**
     * Set multiple values in an array using dot notation.
     *
     * @param array $array The input array.
     * @param array $keyValue An associative array of dot-separated keys and values.
     *
     * @return array The modified array.
     */
    private function setByDotMulti(array $array, array $keyValue): array
    {
        foreach ($keyValue as $key => $value) {
            $array = $this->setByDot($array, $key, $value);
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
     * @param array $keys The key to check for existence.
     * @return bool True if the key exists, false otherwise.
     */
    public function issetAll(array $keys):bool
    {
        foreach ($keys as $key){
            if(!$this->isset($key)){
                return false;
            }
        }
        return true;
    }

    /**
     * Check if a key of keys exists in the array using dot notation.
     *
     * @param array $keys The key to check for existence.
     * @return bool True if the key exists, false otherwise.
     */
    public function issetOne(array $keys):bool
    {
        foreach ($keys as $key){
            if($this->isset($key)){
                return true;
            }
        }
        return false;
    }
}