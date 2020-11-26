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
        return $this->passed();
    }

    public function passed()
    {
        return $this->_passed;
    }

    public function then(callable $callable)
    {
        if ($this->_passed) {
            call_user_func_array($callable, []);
        }
        return $this;
    }

    public function catch(callable $callable)
    {
        if (!$this->_passed) {
            return call_user_func_array($callable, [$this->_errors]);
        }
        return $this;
    }

    /**
     *
     * @method Validation rules
     * @example
     * $validate = new Validate(Request $request);
     * $validate->rules([
     *  'entry' => [ 'required' => true, ]
     * ]);
     *
    */

    public function rules(array $items): Validation
    {
        foreach ($items as $item => $rules) {
            $display = $item;
            foreach ($rules as $rule => $ruleValue) {
                $value = $this->source[$item];
                $method = $rule.'Validator';
                if( $this->$method($value, $ruleValue, $display) === false){
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

    protected function safeValidator($value, $ruleValue, $display): bool
    {
        if ( !Strings::isSafe($value) ) {
            $this->_errors[] = "{$display} can only contain space,-,_ and alphanumeric characters";
            return false;
        }
        return true;
    }

    protected function requiredValidator($value, $ruleValue, $display): bool
    {
        if (empty($value)) {
            $this->_errors[] = "{$display} is required";
            return false;
        }
        return true;
    }

    /**
    * @method stringValidator tests for string
    * 
    * @return bool
    */

    public function stringValidator($value, $ruleValue, $display): bool
    {
        if (!is_string($value)) {
            $this->_errors[] = "{$display} must be a string.";
            return false;
        }
        return true;
    }

    /**
    * @method emailValidator tests for valid email
    *
    * @return bool
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
    * @method urlValidator tests for valid url
    *
    * @return bool
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
    * @method ltValidator tests for lesser than
    *
    * @return bool
    */
    protected function ltValidator($value, $ruleValue, $display): bool
    {
        if ($value > $ruleValue) {
            $this->_errors[] = "{$display} can not be greater than {$ruleValue} characters.";
            return false;
        }
        return true;
    }

    /**
    * @method gtValidator tests for greater than
    * 
    * @return bool
    */
    protected function gtValidator($value, $ruleValue, $display): bool
    {
        if ($value < $ruleValue) {
            $this->_errors[] = "{$display} can not be less than {$ruleValue} characters.";
            return false;
        }
        return true;
    }

    /**
    * @method minValidator tests for minimum length
    *
    * @return bool
    */
    protected function minValidator($value, $ruleValue, $display): bool
    {
        if (mb_strlen($value) < $ruleValue) {
            $this->_errors[] = "{$display} must be a minimum of {$ruleValue} characters.";
            return false;
        }
        return true;
    }

    /**
    * @method maxValidator tests for maximum length
    *
    * @return bool
    */
    protected function maxValidator($value, $ruleValue, $display): bool
    {
        if (mb_strlen($value) > $ruleValue) {
            $this->_errors[] = "{$display} must be a maximum of {$ruleValue} characters.";
            return false;
        }
        return true;
    }

    /**
    * @method lenValidator tests for exact length
    *
    * @return bool
    */
    protected function lenValidator($value, $ruleValue, $display): bool
    {
        if (mb_strlen($value) !== $ruleValue) {
            $this->_errors[] = "{$display} must be exactly {$ruleValue} characters.";
            return false;
        }
        return true;
    }

    /**
    * @method matchValidator matches a string using regular expression
    *
    * @return bool
    */
    protected function matchValidator($value, $ruleValue, $display): bool
    {
        if ( !Strings::matchesPattern($value, $ruleValue) ) {
            $this->_errors[] = "{$display} does not match the given pattern.";
            return false;
        }
        return true;
    }

    /**
    * @method numericValidator matches a string using regular expression
    *
    * @return bool
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
            return false;
        }
        return true;
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
        if (is_array($ruleValue) && (!in_array($value, $ruleValue))) {
            $this->_errors[] = "{$display} can only be one of the given options";
            return false;
        }
        return true;
    }

    protected function notValidator($value, $ruleValue, $display): bool
    {
        if (is_array($ruleValue) && in_array($value, $ruleValue)) {
            $this->_errors[] = "{$display} can not be any of :" . implode(', ', $ruleValue);
            return false;
        }
        if($value == $ruleValue){
            $this->_errors[] = "{$display} can not be '{$ruleValue}'";
            return false;
        }
        return true;
    }

    protected function isValidator($value, $ruleValue, $display): bool
    {
        if ($value !== $ruleValue) {
            $this->_errors[] = "{$display} must be {$ruleValue}";
            return false;
        }
        return true;
    }

    protected function sameValidator($value, $ruleValue, $display): bool
    {
        if ($value !== $this->source[$ruleValue]) {
            $this->_errors[] = "{$display} must be the same value as {$ruleValue}";
            return false;
        }
        return true;
    }
    
}
