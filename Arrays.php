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


  public function count(): int
  {
    return count($this->var);
  }

  public function last(): Arrays
  {
    return new Arrays( end($this->var) );
  }

  public function first(): Arrays
  {
    return new Arrays( $this->var[0] );
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
  * @param Callable (array | Closure) $fn
  * @example [$object, method] OR function() use {}
  */
	public function apply($to_key, Callable $fn)
	{
    foreach ($this->var as &$k) {
      $k[$to_key] = call_user_func_array($fn, $k[$to_key]);
    }
    /**
		foreach ($this->var as $k => $v) {
			
		}
    array_walk($this->var, function(&$value[$to_key], $key) {
     //apply action on $value[$to_key];
    });
    */
    return $this;
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
		foreach ($this->var as &$k) {
			$k[$new_name] = '';
			foreach ($keys as $key) {
				$k[$new_name] .= $k[$key]." ";
			}
			rtrim($k[$new_name], $separator);
		}
		return $this;
	}

  private function objectify(array $arr): Array{
		$new_class = [];
		foreach ($arr as $key => $value) {
			$new_class[] = (object) $value;
		}
		return $new_class;
	}

  /**
  * @param Array $k_v
  * @example
  * <pre>
  * $k_v = [ 'key' => 'value' ]
  * </pre>
  */

	public function search(Array $k_v ): ArrayObject
	{
		$this->tmp = [];
		foreach ($this->var as $key => $value) {
			while ( list($k, $v) = each($k_v) ) {
				if ( isset($key[$k]) && $key[$k] == $v ) {
					$this->tmp[] = $this->var[$key]; // $this->tmp[] = current($this->var);
				}
			}
		}
		return new ArrayObject($this->tmp);
	}

	public function extract_by_key($key)
	{
		$this->tmp = [];
		foreach($this->var as &$k){
  		if(array_key_exists($key, $k)){
	 	   	$this->tmp[] = $k[$key];
		  }
	  }
		return $this->tmp;
	}

	public function extract_key($key)
	{
		$new = [];
		foreach ($this->var as $k) {
			if ( array_key_exists($key, $k) ) {
				$new[] = $k[$key]; // $this->tmp[] = current($this->var);
			}
		}
		return $this->tmp;
    return new Arrays($new);
	}

	public function exclude_by_key(string $key): Arrays
	{
    $new = $this->var;
		foreach($new as $k => $v){
			if(array_key_exists($key, $v)){
				unset($new[$k]);
			}
	  }
    return new Arrays($new);
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
      /*
      while ( list($k, $v) = each($k_v) ) {
				if ( $key[$k] == $v ) {
					unset($new[$key]); //unset( current($new) );
				}
			}
      */
		}
    return new Arrays($new);
	}

	public function exclude_key(Array $keys): Arrays
	{
    $new = $this->var;
		foreach ($new as $k => $v) {
      foreach ($keys as $one_key) {
        if( isset($v[$one_key]) ){
          unset( $v[$key] );
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

  /**
  * @method(s) for retrieval of data 
  */

  public function get(): Array
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

  public function return(int $count, int $start = 0)
  {
    $k = []; 
    $counter = 0;
    foreach ($this->var as $key => $value) {
      if( $key >= $start && $counter <= $count) {
        $k[] = $value;
      }elseif ( $key > ($start + $count) ) {
        break;
      }
      $counter++;
    }
    return $k;
    /* alternative means
    for($i=$start; $i < $count; $i++){ 
      $k[] = $this->var[$i];
    }
    */
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



/*
  public function next()
  {
    return next($this->var);
  }

  public function key($value='')
  {
    key(array);
  }

  public function rewind($value='')
  {
    
  }

  public function valid($key)
  {
    return ( null !== $ );
  }

  public function current(): Iterator
  {
    return current($this->var);
  }

  public function depth(): int
	{
		$depth = 0;
		while ( list($k, $v) = each($this->var) ) {
			while ( key($v) !== null ) {
				$depth += 1;
			}
		}
		return $depth;
	}
*/
} 