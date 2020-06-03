<?php
Namespace Seven\Vars;

use Seven\Vars\Strings;
use \Countable;
use \Serializable;
use \ArrayAccess;

/**
 * @author Elisha Temiloluwa a.k.a TemmyScope (temmyscope@protonmail.com)
 * @copyright MIT
 *
*/

class Arrays Implements Countable, Serializable, ArrayAccess
{
  /**
  *@property var
  */
  protected $var = [];

  /**
   * @param Array $arr is an array of arrays i.e. 2 levels deep array
  **/
  public function __construct(Array $arr = [])
  {
    $this->var = $arr;
  }
  
  public static function init(Array $arr = [])
  {
    return new self($arr);
  }

  public function offsetSet($offset, $value) {
    if (is_null($offset)) {
        $this->var[] = $value;
    } else {
        $this->var[$offset] = $value;
    }
  }

  public function offsetExists($offset) {
    return isset($this->var[$offset]);
  }

  public function offsetUnset($offset) {
    unset($this->var[$offset]);
  }

  public function offsetGet($offset){
    return isset($this->var[$offset]) ? $this->var[$offset] : null;
  }

  /** 
   * Adds an array to the existing one
   * @param [] vars
   * @return Arrays $this
  */
  public function add(Array $var)
  {
    $this->var[] = $var;
    return $this;
  }

  /** 
   * Adds multiple arrays to the existing one by looping through
   * @param array[] $var
   * @return Arrays $this
  */
  public function add_each(Array $var)
  {
    foreach ($var as $key => $value) {
      $this->var[] = $value;
    }
    return $this;
  }

  /** 
   * Adds each key and value to each array
   * @param [] $var
   * @example [ 'key' => 'value', 'another_key' => 'another_value'];
   * @return Arrays $this
  */
  public function add_to_each(Array $k_v): Arrays
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
   * @return Arrays $this
  */
  public function pop(): Arrays
  {
    array_pop($this->var);
    return $this;
  }

  /** 
   * Removes the last array from each array inside the existing one
   * @return Arrays $this
  */
  public function pop_each()
  {
    foreach ($this->var as $key => &$value) {
      array_pop($value);
    }
    return $this;
  }

  /** 
   * Removes the first array from the existing one
   * @return Arrays $this
  */
  public function shift(): Arrays
  {
    array_shift($this->var);
    return $this;
  }


  /** 
   * Removes the first array from each array inside the existing one
   * @return Arrays $this
  */
  public function shift_each()
  {
    foreach ($this->var as $key => &$value) {
      array_shift($value);
    }
    return $this;
  }  

  /** 
  * Sorts all arrays inside the main array by checking the 'natural' order of the passed key in eah of those arrays
  * @return Arrays $this
  */
  public function sort(string $key): Arrays
  {
    usort($this->var, 
      function ($arr1, $arr2) use ($key) {
        return $arr1[$key] <=> $arr2[$key];
    });
    return $this;
  }

  public function up_sort(string $key): Arrays
  {
    return $this->sort($key);
  }

  public function down_sort(string $key): Arrays
  {
    usort($this->var, 
      function ($arr1, $arr2) use ($key) {
        return $arr2[$key] <=> $arr1[$key];
    });
    return $this;
  }

  /** 
   * Returns the last array
   * @return Arrays $this
  */
  public function last(): Arrays
  {
    return new Arrays( end($this->var) );
  }

  /** 
   * Returns the first array
   * @return Arrays $this
  */
  public function first(): Arrays
  {
    return new Arrays( $this->var[0] );
  }

  /**
  * @method(s) for data modification
  * START
  */

  /**
  * Apply a closure | method | function on certain keys and store result in the $to key
  * @param Callable (array | Closure) $fn
  * @example [$object, method] OR function() use {}
  * @param string $to
  * @param variadic $_keys
  */
  public function apply(Callable $fn, $to, ...$_keys): Arrays
  {
    foreach($this->var as $key => &$value){
      $params = [];
      foreach ($_keys as $k => $v) {
        $params[] = $value[$v];
      }
      $value[$to] = call_user_func_array($fn, $params);
    }
    return $this;
  }

  /**
  * Multi-Apply a closure | method | function on certain keys and store result in the $to key
  * @param []Callable (array | Closure) $fn
  * @param []string $tos
  * @param []array $array_of_keys 
  * @return Arrays
  */
  public function multi_apply(Array $callables, Array $tos, Array $array_of_keys): Arrays
  {
    foreach ($this->var as $key => &$value) {
      foreach ($callables as $k => $callable) {
        $current = $array_of_keys[$k];
        $params = [];
        foreach ($current as $index => $param_key) {
          $params[] = $value[$param_key];
        }
        $value[$tos[$k]]  = call_user_func_array($callable, $params);
      }
    }
    return $this;
  }

  /**
  * sets a new value for each of the arrays using each of the key -> value pair passed,
  * if an index is passed, only the key at that array index will be changed
  * @param [] param ['key' => 'value']
  * @param int|null index
  * @return $this
  */
  public function set(array $param, $index = null): Arrays{
    if( ctype_digit($index) ){
      foreach ($param as $k => $v) {
        $this->var[$index][$k] = $v;  
      }
    }else{
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
  public function rename(array $k_v, $index = null): Arrays
  {
    if( is_int($index) ){
      foreach ($k_v as $k => $v) {
        $this->var[$index][$v] = $this->var[$index][$k];
        unset($this->var[$index][$k]);
      }
    }else{
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
   * merge values of multiple keys of an array into a single sub-array of that array which iis part of the larger Arrays
   * @param array keys 
   * @param string new_name
   * @return Arrays $this
  */  
  public function merge(array $keys, string $new_name){
    foreach($this->var as $k => &$value){
      $value[$new_name] = [];
      foreach ($keys as $key) {
        $value[$new_name][] = $value[$key];
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
  public function concat(array $keys, string $new_name, string $separator = "_"): Arrays
  {
    foreach($this->var as $key => &$value){
      $value[$new_name] = "";
      foreach ($keys as $k => $v) {
        $value[$new_name] .= $value[$v].$separator;
      }
      $value[$new_name] = trim($value[$new_name], $separator);
    }
    return $this;
  }

  /** 
   * Extracts all the arrays containing the passed key
   * @return Arrays $this
  */
  public function extract_by_key($key)
  {
    $new = [];
    foreach($this->var as $k => $value){
      if(array_key_exists( $key, $value )){
        $new[] = $value;
      }
    }
    $this->var = $new;
    return $this;
  }

  /** 
   * Extracts from all the arrays, if it contains the passed key
   * @return Arrays $this
  */
  public function extract_key($key)
  {
    $new = [];
    foreach ($this->var as $k => $v) {
      if ( array_key_exists($key, $v) ) {
        $new[] = $v;
      }
    }
    $this->var = $new;
    return $this;
  }

  /** 
   * Excludes all array containing the passed key
   * @return Arrays $this
  */
  public function exclude_by_key(string $key): Arrays
  {
    foreach($this->var as $k => &$v){
      if(array_key_exists($key, $v)){
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
  public function exclude_by(Array $k_v ): Arrays
  {
    foreach ( $this->var as $key => &$value ) {
      foreach ($k_v as $k => $v){
        if ( array_key_exists($k, $value) && $value[$k] == $v ) { 
          unset( $this->var[$key] );
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
  public function exclude_key(...$keys): Arrays
  {
    return $this->hide(...$keys);
  }

  public function hide(...$keys): Arrays
  {
    foreach ($this->var as $key => &$value) {
      foreach ($keys as $k => $v) {
        if(array_key_exists($v, $value)){
          unset($value[$v]);
        break;
        }
      }  
    }
    return $this;
  }

  /**
  * @method (s) that operate and return an array immediately
  */

  /**
  * Returns an array of arrays from the initial array containing any of the keys specified in the argument
  * @param string['key' => 'value']
  * @return array
  */
  public function whitelist(Array $whitelist): Array
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
  public function random(int $size): Array
  {
    $arr = $this->var;
    $new = [];
    shuffle($arr);
    for ($i=0; $i<$size ; $i++) { 
      $new[$i] = $arr[$i];
    }
    return $new;
  }

  /**
  * search the value in a key if it contains a certain string
  * @param string $key
  * @param string $search
  * @return []
  */

  public function search_in(string $key, $search): array
  {
    $sub = [];
    foreach($this->var as $k => $v){
      if ( Strings::contains( $v[$key], $search, true )  ) {
        $sub[] = $v;
      }
    }
    return $sub;  
  }

  /**
  * search for each string of an array in a certain key
  * @param string[] $search
  * @param string $key
  * @return []
  */
  public function search_for(array $search, string $key): array
  {
    return $this->search_in($key, $search); 
  }

  /**
  * Returns an array of arrays from the initial array containing any of the keys specified in the argument and their values
  * @param string['key' => 'value']
  * @return array
  */
  public function search(Array $k_v )
  {
    $new = [];
    foreach ($this->var as $key => $value) {
      foreach ($k_v as $k => $v) {
        if ( isset($value[$k]) && Strings::contains($value[$k], $v) ) {
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
  public function trim(int $count, int $start = 0)
  {
    $k = [];
    $end = ($start + $count) - 1;
    for($i=$start; $i <= $end; $i++){
      $k[] = $this->var[$i];
    }
    return $k;
  }

  /**
  * @method(s) that return current status of data
  * START
  */

  /**
  * Returns the count of arrays
  * @return int 
  */

  public function count(): int
  {
    return count($this->var);
  }

  /**
  * checks if the key exists in any of the internal arrays
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

  public function get(): Array
  {
    return $this->var;
  }

  public function return()
  {
    return $this->var;
  }

  /**
  * @method(s) serializing and unserializing data
  */
  public function serialize()
  {
    return serialize($this->var);
  }

  public function unserialize($serialized)
  {
    $this->var = unserialize($serialized);
  }

  /**
  * @method(s) returning json_encoded data
  */
  public function returnJson()
  {
    return json_encode($this->var);
  }

  public function getJson()
  {
    $this->returnJson();
  }

  public function returnObjects()
  {
      return $this->objectify($this->var);
  }

  private function objectify(array $arr): Array{
    $new_class = [];
    foreach ($arr as $key => $value) {
      $new_class[] = (object) $value;
    }
    return $new_class;
  }

} 