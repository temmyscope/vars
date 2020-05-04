<?php
Namespace Seven\Vars;

use \DateTime;
use \DateTimeZone;

class Strings
{
	const ENCODE_STYLE_HTML = 0;
	const ENCODE_STYLE_JAVASCRIPT = 1;
	const ENCODE_STYLE_CSS = 2;
	const ENCODE_STYLE_URL = 3;
	const ENCODE_STYLE_URL_SPECIAL = 4;
	private static $URL_UNRESERVED_CHARS ='ABCDEFGHIJKLMNOPQRSTUVWXYZabcedfghijklmnopqrstuvwxyz-_.~';

	/**
	* @param string alg, is the encryption algorithm to be used ()
	* @param string salt, is a 32 character-long string for encrypting your data  
	* @param string iv is a base64 encoded : base64_encode( openssl_random_pseudo_bytes( openssl_cipher_iv_length() ));
	*/
	public function __construct($alg, $salt, $iv){
		$this->alg = $alg;
		$this->salt = $salt;
		$this->iv = $iv;
	}

	public static function init($alg, $salt, $iv)
	{
		return new self($alg, $salt, $iv);
	}
	
	/** 
	*  @param string data to be encrypted
	*/
	public function encrypt($data){
		return @base64_encode( openssl_encrypt($data, $this->alg, $this->salt, $options = 0, $this->iv, $tag = NULL) );
		//return base64_encode( openssl_encrypt($data, $this->alg, $this->salt) );
	}

	/** 
	*  @param string data to be decrypted
	*/
	public function decrypt($encrypted){
		return openssl_decrypt(  base64_decode($encrypted),  $this->alg, $this->salt, $options = 0, $this->iv, $tag = NULL );
		//return openssl_decrypt( base64_decode($encrypted),  $this->alg, $this->salt);
	}

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

    /**
     * Checks if a string contains a specific string
     *
     * @param string $value
     * @param string|array $contain
     * @param bool $ignoreCase
     * @return bool
    */
    public static function contains(string $str, $contain, bool $ignoreCase = false): bool
    {
    	if ($ignoreCase) {
            $str = mb_strtolower($str);
        }
        $contain = is_array($contain) ? $contain : [$contain];
        foreach ($contain as $val) {
            if ($ignoreCase) {
                $val = mb_strtolower($val);
            }
            if ( mb_strpos($str, $val) >= 0 ) {
                return true;
            }
        }
        return false;
    }

	/**
     * Checks if two strings match
     *
     * @param string $str1
     * @param string $str2
     * @param bool $ignoreCase
     * @return bool
    */
    public function match(string $str1, string $str2, bool $ignoreCase = false): bool
    {
    	if ($ignoreCase) {
    		$str1 = mb_strtolower($str1);
    		$str2 = mb_strtolower($str2);
    	}
    	if ( $str1 == $str2 ) {
    		return true;
    	}
    	return false;
	}
	
	/**
     * Checks if a string matches a pattern
     *
     * @param string $str
     * @param string $pattern
     * @return bool
    */
    public function match_pattern(string $str, string $pattern): bool
    {
    	if ( preg_match($pattern, $str ) === true ) {
			return true;
		}
    	return false;
	}
	
	/**
     * Returns upper case
     *
     * @param string $str
     * @return string
    */
    public function toUpper(string $str): string
    {
    	return mb_strtoupper($str);
    }
	
	/**
     * Returns lower case
     *
     * @param string $str
     * @return string
    */
    public function toLower(string $str): string
    {
    	return mb_strtolower($str);
    }

	/**
    * Sanitizes a string
	*
	* @return a sanitized string
	*/
 	final public static function sanitize(String $dirty): String{
    	return htmlentities($dirty, ENT_QUOTES, 'UTF-8');
  	}

	/**
	 * checks if a string only conttains alpha-numeric characters
	 * @return bool
	*/
	final public static function is_Alnum($var): bool{
		return ctype_alnum($var);
	}

	/**
	 * checks if a string is a valid url
	 * @return bool
	*/
	final public static function is_Url($var): bool{
		return filter_var($var, FILTER_VALIDATE_URL) ? true : false;
	}

	/**
	 * checks if a string is a valid email
	 * @return bool
	*/
	final public static function is_Email($var): bool{
		return filter_var($var, FILTER_VALIDATE_EMAIL) ? true : false;
	}

	/**
	 * Returns a string of the specified length
	 * @param int len
	 * @return string
	 */
	final static public function rand($len = 0){
		$chars = 'qwertyuiopasdfghjklzxcvbnm_-1234567890QWERTYUIOPZXCVBNMLKJHGFDSA';
		return substr(str_shuffle(($chars)), 0, (($len == 0) ? random_int(6, 64) : $len) );
	}

	/**
	 * Returns a very random string
	 * @return string
	 */	
	public static function rand_token(): string
	{
    	return base64_encode( openssl_random_pseudo_bytes(random_int(32, 256)) );
	}

	/**
	 * Returns a very random string
	 * @param len
	 * @return string
	 */
	public static function fixed_length_token(int $len): string
	{
    	return base64_encode(openssl_random_pseudo_bytes($len));
	}

	/**
	* @param string var
	* @param int count
	* @return reduced string to the specified length
	*/
	final static public function limit($var, $count = 2225)
	{
		return mb_substr($var, 0, $count);
	}

	/**
	* @param string str
	*/
	final static public function get_unique_name(string $str): string
	{
		return hash('SHA256', uniqid().microtime(true).random_bytes(8).$str);
	}

	/**
	* @param str is the string that needs hashing
	*/
	final static public function hash($str): string
	{
		return password_hash($str, PASSWORD_DEFAULT);
	}

	/**
	* @param string str
	* @param string hash
	*/
	final static public function verify_hash(string $str, string $hash): bool
	{
		return password_verify($str, $hash);
	}

	/**
	* @param <string> Time string
	* @param <string> TimeZone
	*/

	public static function time_from_string(string $str = 'now', $tz = 'UTC'): string{
		$var = new DateTime($str, new DateTimeZone($tz));
		return $var->format('Y-m-d H:i:s');
	}

	/**
	* @param <string> time
	* @return <string> 
	*/
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

	final static public function Linkify(string $str): string{
		$url = '@(http)?(s)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
		$string = preg_replace($url, "<a href='http$2://$4' target='_blank' rel='noopener' title='$0'>$0</a>", $str);
		return $string;
	}

	final static public function removeHTML($str){
		$string = preg_replace ('/<[^>]*>/', ' ', $str);     
	    return $string;
	}

	public function encodeForHTML($value){
		$value = str_replace('&', '&amp;', $value);
		$value = str_replace('<', '&lt;', $value);
		$value = str_replace('>', '&gt;', $value);
		$value = str_replace('"', '&quot;', $value);
		$value = str_replace('\'', '&#x27;', $value); // &apos; is not recommended
		$value = str_replace('/', '&#x2F;', $value); // forward slash can help end HTML entity
		return $value;
	}

	public function encodeForHTMLAttribute($value){
		return $this->_encodeString($value);
	}

	public function encodeForJavascript($value){
		return $this->_encodeString($value, self::ENCODE_STYLE_JAVASCRIPT);
	}

	public function encodeForURL($value){
		return $this->_encodeString($value, self::ENCODE_STYLE_URL_SPECIAL);
	}

	public function encodeForCSS($value){
		return $this->_encodeString($value, self::ENCODE_STYLE_CSS);
	}
	
	/**
	* Encodes any special characters in the path portion of the URL. Does not
	* modify the forward slash used to denote directories. If your directory
	* names contain slashes (rare), use the plain urlencode on each directory
	* component and then join them together with a forward slash.
	*
	* Based on http://en.wikipedia.org/wiki/Percent-encoding and http://tools.ietf.org/html/rfc3986
	*/
	public function encodeURLPath($value){
		$length = mb_strlen($value);
		if ($length == 0) {
			return $value;
		}
		$output = '';
		for ($i = 0; $i < $length; $i++) {
			$char = mb_substr($value, $i, 1);
			if ($char == '/') {
			// Slashes are allowed in paths.
			$output .= $char;
			}else if (mb_strpos(self::$URL_UNRESERVED_CHARS, $char) == false) {
				// It's not in the unreserved list so it needs to be encoded.
				$output .= $this->_encodeCharacter($char, self::ENCODE_STYLE_URL);
			}else {
				// It's in the unreserved list so let it through.
				$output .= $char;
			}
		}
		return $output;
	}

	private function _encodeString($value, $style = self::ENCODE_STYLE_HTML){
		if (mb_strlen($value) == 0) {
			return $value;
		}
		$characters = preg_split('/(?<!^)(?!$)/u', $value);
		$output = '';
		foreach ($characters as $c) {
			$output .= $this->_encodeCharacter($c, $style);
		}
		return $output;
	}

	private function _encodeCharacter($c, $style = self::ENCODE_STYLE_HTML){
		if (ctype_alnum($c)){
			return $c;
		}
		if(($style === self::ENCODE_STYLE_URL_SPECIAL) && ($c == '/' || $c == ':')) {
			return $c;
		}
		$charCode = $this->_unicodeOrdinal($c);
		$prefixes = array(
			self::ENCODE_STYLE_HTML => array('&#x', '&#x'),
			self::ENCODE_STYLE_JAVASCRIPT => array('\\x', '\\u'),
			self::ENCODE_STYLE_CSS => array('\\', '\\'),
			self::ENCODE_STYLE_URL => array('%', '%'),
			self::ENCODE_STYLE_URL_SPECIAL => array('%', '%'),
		);
		$suffixes = array(
			self::ENCODE_STYLE_HTML => ';',
			self::ENCODE_STYLE_JAVASCRIPT => '',
			self::ENCODE_STYLE_CSS => '',
			self::ENCODE_STYLE_URL => '',
			self::ENCODE_STYLE_URL_SPECIAL => '',
		);
		// if ASCII, encode with \\xHH
		if ($charCode < 256) {
			$prefix = $prefixes[$style][0];
			$suffix = $suffixes[$style];
			return $prefix . str_pad(strtoupper(dechex($charCode)), 2, '0') . $suffix;
		}
		// otherwise encode with \\uHHHH
		$prefix = $prefixes[$style][1];
		$suffix = $suffixes[$style];
		return $prefix . str_pad(strtoupper(dechex($charCode)), 4, '0') . $suffix;
	}

	private function _unicodeOrdinal($u){
		$c = mb_convert_encoding($u, 'UCS-2LE', 'UTF-8');
		$c1 = ord(substr($c, 0, 1));
		$c2 = ord(substr($c, 1, 1));
		return $c2 * 256 + $c1;
	}

	public static function toUTF8($str): String{
		return (!mb_check_encoding($str, 'UTF-8')) ? mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str)) : $str;
	}

}