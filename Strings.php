<?php
Namespace Seven\Vars;

use \DateTime;
use \DateTimeZone;
use StringsInterface;

class Strings Implements StringsInterface
{
 	/**
     * Checks if a string starts with a specific string
     *
     * @param string $value
     * @param string|string[] $start
     * @param bool $ignoreCase
     * @return bool
    */
    public static function startsWith(string $value, $start, bool $ignoreCase = false): bool
    {
        if ($ignoreCase) {
            $value = mb_strtolower($value);
        }
        $start = is_array($start) ? $start : [$start];
        foreach ($start as $val) {
            if ($ignoreCase) {
                $val = mb_strtolower($val);
            }
            if (mb_substr($value, 0, mb_strlen($val)) == $val) {
                return true;
            }
        }
        return false;
    }
    /**
     * Checks if a string ends with a specific string
     *
     * @param string $value
     * @param string|string[] $end
     * @param bool $ignoreCase
     * @return bool
    */
    public static function endsWith(string $value, $end, bool $ignoreCase = false): bool
    {
        if ($ignoreCase) {
            $value = mb_strtolower($value);
        }
        $end = is_array($end) ? $end : [$end];
        foreach ($end as $val) {
            if ($ignoreCase) {
                $val = mb_strtolower($val);
            }
            if (mb_substr($value, -mb_strlen($val)) == $val) {
                return true;
            }
        }
        return false;
    }

 	final public static function sanitize(String $dirty): String{
    	return htmlentities($dirty, ENT_QUOTES, 'UTF-8');
  	}

  	final public function match(string $var1, string $var2) : bool{
		return ($var1 == $var2) ? true : false;
	}

	final public static function is_Alnum($var): bool{
		return ctype_alnum($var);
	}

	final public static function is_Url($var): bool{
		return filter_var($var, FILTER_VALIDATE_URL) ? true : false;
	}

	final public static function is_Email($var): bool{
		return filter_var($var, FILTER_VALIDATE_EMAIL) ? true : false;
	}

	final static public function rand($len = 0){
		$chars = 'qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPZXCVBNMLKJHGFDSA';
		return substr(str_shuffle(($chars)), 0, ($len == 0) ? random_int(6, 64) : $len );
	}

	public static function rand_token(): string
	{
    	return base64_encode(openssl_random_pseudo_bytes(random_int(32, 256)))
	}

	public static function fixed_length_token(int $len): string
	{
    	return base64_encode(openssl_random_pseudo_bytes($len))
	}

	/**
	* @param <string> Time string
	* @param <string> TimeZone
	*/

	public static function time_from_string($str = 'now', $tz = 'UTC'): string{
		$var = new DateTime($time_str, new DateTimeZone($tz));
		return $var->format('Y-m-d H:i:s');
	}

	public static function time_to_string($time): string{
		$current_time = time();
		$time = strtotime($time);	

		if ($current_time > $time) {
			if($current_time < ( $time + 60)){
				// the update was in the past minute
				return "just now"; //"less than a minute ago";
			}elseif( $current_time < ( $time + ( 60*60 ) ) ){
				// it was less than 60 minutes ago: so say X minutes ago
				return round( ( $current_time - $time ) / 60 ) . " minutes ago";
			}elseif( $current_time < ( $time + ( 60*120 ) ) ){
				// it was more than 1 hour
				return "just over an hour ago";
			}elseif( $current_time < ( $time + ( 60*60*24 ) ) ){
				//it was in the last day:X hours
				return round( ( $current_time - $time ) / (60*60) ) . " hours ago";
			}elseif( $current_time > ( $time + ( 60*60*24) ) && $current_time < ( $time + ( 60*60*24*2) )){
				//it was in the last month: X days
				return " about a day ago";
			}elseif( $current_time < ( $time + ( 60*60*24*28 ) )){
				//it was in the last month: X days
				return round( ( $current_time - $time ) / (60*60*24) ) . " days ago";
			}elseif ($current_time > ( $time + ( 60*60*24*56 ) ) && $current_time < ( $time + ( 60*60*24*365 ) )) {
				//over 2 months at least
				return 'about '.round( ( $current_time - $time ) / (60*60*24*28) ) . " months ago";
			}else{
				// longer than a day ago: give up, and display the date
				return "" . date('jS \o\f M, Y', $time);
			}
		}else{
			if ($time < ( $current_time + 60)) {
				return "in a minute";
			} elseif ($time < ( $current_time + ( 60*60 ) ) ) {
				return "in a couple of minutes";
			} elseif (  $time < ( $current_time + ( 60*120 )) ) {
				return "in the next hour";
			} elseif ( $time < ( $current_time + ( 60*60*12 )) ) {
				return "in a couple of hours";
			} elseif ( $time < ( $current_time + ( 60*60*23.5 )) ) {
				return "within the next day";
			} elseif ( $time > ( $current_time + ( 60*60*24 )) && ( $time < ( $current_time + ( 60*60*24*28 )) ) ) {
				return "within the next ".round( ( $time - $current_time ) / (60*60*24) )." days";
			}else {
				return "soon";
			}	
		}
	}

}