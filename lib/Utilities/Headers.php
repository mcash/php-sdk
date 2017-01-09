<?php

namespace mCASH\Utilities;

/**
 * Headers class.
 */
class Headers {
	
	/**
	 * Headers
	 * 
	 * @var mixed
	 * @access public
	 */
	public $Headers;

	/**
	 * ApiKey
	 * 
	 * @var mixed
	 * @access public
	 */
	public $ApiKey;

	/**
	 * ApiLevel
	 * 
	 * @var mixed
	 * @access public
	 */
	public $ApiLevel;
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @param mixed $key (default: null)
	 * @param mixed $level (default: null)
	 * @param array $headers (default: array())
	 * @return void
	 */
	public function __construct( $key = null, $level = null, $headers = array() ){
		$this->ApiKey = $key;
		$this->ApiLevel = $level;
		$this->Headers = $headers;
	}

    /**
     * Unpacks an options array into an Headers object
     * @param array|null $options a key => value array
     *
     * @return Headers
     */
    public static function parse($options){
        if ($options instanceof self) {
            return $options;
        }

        if (is_null($options)) {
            return new Headers(null, array());
        }

        if (is_array($options)) {
            $headers = array();
            $key = null;
            if (array_key_exists('api_key', $options) && array_key_exists('api_level', $options) ) {
                $key = $options['api_key'];
                $level = $options['api_level'];
                $headers['Authorization'] = 'RSA-SHA256 ' . $key;
            }
            if (array_key_exists('merchant_id', $options)) {
                $headers['X-Mcash-Merchant'] = $options['merchant_id'];
            }
            if (array_key_exists('user_id', $options)) {
                $headers['X-Mcash-User'] = $options['user_id'];
            }          
            if (array_key_exists('test_token', $options)) {
                $headers['X-Testbed-Token'] = $options['test_token'];
            }                        
            return new Headers($key, $level, $headers);
        }

    }
    
    /**
     * getallheaders function.
     * 
     * @access public
     * @static
     * @return void
     */
    public static function getallheaders(){ 
        foreach($_SERVER as $K=>$V){$a=explode('_' ,$K); 
            if(array_shift($a)=='HTTP'){ 
                array_walk($a,function(&$v){$v=ucfirst(strtolower($v));});
                $retval[join('-',$a)]=$V;
            }
        }
        if(isset($_SERVER['CONTENT_TYPE'])) $retval['Content-Type'] = $_SERVER['CONTENT_TYPE'];
        if(isset($_SERVER['CONTENT_LENGTH'])) $retval['Content-Length'] = $_SERVER['CONTENT_LENGTH'];
        return $retval;
    }
    
    /**
     * request_headers function.
     * 
     * @access public
     * @static
     * @return void
     */
    public static function request_headers(){
        if( function_exists('apache_request_headers') ) {
            return apache_request_headers();
        } else {
            return self::getallheaders();
        }
    }
    
	
}
	
?>