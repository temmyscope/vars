<?php
Namespace Seven\Vars;

use Strings;

class Encoder extends Strings
{
	const ENCODE_STYLE_HTML = 0;
	const ENCODE_STYLE_JAVASCRIPT = 1;
	const ENCODE_STYLE_CSS = 2;
	const ENCODE_STYLE_URL = 3;
	const ENCODE_STYLE_URL_SPECIAL = 4;
	const DEFAULT_SALT = SALT;
	private static $salt = SALT;
	private static $URL_UNRESERVED_CHARS ='ABCDEFGHIJKLMNOPQRSTUVWXYZabcedfghijklmnopqrstuvwxyz-_.~';

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
	final static public function verify_hash($str, $hash): bool
	{
		return password_verify($str, $hash);
	}	
}