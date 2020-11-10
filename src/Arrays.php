<?php

namespace Seven\Vars;

use Countable;
use Serializable;
use ArrayAccess;
use Seven\Vars\{Strings, Validation};
/**
 * @author Elisha Temiloluwa a.k.a TemmyScope (temmyscope@protonmail.com)
 * @copyright MIT
 *
*/

class Arrays implements ArrayAccess, Countable, Serializable
{
    /**
    * @property var
    */
    
    protected $var = [];
    
    /**
    * @param Array $arr is an array of arrays i.e. 2 levels deep array
    */

    public function __construct(array $arr = [])
    {
        $this->var = $arr;
    }

    /**
    * @param []array $arr
    */

    public static function init(array $arr)
    {
        return new self($arr);
    }

    /**
    * @param []object $arr
    */

    public static function safeInit(array $arr)
    {
        $newArray = [];
        foreach ($arr as $key => $value) {
            $newArray[$key] = (array) $value;
        }
        return new self($newArray);
    }

    public function validateOrDelete(array $rules)
    {
        foreach ($this->var as $key => &$value) {
            $validation = Validation::init($value);
            $validation->rules($rules);
            if( !$validation->passed() ){
                unset($this->var[$key]);
            }
        }
        return $this;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->var[] = $value;
        } else {
            $this->var[$offset] = $value;
        }
    }

    public function offsetExists($offset): bool
    {
        return isset($this->var[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->var[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->var[$offset] ?? null;
    }

    /**
    * Adds an array to the existing one
    * @param [] vars
    * @return Arrays $this
    */
    public function add(array $var): Arrays
    {
        $this->var[] = $var;
        return $this;
    }

    /**
    * Adds multiple arrays to the existing one by looping through
    * @param array[] $var
    * @return Arrays $this
    */
    public function addEach(array $var): Arrays
    {
        foreach ($var as $key => $value) {
            $this->var[] = $value;
        }
        return $this;
    }

    public function sum(string $key)
    {
        $sum = 0;
        foreach ($this->var as $key => $value) {
            $sum += $value[$key];
        }
        return $sum;
    }

    public function avg(string $key)
    {
        $sum = 0;
        $total = 0;
        foreach ($this->var as $k => $v) {
            if (array_key_exists($key, $v) && is_numeric($v[$key])) {
                $sum += $v[$key];
                $total++;
            }
        }
        return (float)($sum/$total);
    }

  /**
   * Adds each key and value to each array
   * @param [] $var
   * @example [ 'key' => 'value', 'another_key' => 'another_value'];
   * @return Arrays $this
  */
    public function addToEach(array $k_v): Arrays
    {
        foreach ($this->var as $key => &$value) {
            foreach ($k_v as $k => $v) {
                $value[$k] = $v;
            }
        }
        return $this;
    }

    /**
    * Removes the last array from the existing one
    * @return array
    */

    public function pop(): array
    {
        return array_pop($this->var);
    }

    /**
    * Removes the last array from each array inside the existing one
    * @return array
    */
    
    public function popEach(): array
    {
        $arr = [];
        foreach ($this->var as $key => &$value) {
            $arr[] = array_pop($value);
        }
        return $arr;
    }

    /**
    * Removes the first array from the existing one and return it
    * @return array
    */
    
    public function shift(): array
    {
        return  array_shift($this->var);
    }


    /**
    * Removes the first array from each array inside the existing one and returns it
    * @return array
    */
    
    public function shiftEach(): array
    {
        $arr = [];
        foreach ($this->var as $key => &$value) {
            $arr[] = array_shift($value);
        }
        return $arr;
    }

    /**
    * Sorts all arrays inside the main array by checking the 'natural' order of the passed key in eah of those arrays
    * @return Arrays $this
    */
    
    public function sort(string $key): Arrays
    {
        usort(
            $this->var,
            function ($arr1, $arr2) use ($key) {
                return $arr1[$key] <=> $arr2[$key];
            }
        );
        return $this;
    }

    public function reverse(): Arrays
    {
        return new Arrays( array_reverse($this->var));
    }

    public function upSort(string $key): Arrays
    {
        return $this->sort($key);
    }

    public function downSort(string $key): Arrays
    {
        usort(
            $this->var,
            function ($arr1, $arr2) use ($key) {
                return $arr2[$key] <=> $arr1[$key];
            }
        );
        return $this;
    }

    /**
    * Returns the last array
    * @return array
    */
    
    public function last(): array
    {
        return end($this->var);
    }

    /**
    * Returns the first array
    * @return array
    */
    
    public function first(): array
    {
        return $this->var[0];
    }

    /**
    * Apply a closure | method | function on each array
    * @method Arrays apply
    * @param callable $fn : [] => must return array
    * 
    */
    
    public function apply(callable $fn): Arrays
    {
        foreach ($this->var as $key => &$value) {
            $value = call_user_func_array($fn, [&$value]);
        }
        return $this;
    }

    public function map(callable $fn): Arrays
    {
        return $this->apply($fn);
    }

    /**
    * sets a new value for each of the arrays using each of the key -> value pair passed,
    * if an index is passed, only the key at that array index will be changed
    * @param [] param ['key' => 'value']
    * @param int|null index
    * @return $this
    */

    public function set(array $param, $index = null): Arrays
    {
        if (ctype_digit($index)) {
            foreach ($param as $k => $v) {
                $this->var[$index][$k] = $v;
            }
        } else {
            foreach ($this->var as $key => &$value) {
                foreach ($param as $k => $v) {
                    $value[$k] = $v;
                }
            }
        }
        return $this;
    }

    /**
    * renames a key in each of the arrays using each of the key -> value pair passed,
    * if an index is passed, only the key at that array index will be changed
    * @param [] k_v
    * @example ['old_key' => 'new_key']
    * @param int|null index
    * @return $this
    */

    public function rename(array $k_v, ?int $index = null): Arrays
    {
        if (is_int($index)) {
            foreach ($k_v as $k => $v) {
                $this->var[$index][$v] = $this->var[$index][$k];
                unset($this->var[$index][$k]);
            }
        } else {
            foreach ($this->var as $key => &$value) {
                foreach ($k_v as $k => $v) {
                    $value[$v] = $value[$k];
                    unset($value[$k]);
                }
            }
        }
        return $this;
    }

    /**
    * concatenates values of a particular key of multiple arrays using the passed separator and saving it on the new name
    * @param array keys
    * @param string new_name
    * @param string separator
    * @return Arrays $this
    */

    public function concat(array $keys, string $new_name, string $separator = ","): Arrays
    {
        foreach ($this->var as $key => &$value) {
            $value[$new_name] = "";
            foreach ($keys as $k => $v) {
                    $value[$new_name] .= $value[$v] . $separator;
            }
            $value[$new_name] = trim($value[$new_name], $separator);
        }
        return $this;
    }

    /**
    * Extract key from the initial array using the key, value pair in the passed argument
    * @param $k_v = [ 'key' => 'value' ]
    * @return Arrays $this
    */
    
    public function extractBy(array $k_v): Arrays
    {
        $new = [];
        foreach ($this->var as $key => &$value) {
            foreach ($k_v as $k => $v) {
                if (array_key_exists($k, $value) && $value[$k] === $v) {
                    $new[] = $value;
                    break;
                }
            }
        }
        $this->var = $new;
        return $this;
    }

    /**
    * Extracts all the arrays containing the passed key
    * @return Arrays $this
    */
    
    public function extractByKey($key): Arrays
    {
        $new = [];
        foreach ($this->var as $k => $value) {
            if (array_key_exists($key, $value)) {
                $new[] = $value;
            }
        }
        $this->var = $new;
        return $this;
    }

    /**
    * Extracts from all the arrays, if it contains the passed key
    * @return array
    */
    
    public function extractKey($key): array
    {
        $new = [];
        foreach ($this->var as $k => $v) {
            if (array_key_exists($key, $v)) {
                $new[] = $v[$key];
            }
        }
        return $new;
    }

    /**
    * Excludes all array containing the passed key
    * @return Arrays $this
    */
    
    public function excludeByKey(string $key): Arrays
    {
        foreach ($this->var as $k => &$v) {
            if (array_key_exists($key, $v)) {
                    unset($this->var[$k]);
            }
        }
        return $this;
    }


    /**
    * Exclude keys from the initial array using the key, value pair in the passed argument
    * @param $k_v = [ 'key' => 'value' ]
    * @return Arrays $this
    */

    public function excludeBy(array $k_v): Arrays
    {
        foreach ($this->var as $key => &$value) {
            foreach ($k_v as $k => $v) {
                if (array_key_exists($k, $value) && $value[$k] == $v) {
                    unset($this->var[$key]);
                    break;
                }
            }
        }
        return $this;
    }

    /**
    * Exclude keys from the initial array
    * @param variadic argument $keys
    * @return Arrays
    */

    public function excludeKey(...$keys): Arrays
    {
        foreach ($this->var as $key => &$value) {
            foreach ($keys as $k => $v) {
                if (array_key_exists($v, $value)) {
                    unset($value[$v]);
                    break;
                }
            }
        }
        return $this;
    }

    /**
    * Returns an array of arrays from the initial array containing any of the keys specified in the argument
    * @param [] string
    * @return array
    */

    public function whiteList(array $whitelist): array
    {
        $new = [];
        foreach ($this->var as $key => $value) {
            $new [] = array_intersect_key($value, array_flip($whitelist));
        }
        return $new;
    }

    /**
    * picks out random arrays
    * the set size has to be smaller than the total size of the array being processed
    * @param in $size
    * @return []
    */

    public function random(int $size): array
    {
        $arr = $this->var;
        shuffle($arr);
        $new = [];
        foreach (range(0, $size-1) as $key) {
            $new[$key] = $arr[$key]; 
        }
        return $new;
    }

    /**
    * search the value in a key if it contains a certain string
    * @param mixed $search
    * @param string $key
    * @return []
    */

    public function searchFor(mixed $search, string $key): array
    {
        $sub = [];
        foreach ($this->var as $k => $v) {
            if (Strings::contains($v[$key], $search, true)) {
                $sub[] = $v;
            }
        }
        return $sub;
    }

    /**
    * Returns an array of arrays from the initial array containing any of the keys 
    * specified in the argument and their values
    * @param string['key' => 'value']
    * @return array
    */

    public function search(array $k_v): array
    {
        $new = [];
        foreach ($this->var as $key => $value) {
            foreach ($k_v as $k => $v) {
                if (isset($value[$k]) && Strings::contains($value[$k], $v)) {
                    $new[] = $value;
                    break;
                }
            }
        }
        return $new;
    }

    /**
    * Returns an array of the size and start position specified
    * @param int count
    * @param int start
    * @return array
    */

    public function trim(int $count, int $start = 0): array
    {
        $k = [];
        $end = ($start + $count) - 1;
        for ($i = $start; $i <= $end; $i++) {
            $k[] = $this->var[$i];
        }
        return $k;
    }

    /**
    * @return Generator[]
    */

    public function &enumerate()
    {
        foreach ($this->var as $key => &$value) {
            yield [$key, $value];
        }
    }

    /**
    * Returns the count of arrays
    * @return int
    */

    public function count(): int
    {
        return count($this->var);
    }

    /**
    * checks if the key exists in at least one of the arrays
    * @return bool
    */

    public function exists(string $k): bool
    {
        foreach ($this->var as $key) {
            if (isset($key[$k])) {
                return true;
            }
        }
        return false;
    }

    /**
    * Returns current state of array
    * @method(s) for retrieval of data
    * @return Array
    */

    public function get(): array
    {
        return $this->var;
    }

    /**
    * @method serailize for serializing and unserializing data
    * @return string 
    */

    public function serialize()
    {
        return serialize($this->var);
    }

    /**
    * @method unserailize for unserializing data
    * @param string
    * @return mixed
    */

    public function unSerialize($serialized)
    {
        return unserialize($serialized);
    }

    /**
    * @method returning json_encoded data
    */

    public function getJson()
    {
        return json_encode($this->var);
    }

    /**
    * @method returning array of objects
    */
    public function getObjects()
    {
        return $this->objectify($this->var);
    }

    private function objectify(array $arr): array
    {
        $new_class = [];
        foreach ($arr as $key => $value) {
            $new_class[] = (object) $value;
        }
        return $new_class;
    }
}
