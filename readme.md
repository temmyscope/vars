## About SevenVars
Simple Variable library Package For Encoding and Manipulating Strings and Arrays.

=> SevenVars is developed by Elisha Temiloluwa a.k.a TemmyScope	

The Arrays library of the SevenVars Package implements the in-built spl countable interface and provides you a plethora of methods to manipulate a level II deep array, i.e. an array of arrays.

Please Note that version 1.1.0 removes the Encoder class; all its methods are now part of the Strings Class

Seven\Vars\Arrays HOW-TO

```bash
=>apply(Callable $fn, $to, ...$_keys): it applies a function to a certain key or keys and stores it on the 'to' key.

=>extract_*() for extracting arrays that contain certain key(s) or values

=>exclude_*() for excluding arrays that contain certain key(s) or values
```

# use case and sample array type

```php
use Seven\Vars\Arrays;

$users = [
  ['name' => 'Alice', 'age' => 20],
  ['name' => 'Bobby', 'age' => 22],
  ['name' => 'Carol', 'age' => 17],
  ['name' => 'Elish', 'age' => 19 ]
];
$class = new Arrays($users); // or Arrays::init($users);

$var= $class->apply( function($age){ 
  return $age * 0.5;
}, 'age', 'age');

$class->apply(function($v){
  return htmlentities($v, ENT_QUOTES, 'UTF-8');
}, 'name', 'name');


var_dump( $class->add( [ 'name' => 'Debby', 'age' => 15 ] ) );

var_dump( $class->sort('age')->return() );

var_dump( $class->down_sort('age')->return() );

```