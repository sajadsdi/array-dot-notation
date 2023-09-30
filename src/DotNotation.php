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
    public function __construct(public array $array = []){}

    /**
     * Get multiple values from input array using dot notation.
     *
     * @param mixed ...$keys eg: 'user.profile.id','user.profile.pic'
     * @return mixed eg: [625 , '652.png']
     * @throws Exceptions\ArrayKeyNotFoundException
     */
    public function get(mixed...$keys): mixed
    {
        return $this->getByDotMulti($this->array,$keys);
    }

    /**
     * Check if a key exists in the array using dot notation.
     *
     * @param string $key The key to check for existence.
     * @return bool True if the key exists, false otherwise.
     */
    public function has(string $key):bool
    {
        try {
            $this->getByDot($this->array,$key);
            return true;
        }catch (ArrayKeyNotFoundException $e){
            return false;
        }
    }

    /**
     * Set multiple values in input array using dot notation.
     *
     * @param array $KeyValue eg: ['user.profile.id' => 625 , 'user.profile.pic' => '625.png']
     * @return $this
     */
    public function set(array $KeyValue): static
    {
        $this->array = $this->setByDotMulti($this->array ,$KeyValue);
        return $this;
    }
}