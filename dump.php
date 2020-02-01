<?php
/*
  array_walk($this->var, function(&$value[$to_key], $key) {
   //apply action on $value[$to_key];
  });

  public function merge(array $keys, string $new_name){
    $new = $this->var;
    foreach ($new as $k => &$value) {
      $value[$new_name] = '';
      foreach ($keys as $key) {
        $value[$new_name] .= $k[$key]." ";
      }
      rtrim($k[$new_name], $separator);
    }
    return $this;
  }
    
  public function exclude_by(Array $k_v ): Arrays
  {
    $new = $this->var;
    foreach ($new as &$key) {
       while ( list($k, $v) = each($k_v) ) {
        if ( $key[$k] == $v ) {
          unset($new[$key]); //unset( current($new) );
        }
      }
    }
    return new Arrays($new);
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
  public function trim(int $count, int $start = 0)
  {
    $k = []; 
    for($i=$start; $i < $count; $i++){ 
      $k[] = $this->var[$i];
    }
    return $k;
  }

  performant altenative
  public function trim(int $count, int $start = 0)
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
  }

*/
