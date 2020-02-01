<?php
Namespace Seven\Vars;

use \Countable;
use \ArrayObject;
use \SplFixedArray;

class Arrays Implements Countable//, Iterator
{
	/**
	*@property var
	*@property tmp
	*/
	protected $var, $tmp = [];
  protected $position;

	public function __construct(Array $arr = [])
	{
		$this->var = $arr; //SplFixedArray::fromArray()
	}
 	
	final public function sanitize(): Arrays{
  	$clean_input = [];
    foreach ($this->var as $k => $v) {
      if ($v != '') $clean_input[$k] = htmlentities($v, ENT_QUOTES, 'UTF-8');
    }
    return new Arrays($clean_input);
  }

  public function pop()
  {
    $new = array_pop($this->var);
    return new Arrays( $new );
  }

  public function shift()
  {
    $new = array_shift($this->var);
    return new Arrays( $new );
  }

  public function sort($key)
  {
    $new = usort($this->var, 
      function ($item1, $item2) use ($key) {
        return $item1[$key] <=> $item2[$key];
    });
    return new Arrays( $new );
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
  */
	public function apply(Callable $fn, $to, ...$_keys)
	{
    $new = $this->var;
    foreach($new as $key => $value){
      $params = [];
      foreach ($_keys as $k => $v) {
        $params[] = $value[$v];
      }array_walk($_keys, function(){
        return ;
      });
      /*
      $params = array_map(function($_key){
        return $value[$_key];
      }, $_keys);
      */
      $value[$to] = call_user_func_array($fn, $_keys);
      //$value[$to] = call_user_func_array($fn, $params);
      //(function(...$args){ return $fn($args); })($_keys);
    }
    return new Arrays( $new );
	}

  public function whitelist(Array $whitelist): Arrays
  {
    $new = [];
    foreach ($this->var as $key => $value) {
      $new [] = array_intersect_key($value, array_flip($whitelist))
    }
    return new Arrays($new);
  }

  public function merge(array $keys, string $new_name){
    $new = $this->var;
		foreach ($new as $k => &$value) {
			$value[$new_name] = [];

			foreach ($keys as $key) {
				$value[$new_name][] = $value[$key];
			}

		}
		return new Arrays( $new );
	}

  /**
  * @param Array $k_v
  * @example Array $k_v = [ 'key' => 'value' ]
  * @return Arrays
  */

	public function search(Array $k_v ): Arrays
	{
		$new = [];
		foreach ($this->var as $key => $value) {
			while ( list($k, $v) = each($k_v) ) {
				if ( isset($value[$k]) && $value[$k] == $v ) {
					$new[] = $value;
				}
			}
		}
		return new Arrays( $new );
	}

	public function extract_by_key($key)
	{
		$new = [];
		foreach($this->var as $k => $value){
  		if(array_key_exists( $key, $value )){
	 	   	$new[] = $value;
		  }
	  }
    return new Arrays($new);
	}

	public function extract_key($key)
	{
		$new = [];
		foreach ($this->var as $k => $value) {
			if ( array_key_exists($key, $value) ) {
				$new[] = $value;
			}
		}
    return new Arrays( $new );
	}

	public function exclude_by_key(string $key): Arrays
	{
    $new = $this->var;
		foreach($new as $k => $v){
			if(array_key_exists($key, $v)){
				unset($new[$k]);
			}
	  }
    return new Arrays( $new );
	}

  /**
  * @param Array $k_v
  * @example  $k_v = [ 'key' => 'value' ]
  */
	public function exclude_by(Array $k_v ): Arrays
	{
    $new = $this->var;
		foreach ($new as &$key) {
      foreach ($k_v as $one){ 
        [$k, $v] = $one;
        if ( isset($key[$k]) && $key[$k] == $v ) { 
          unset( $key[$k] );
        }
      }
		}
    return new Arrays($new);
	}

  /** 
  * Exclude keys from
  * @param variadic argument $keys
  */
	public function exclude_key(...$keys): Arrays
	{
    return $this->hide($keys);
	}


  public function hide(...$keys): Arrays
  {
    $new = $this->var;
    foreach ($new as $key => &$value) {
      foreach ($keys as $k => $v) {
        if(array_key_exists($v, $value)){
          unset($value[$v]);
        }
      }  
    }
    return new Arrays($new);
  }

  public function random(int $size): Arrays
  {
    $size_list = $data = [];
    for($i=0; $i < $size; $i++){
      $size_list[] = $i;
    }
    shuffle($size_list);
    foreach ($size_list as $key => $value) {
      $data[] = $this->var[$value];
    }
    return new Arrays($data);
  }

  public function trim(int $count, int $start = 0)
  {
    $k = [];
    for($i=$start; $i < $count; $i++){ 
      $k[] = $this->var[$i];
    }
    return new Arrays($k);
  }

  /**
  * END
  */  

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
  * END
  */

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

  public function returnAll(): Array
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

  public function returnObjects()
  {
    $obj = (!empty($this->tmp)) ? $this->tmp: $this->var;
    foreach ($obj as $key => $value) {
      if (is_array($value)) {
        $obj[] = $this->objectify($value);
      }else{
        $obj = (object) $value;
      }
    }
    unset($this->tmp);
    return $obj;
  }

  private function objectify(array $arr): Array{
    $new_class = [];
    foreach ($arr as $key => $value) {
      $new_class[] = (object) $value;
    }
    return $new_class;
  }

  /**
  * END
  */
} 