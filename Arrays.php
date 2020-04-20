<?php
Namespace Seven\Vars;

use \Countable;

/**
 * @author Elisha Temiloluwa a.k.a TemmyScope (temmyscope@protonmail.com)
 * @copyright MIT
 *
*/

class Arrays Implements Countable
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
   * @param [] vars
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
  * Returns an array of arrays from the initial array containing any of the keys specified in the argument and their values
  * @param string['key' => 'value']
  * @return array
  */
  public function search(Array $k_v )
  {
    $new = [];
    foreach ($this->var as $key => $value) {
      foreach ($k_v as $k => $v) {
        if ( isset($value[$k]) && $value[$k] == $v ) {
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
  * @method(s) returning serialized and unserialized data
  */
  public function serialize()
  {
    return serialize($this->var);
  }

  public function unserialize()
  {
    return unserialize($this->var);
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