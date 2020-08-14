<?php
namespace Seven\Vars;

/**
 * Validation
 *
 * @package Vars
 * @author Elisha Temiloluwa <temmyscope@protonmail.com>
 **/
class Validation
{
	/**
	 * @var Array | Object $source
	 *
	 * @var bool $_passed
	 * @var Array $_errors
	*/

	private $source;
	private $_passed = false;
	private $_errors = [];
	
	public function __construct($source)
	{
		$this->source = $source;
	}

	public static function init($source)
	{
		return new self($source);
	}

	public function valid()
	{
		return $this->_passed;
	}

	public function passed()
	{
		return $this->_passed;
	}

	/**
	 * 
	 *
	 * @return Validate object
	 * @author TemmyScope
	 * @example
	 * $validate = new Validate(Request $request);
	 * $validate->rules([
	 * 	'entry' => [ 'display' => 'entry', 'required' => true,  ]
	 * ]);
	 *
	*/
	
	public function rules(Array $items): Validation
	{
		foreach($items as $item => $rules) {
			$display = $rules['display'] ?? $item;
			array_shift($rules);
			foreach ($rules as $rule => $rule_value) {
				$value = $this->source[$item];
				if ($rule === 'required' && empty($value)) {
					$this->_errors[] = "{$display} is required";
				}elseif(!empty($value)){
					switch((string) $rule){
						case 'not_less_than':
							if ( (int)$value < $rule_value) {
								$this->_errors[] = "{$display} can not be less than {$rule_value} characters.";
							}
							break;
						case 'not_greater_than':
							if ( (int)$value > $rule_value) {
								$this->_errors[] = "{$display} can not be greater than {$rule_value} characters.";
							}
							break;
						case 'min':
							if ( mb_strlen($value) < $rule_value) {
								$this->_errors[] = "{$display} must be a minimum of {$rule_value} characters.";
							}
							break;
						case 'max':
							if ( mb_strlen($value) > $rule_value) {
								$this->_errors[] = "{$display} must be a maximum of {$rule_value} characters.";
							}
							break;
						case 'len':
							if ( mb_strlen($value) !== $rule_value) {
								$this->_errors[] = "{$display} must be exactly {$rule_value} characters.";
							}
							break;
						case 'matches':
							if ($value != $source[$rule_value]){
								$matchDisplay = $items[$rule_value]['display'];
								$this->_errors[] = "{$matchDisplay} and {$display} must match.";
							}
							break;
						case 'is_numeric':
							if (!is_numeric($value)) {
								$this->_errors[] = "{$display} has to be a number. Please use a numeric value.";
							}
							break;
						case 'is_email':
							if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
								$this->_errors[] = "{$display} must be a valid email address.";
							}
							break;
						case 'alpha':
							if (!ctype_alpha($value)){
								$this->_errors[] = "{$display} can only be alphabeths";
							}
							break;
						case 'alpha_num':
							if (!ctype_alnum($value)){
								$this->_errors[] = "{$display} can only be alphabeths and or numbers.";
							}
							break;
						case 'is_one_of': 
							if(is_array($rule_value) && (!in_array($value, $rule_value)) ){
								$this->_errors[] = "{$display} can only be one of the given options";
							}
							break;
						case 'equals':
							if( $value !== $rule_value['value'] ){
								$this->_errors[] = "{$display} must be the same value as {$rule_value['display']}";
							}
							break;
						case 'is_not':
						case 'is_not_one_of':
						case 'can_not_be':
							if ( is_array($rule_value) && in_array($value, $rule_value) ){
								$this->_errors[] = "{$display} can not be any of :".implode(', ', $rule_value);
							}
							break;
						case 'is_same_as':
							if( $value !== $this->source[$rule_value] ){
								$this->_errors[] = "{$display} must be the same value as {$rule_value}";
							}
							break;
					}
				}
			}
		}
		if(empty($this->_errors)){
			$this->_passed = true;
		}
		return $this;
	}

	public function errors(): array
	{
		return $this->_errors;
	}
}