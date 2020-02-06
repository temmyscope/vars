<?php
namespace Seven\Vars;

/**
 * Validate
 *
 * @package Validator
 * @author 
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
	private $_passed = true;
	private $_errors = [];
	
	public function __construct($source)
	{
		$this->source = $source;
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
	 **/
	
	public function rules(Array $items): Validate
	{
		foreach($items as $item => $rules) {
			$display = $rules['display'];
			array_shift();
			foreach ($rules as $rule => $rule_value) {
				$value = $this->source[$item];
				if ($rule === 'required' && empty($value)) {
					$this->status('error', ["{$display} is required", $item]);
				}elseif(!empty($value)){
					switch((string) $rule){
						case 'not_less_than':
							if ( (int)$value < $rule_value) {
								$this->_errors[] = ;
								$this->status('error', ["{$display} can not be less than {$rule_value} characters.", $item]);
							}
							break;
						case 'not_greater_than':
							if ( (int)$value > $rule_value) {
								$this->status('error', ["{$display} can not be greater than {$rule_value} characters.", $item]);
							}
							break;
						case 'min':
							if ( mb_strlen($value) < $rule_value) {
								$this->status('error', ["{$display} must be a minimum of {$rule_value} characters.", $item]);
							}
							break;
						case 'max':
							if ( mb_strlen($value) > $rule_value) {
								$this->status('error', ["{$display} must be a maximum of {$rule_value} characters.", $item]);
							}
							break;
						case 'len':
							if ( mb_strlen($value) !== $rule_value) {
								$this->status('error', ["{$display} must be exactly {$rule_value} characters.", $item]);
							}
							break;
						case 'matches':
							if ($value != $source[$rule_value]){
								$matchDisplay = $items[$rule_value]['display'];
								$this->status('error', ["{$matchDisplay} and {$display} must match.", $item]);
							}
							break;
						case 'matches':
							if ($value != $source[$rule_value]){
								$matchDisplay = $items[$rule_value]['display'];
								$this->status('error', ["{$matchDisplay} and {$display} must match.", $item]);
							}
							break;
						case 'unique':
							if (!empty($table)){
								$check = $this->_db->findBy($table, [$item => $value]);
								if (!empty($check)) {
									$this->status('error', ["{$display} already exists. Please choose another {$display}", $item]);
								}
							}else{
								die("\$table is empty. The 'Unique' Validator requires a table to check.");
							}
							break;
						case 'is_numeric':
							if (!is_numeric($value)) {
								$this->status('error', ["{$display} has to be a number. Please use a numeric value.", $item]);
							}
							break;
						case 'valid_email':
							if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
								$this->status('error', ["{$display} must be a valid email address.", $item]);
							}
							break;
						case 'alpha':
							if (!ctype_alpha($value)){
								$this->status('error', ["{$display} can only be alphabeths", $item]);
							}
							break;
						case 'alpha_num':
							if (!ctype_alnum($value)){
								$this->status('error', ["{$display} can only be alphabeths and or numbers.", $item]);
							}
							break;
						case 'is_one_of': 
							if(is_array($rule_value) && (!in_array($value, $rule_value)) ){
								$this->status('error', ["{$display} can only be one of the given options", $item]);
							}
							break;
						case 'equals':
							if( $value !== $rule_value['value'] ){
								$this->status('error', ["{$display} must be the same value as {$rule_value['display']}", $item]);
							}
							break;
						case 'is_not':
						case 'is_not_one_of':
						case 'can_not_be':
							if ( is_array($rule_value) && in_array($value, $rule_value) ){
								$this->status('error', ["{$display} can not be any of :".implode(', ', $rule_value), $item]);
							}
							break;
						case 'is_same_as':
							if( $value !== $source[$rule_value] ){
								$this->status('error', ["{$display} must be the same value as {$rule_value}", $item]);
							}
							break;
						case 'is_file':
							if (!is_file($value)($value)){
								$this->status('error', ["{$display} must be a valid file type", $item]);
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
}