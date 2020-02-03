<?php
Namespace Seven\Vars;

use \Countable;

class Arrays Implements Countable
{
	/**
	*@property var
	*/
	protected $var = [];

	public function __construct(Array $arr = [])
	{
		$this->var = $arr;
	}

  public function add(Array $var)
  {
    array_push($this->var, $var);
    return $this; 
  }

  public function pop(): Arrays
  {
    array_pop($this->var);
    return $this;
  }

  public function shift(): Arrays
  {
    array_shift($this->var);
    return $this;
  }

  public function sort(string $key): Arrays
  {
    usort($this->var, 
      function ($arr1, $arr2) use ($key) {
        return $arr1[$key] <=> $arr2[$key];
    });
    return $this;
  }

  public function last(): Arrays
  {
    return new Arrays( end($this->var) );
  }

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

  public function merge(array $keys, string $new_name){
		foreach($this->var as $k => &$value){
			$value[$new_name] = [];
			foreach ($keys as $key) {
				$value[$new_name][] = $value[$key];
			}
		}
		return $this;
	}

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
  * @param Array $k_v
  * @example  $k_v = [ 'key' => 'value' ]
  */
	public function exclude_by(Array $k_v ): Arrays
	{
		foreach ( $this->var as $key => &$value ) {
      foreach ($k_v as $k => $v){
        if ( array_key_exists($k, $value) && $value[$k] == $v ) { 
          unset( $this->var[$key] );
        }
      }
		}
    return $this;
	}

  /** 
  * Exclude keys from
  * @param variadic argument $keys
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
        }
      }  
    }
    return $this;
  }

  /**
  * @method (s) that operate and return an array immediately
  */

  /**
  * @param Array $k_v
  * @example Array $k_v = [ 'key' => 'value' ]
  * @return Arrays
  */


  public function whitelist(Array $whitelist): Array
  {
    $new = [];
    foreach ($this->var as $key => $value) {
      $new [] = array_intersect_key($value, array_flip($whitelist));
    }
    return $new;
  }

  public function search(Array $k_v )
  {
    $new = [];
    foreach ($this->var as $key => $value) {
      foreach ($k_v as $k => $v) {
        if ( isset($value[$k]) && $value[$k] == $v ) {
          $new[] = $value;
        }
      }
    }
    return $new;
  }

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

  public function count(): int
  {
    return count($this->var);
  }

  public function exists(string $k): bool
  {
    foreach ($this->var as $key) {
      if (isset($key[$k])) {
        return true;
      }else{
        return false;
      }
    }
  }

  /**
  * @method(s) for retrieval of data 
  */

  public function get(): Array
  {
    return $this->var;
  }

  public function return()
  {
    return $this->var;
  }

  public function serialize()
  {
    return serialize($this->var);
  }

  public function unserialize()
  {
    return unserialize($this->var);
  }

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
    $obj = [];
    foreach ($this->var as $key => $value) {
      if (is_array($value)) {
        $obj[] = $this->objectify($value);
      }else{
        $obj = (object) $value;
      }
    }
    return $obj;
  }

  private function objectify(array $arr): Array{
    $new_class = [];
    foreach ($arr as $key => $value) {
      $new_class[] = (object) $value;
    }
    return $new_class;
  }

} 