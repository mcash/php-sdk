<?php
	
namespace mCASH;

/**
 * mCASH class.
 */
class mCASH {
	
	// @var mCASH Public Key
	public static $mcashPubKey = "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAyGr/0kllDmLNq8KblWJt
Ths43xqlj0q++xWdHjZKL/6Ko1/NouQsWCVhtoRvAwKWc8TKhVDfRn3an7zBnnyD
/9BXiHoN2OFfogwlY/EAHX4MbKR/0Ankqo5OPG875IpqrZJvWZ/1/NG5epuJAWYG
dxrlaS0QqueX8sl77bAA5U7CYEvUswiFQ3Fegm2xJzVYgTh81ScfPw8G+JyugxCR
C/guFdebyYqSGLRoC/A7oUrEyqUr04PSx8J1Axbp46ml0l6M9cS5e1YRyYREAB14
hxeVSYbgALaCSD+44YeN5XWgzqezocGdilNumPaQW1iVeRAgdTginTgk4rHohynp
AwIDAQAB
-----END PUBLIC KEY-----";
	
	// @var mCASH Test Public Key
	public static $mCASHPubTestKey = "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA8Pg5kMWZzX0U+ZGts6Ws
oLrI1bN5PjXzFRAPza19qYrONVxhFlJx8AQWohISL1hKVPJCMuyQKhs0/2jtWk+E
mDHXFafW+kYV7lseznj6nW49VFyxHYdQDNHgpyUA5p+lmZABbmcKGabw/Cp28vtH
im4zWBGVXnQ7UPm1peMzeuaB7L246J+ZcfLpd3trSWg2mywB23rqELzTNKi0s7cb
kS+2gk5B72q3qcaTO47rPgEVcUTB2A+jxcu6rOVFCbhQ8+JkLDPeHPDuIBQ5mAwN
XLY+3ffovc31S5cMhquiKaYYwiuxeI23AWtNV2FoD00bm4q+5XCuBGgPJf3nkNYV
eQIDAQAB
-----END PUBLIC KEY-----";
	
	// @var string Authentication level to use
	public static $ApiLevel = "KEY"; // KEY / SECRET

	// @var string API secret or key
	public static $ApiSecret;
	
	// @var string Public Key
	public static $ApiPublicKey;
	
	// @var string The mCASH Merchant ID
	public static $MerchantId;
	
	// @var string the mCASH User ID
	public static $UserID;
	
    // @var string The base URL for the  API.
    public static $apiBase = 'https://api-dot-paymentsdoneright.appspot.com/merchant/v1'; //'https://api.mca.sh/merchant/v1'
    
    // @var string the base URL for the test API
    public static $testApiBase = 'https://mcashtestbed.appspot.com/merchant/v1';

    // @var string|null The version of the API to use for requests.
    public static $apiVersion = null;
    
    // @var boolean Wether to use production or test api
    public static $live = true;
    
    // @var string Testtoken to be used when using the test api
    public static $testToken;
	
    const VERSION = 1;

	/**
	 * setTestEnvironment function.
	 * 
	 * Accepts a boolean parameter. Used to switch between live and test mode. 
	 * If boolean true is set, mCASH::setTestToken() needs to be called aswell, passing along the test token.
	 *
	 * @access public
	 * @static
	 * @param boolean $test
	 * @return void
	 */
	public static function setTestEnvironment( $test ){
		return self::$live = !$test;
	}
	
	/**
	 * setTestToken function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $token
	 * @return void
	 */
	public static function setTestToken( $token ){
		self::$testToken = $token;
	}

    /**
     * getApiLevel function.
     * 
     * @access public
     * @static
     * @return string
     */
    public static function getApiLevel()
    {
        return self::$ApiLevel;
    }

    /**
     * setApiLevel function.
     * 
     * Accepts a string parameter defining the authorization method. (KEY, SECRET, OPEN)
     * If Key or Secret are being used, mCASH::setApiSecret() needs to be used to set the rsa key or password aswell
     *
     * @access public
     * @static
     * @param string $apiLevel
     * @return void
     */
    public static function setApiLevel($apiLevel)
    {
	    $allowed = array( 'KEY', 'SECRET', 'OPEN' );
        if( !in_array( $apiLevel, $allowed ) ) throw new Error\Api( "Provided API Authentication Level ({$apiLevel}) is not valid" );
        self::$ApiLevel = $apiLevel;
    }

    /**
     * getApiSecret function.
     * 
     * @access public
     * @static
     * @return string
     */
    public static function getApiSecret()
    {
        return self::$ApiSecret;
    }

    /**
     * setApiSecret function.
     * 
     * @access public
     * @static
     * @param mixed $apiSecret
     * @return void
     */
    public static function setApiSecret($apiSecret)
    {
        self::$ApiSecret = $apiSecret;
    }
    
    /**
     * getApiPublicSecret function.
     * 
     * @access public
     * @static
     * @return string
     */
    public static function getApiPublicKey()
    {
        return self::$ApiPublicKey;
    }

    /**
     * setApiPublicSecret function.
     * 
     * @access public
     * @static
     * @param mixed $apiSecret
     * @return void
     */
    public static function setApiPublicKey($pubkey)
    {
        self::$ApiPublicKey = $pubkey;
    }    

    /**
     * getMerchantId function.
     * 
     * @access public
     * @static
     * @return string
     */
    public static function getMerchantId()
    {
        return self::$MerchantId;
    }

    /**
     * setMerchantId function.
     * 
     * @access public
     * @static
     * @param mixed $merchantId
     * @return void
     */
    public static function setMerchantId($merchantId)
    {
        self::$MerchantId = $merchantId;
    }

    /**
     * getUserId function.
     * 
     * @access public
     * @static
     * @return string
     */
    public static function getUserId()
    {
        return self::$UserID;
    }

    /**
     * setUserId function.
     * 
     * @access public
     * @static
     * @param mixed $userId
     * @return string
     */
    public static function setUserId($userId)
    {
        self::$UserID = $userId;
    }  
    
    /**
     * defaultHeaders function.
     * 
     * @access public
     * @static
     * @return Utilities\Headers
     */
    public static function defaultHeaders(){
	    
	    $headers = array(
		    'api_key' => self::getApiSecret(),
		    'api_level' => self::getApiLevel(),
		    'merchant_id' => self::getMerchantId(),
		    'user_id' => self::getUserId(),		    
	    );
	    
	    if( !self::$live ) $headers['test_token'] = self::$testToken;
	    
	    return Utilities\Headers::parse($headers);
	    
    }  
    	
}	
	
?>