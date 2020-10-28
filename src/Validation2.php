<?php

namespace Seven\Vars;

/**
 * Validation
 *
 * @package Vars
 * @author Elisha Temiloluwa <temmyscope@protonmail.com>
 **/

use Seven\Vars\Strings;

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
     * @method Validation rules
     * @example
     * $validate = new Validate(Request $request);
     * $validate->rules([
     *  'entry' => [ 'display' => 'entry', 'required' => true,  ]
     * ]);
     *
    */

    public function rules(array $items): Validation
    {
        foreach ($items as $item => $rules) {
            $display = $rules['display'] ?? $item;
            array_shift($rules);
            foreach ($rules as $rule => $rule_value) {
                $value = $this->source[$item];
                if( $this->$rule.'Validator'($value, $rule_value, $display) === false){
                    return $this;
                }
            }
        }
        $this->_passed = true;
        return $this;
    }

    public function errors(): array
    {
        return $this->_errors;
    }

    /**
    * @method bool gtValidator tests for greater than
    */
    protected function requiredValidator($value, $ruleValue, $display): bool
    {
        if (empty($value)) {
            $this->_errors[] = "{$display} is required";
            return false;
        }
        return true;
    }

    /**
    * @method bool gtValidator tests for greater than
    */
    protected function emailValidator($value, $ruleValue, $display): bool
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->_errors[] = "{$display} must be a valid email address.";
            return false;
        }
        return true;
    }

    /**
    * @method bool gtValidator tests for greater than
    */
    protected function urlValidator($value, $ruleValue, $display): bool
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $this->_errors[] = "{$display} must be a valid website address or url.";
            return false;
        }
        return true;
    }

    /**
    * @method bool ltValidator tests for greater than
    */
    protected function ltValidator($value, $ruleValue, $display): bool
    {
        if ((int)$value > $rule_value) {
            $this->_errors[] = "{$display} can not be greater than {$rule_value} characters.";
            return false;
        }
        return true;
    }

    /**
    * @method bool gtValidator tests for greater than
    */
    protected function gtValidator($value, $ruleValue, $display): bool
    {
        if ((int)$value < $rule_value) {
            $this->_errors[] = "{$display} can not be less than {$rule_value} characters.";
            return false;
        }
        return true;
    }

    /**
    * @method bool minValidator tests for greater than
    */
    protected function minValidator($value, $ruleValue, $display): bool
    {
        if (mb_strlen($value) < $rule_value) {
            $this->_errors[] = "{$display} must be a minimum of {$rule_value} characters.";
            return false;
        }
        return true;
    }

    /**
    * @method bool maxValidator tests for greater than
    */
    protected function maxValidator($value, $ruleValue, $display): bool
    {
        if (mb_strlen($value) > $rule_value) {
            $this->_errors[] = "{$display} must be a maximum of {$rule_value} characters.";
            return false;
        }
        return true;
    }

    /**
    * @method bool lenValidator tests for greater than
    */
    protected function lenValidator($value, $ruleValue, $display): bool
    {
        if (mb_strlen($value) !== $rule_value) {
            $this->_errors[] = "{$display} must be exactly {$rule_value} characters.";
            return false;
        }
        return true;
    }

    /**
    * @method bool matchValidator tests for greater than
    */
    protected function matchValidator($value, $ruleValue, $display): bool
    {
        if ( !Strings::matchPattern($value, $ruleValue) ) {
            $this->_errors[] = "{$display} does not match the given pattern.";
            return false;
        }
        return true;
    }

    /**
    * @method bool numericValidator tests for greater than
    */
    protected function numericValidator($value, $ruleValue, $display): bool
    {
        if (!is_numeric($value)) {
            $this->_errors[] = "{$display} has to be a number. Please use a numeric value.";
            return false;
        }
        return true;
    }

    protected function alnumValidator($value, $ruleValue, $display): bool
    {
        if (!ctype_alnum($value)) {
            $this->_errors[] = "{$display} can only be alphabeths and or numbers.";
        }
    }

    protected function alphaValidator($value, $ruleValue, $display): bool
    {
        if (!ctype_alpha($value)) {
            $this->_errors[] = "{$display} can only be alphabeths";
            return false;
        }
        return true;
    }

    protected function oneOfValidator($value, $ruleValue, $display): bool
    {
        if (is_array($rule_value) && (!in_array($value, $rule_value))) {
            $this->_errors[] = "{$display} can only be one of the given options";
        }
    }

    protected function notValidator($value, $ruleValue, $display): bool
    {
        if (is_array($rule_value) && in_array($value, $rule_value)) {
            $this->_errors[] = "{$display} can not be any of :" . implode(', ', $rule_value);
            return false;
        }
        if($value == $ruleValue){
            $this->_errors[] = "{$display} can not be {$rule_value}";
            return false;
        }
        return true;
    }

    protected function isValidator($value, $ruleValue, $display): bool
    {
        if ($value !== $rule_value['value']) {
            $this->_errors[] = "{$display} must be the same value as {$rule_value['display']}";
            return false;
        }
        return true;
    }

    protected function sameValidator($value, $ruleValue, $display): bool
    {
        if ($value !== $this->source[$rule_value]) {
            $this->_errors[] = "{$display} must be the same value as {$rule_value}";
            return false;
        }
        return true;
    }
    
}
