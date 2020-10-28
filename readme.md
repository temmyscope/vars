## About SevenVars

	=> Simple Variable library Package For Validating, Encoding and Manipulating Strings and Arrays.

	=> Seven Vars is developed by Elisha Temiloluwa a.k.a TemmyScope	

	=> Seven Vars is a library that comes with a Validation Class, 
	Arrays Manipulation Class and Strings Generation, Encoding, Sanitization & Manipulation Class

	=> The Arrays library of the SevenVars Package implements the Countable, 
	ArrayAccess & Serializable interface(s) of the Standard PHP Library and provides 
	you a plethora of methods to manipulate an array of arrays i.e. a level II deep array.

	=> The Strings Class allows developers encode, manipulate, sanitize and generate truly random strings.

	=> The Validation Class takes in an array and rules for validating whether the value assigned to each key
	in the array is a valid entry. 


#### Installation
###
```bash
composer require sevens/vars
```

### Usage: Seven\Vars\Arrays HOW-TO
##

***In order for the library to generate expected outputs, always initialize with a valid array of arrays***
***Completely unit tested***


	- Valid Input Sample

```php
$data = [
  [
   'name' => 'Random 1',
   'age' => 24, 'password' => 'gHAST_V43SS',
   'nickname' => 'dick & harry'
  ],
  [
   'name' => 'Random 2',
   'age' => 27, 'password' => 'gHAST0SVSS',
   'nickname' => 'harry'
  ],
  [
   'name' => 'Random 3',
   'age' => 24, 'password' => 'gHASTS*VSS',
   'nickname' => 'dick'
  ],
  [
   'name' => 'Random 4',
   'age' => 21, 'password' => 'gHASTSV#SS',
   'nickname' => 'choudharry'
  ]
];
```

***Note: All methods and operations available to objects of the Countable, ArrayAccess and Serializable interfaces 
are implemented/declared exactly as defined.***

	- Initialization

```php
use Seven\Vars\Arrays;

$arrays = new Arrays($data) || Arrays::init($data): Arrays;
```

	- Safe Initialization: From an array of objects E.g. Laravel's Collection

***You can always use the safe Initialization when you are not sure of what
type of data is contained in your array i.e. array of arrays OR array of objects***

```php
use Seven\Vars\Arrays;

$arrays = Arrays::safeInit($data): Arrays;
```

	- Adding an array to the existing arrays

```php
$arrays->add([
 'name' => 'Random 5',
 'age' => 23,
 'nickname' => 'newbie'
]): Arrays;
```

	- Merging array of arrays to the existing one

```php
$arrays->addEach([
 [ 'name' => 'Random 6', 'age' => 22, 'nickname' => 'newb1e'],
 [ 'name' => 'Random 7', 'age' => 28, 'nickname' => 'newb6e']
]): Arrays;
```

	- Adding an array to each array in the existing array of arrays

```php
$arrays->addToEach([
 [ 'name' => 'Random 6', 'age' => 22, 'nickname' => 'newb1e'],
 [ 'name' => 'Random 7', 'age' => 28, 'nickname' => 'newb6e']
]): Arrays;
```

	- Pop off and return the last array

```php
$arrays->pop(): array;
```

	- Pop off and return an array containing the last key => value in each array

```php
$arrays->popEach(): array;
```

	- Shift off and return the first array

```php
$arrays->shift(): array;
```

	- Shift off and return an array containing the first key => value in each array

```php
$arrays->shiftEach(): array;
```

	- Sort based on key: sorts the arrays based on the value attached to the passed key
	 in ascending order: from smallest to highest

```php
$arrays->sort('name'): Arrays;
```

	- Sort based on key: sorts the arrays based on the value attached to the passed key
	 in ascending order: from highest to smallest.

```php
$arrays->sort('name'): Arrays;
```

	- Sort based on key: sorts the arrays based on the value attached to the passed key
	 in ascending order: from highest to smallest.

```php
$arrays->downSort('name'): Arrays;
```

	- Last: get the last array from the arrays

```php
$arrays->last(): array;
```

	- First: get the first array from the arrays

```php
$arrays->first(): array;
```

	- apply a callable on all the arrays using their keys: alias => map()
***An array reference will be passed to your callable and your callable must 
return the array, else all values will be NULL***

```php
$arrays->apply(function($array): array{
 $array['age'] = $array['age'] * 2;
 return $array;
}): Arrays;
```

	- sets a new value for each of the arrays using each of the key -> value pair passed;
    if an index is passed, only the key at that array index will be changed

```php
$arrays->set(array $param, ?int $index = null): Arrays;
```

	- renames a key in each of the arrays using each of the key -> value pair passed;
    * if an index is passed, only the key at that array index will be changed

```php
$arrays->rename(array $k_v, ?int $index = null): Arrays;
```

	- concatenates values of a particular key of multiple arrays using the passed 
	separator and saving it on the new name

```php
$arrays->concat(array $keys, string $new_name, string $separator = "_"): Arrays;
```

	- Extract key from the initial array using the key, value pair in the passed argument

```php
$arrays->extractBy(array $k_v): Arrays;
```

	- Extracts all the arrays containing the passed key

```php
$arrays->extractByKey($key): Arrays;
```

	- Extracts from all the arrays, if it contains the passed key

```php
$arrays->extractKey($key): array;
```

	- Excludes all array containing the passed key

```php
$arrays->excludeByKey(string $key): Arrays;
```

	- Exclude keys from the initial array using the key, value pair in the passed argument

```php
$arrays->excludeBy(array $k_v): Arrays;
```

	- Exclude keys from the initial array

```php
$arrays->excludeKey(...$keys): Arrays;
```

	- Returns an array of arrays from the initial array containing only the keys specified in the argument

```php
$arrays->whiteList(array $whitelist): array;
```

	- picks out random arrays
    * the set size has to be smaller than the total size of the array being processed

```php
$arrays->random(int $size): array;
```

	- search the value in a key if it contains a certain string

```php
$arrays->searchFor(mixed $search, string $key): array;
```

	- Returns an array of arrays from the initial array containing only arrays that
	values matching those of the keys specified in the argument;

```php
$arrays->search(array $k_v): array;
```


	- Returns an array of the size and start position specified

```php
$arrays->trim(int $count, int $start = 0): array;
```


	- checks if the key exists in at least one of the arrays

```php
$arrays->exists(string $k): bool;
```

	- yields a list-like array containing [index, array] like python enumerate method

```php
$arrays->enumerate(): Generator;
```

	- Returns the count of arrays

```php
$arrays->count(): int;
```
	
	- For returning the array at its current state

```php
$arrays->get();
```

	- For serializing and unserializing

```php
$arrays->serialize();

$arrays->unserialize( string $serializedData );
```

	- Return the arrays as JSON ecoded data

```php
$arrays->getJson();
```

	- Return the arrays as an iterable array of objects => equivalent to laravel's collection

```php
$arrays->getObjects();
```


### Usage: Seven\Vars\Strings HOW-TO
##

***Most methods of this class are static, hence do not require initialization***
***Non static methods in this class are used for encoding, encryption and decryption***

	- Initialization & Usage: If you'd be using the encryption & decryption feature, It's essential
	you construct the object passing necessary variables

```php
use Seven\Vars\Strings;

$string = new Strings($alg='aes-256-cfb', $salt='a3M5w/bnPIrWs889BSQ==ZnM', $iv='uosL_7pM-5qU_c4S' );

$string->encrypt($data);

$string->decrypt($encryptedData);

```

	- Check for the position of a string in another string

```php
/**
 * Checks if a string ends with a specific string
 *
 * @param string $value
 * @param string|string[] $end
 * @param bool $ignoreCase
 * @return bool
*/
public static function endsWith(string $value, $end, bool $ignoreCase = false): bool

/**
* Checks if a string starts with a specific string
*
* @param string $value
* @param string|string[] $start
* @param bool $ignoreCase
* @return bool
*/
public static function startsWith(string $value, $start, bool $ignoreCase = false): bool


/**
* Extracts a string from between two strings, the start string and the stop string
* @param string $full
* @param string $start
* @param string $stop
* @param bool $ignoreCase
* @return string
*/
public function between($full, $start, $stop, bool $ignoreCase = true): string


/**
* Checks if a string contains a specific string
*
* @param string $value
* @param string|array $contain
* @param bool $ignoreCase
* @return bool
*/
public static function contains(string $str, $contain, bool $ignoreCase = false): bool


/**
* Checks if a string matches a pattern
*
* @param string $str
* @param string $pattern => regular expression pattern
* @return bool
*/
public static function matchesPattern(string $str, string $pattern): bool


```

	- For generating truly random strings

```php


/**
* Returns a very random string over 32 characters in length
* @return string
*/
public static function randToken(): string

/**
* Returns a very random string of fixed length
* @param len
* @return string
*/
public static function fixedLengthToken(int $len): string

/**
* trims string to specified length starting from the specified offset
* It is multibyte
* @param string var
* @param int count
* @return reduced string to the specified length
*/
final public static function limit($var, $count = 2225, $offset = 0)

/**
* Returns a random that with very high entropy due to the specified string
* will never return two equal strings 
* @param string str
* @return string unique_name
*/
final public static function uniqueId(string $str): string

```

	- Several other methods to format, remove and fix HTML tags; generate time From string etc.

### Usage: Seven\Vars\Validation HOW-TO
##

***In order for the library to generate expected outputs, always initialize with a valid array.***
***The library comes in handy when dealing with arrays such as $_POST, $_GET etc.***

	- Initialization

```php
use Seven\Vars\Validation;

$validation = Validation::init($_POST);

####### OR ########

$data = [
 'name' => 'Random 1',
 'email' => 'random1@mail.com',
 'password' => 'pa33w0rd',
 'site' => 'hybeexchange.com',
 'age' => 24,
 'nickname' => 'dick & harry'
];

$validation = new Validation($data);

```

	- Validation Rules

```php

$validation->rules([

	#checks if a value was provided or not
 'name' => [ 'required' => true ],

 #checks if value is a valid email address
 'email' => [ 'email' => true ],

 #checks for minimum and maximum character length
 'password' => [ 'required' => true, 'min' => 8, 'max' => 32 ],

 #checks if value is a valid website address
 'site' => [ 'url' => true ],

 #check if value is a number, then check if it is greater than 17 and less than 60
 'age' => [ 'is_numeric' => true, 'gt' => 17, 'lt' => '60']
 
]);

```

	- Validation Passed or Failed

```php
$validation->passed(): bool;
```

	- Validation Errors
***Returns an array containing the first encountered error***
***Returns an empty array, if all the validation rules passed successfully***

```php
$validation->errors(): array;
```