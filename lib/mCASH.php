<?php
	
namespace mCASH;

/**
 * mCASH class.
 */
class mCASH {
	
	// @var string Authentication level to use
	public static $ApiLevel = "KEY"; // KEY / SECRET

	// @var string API secret or key
	public static $ApiSecret;
	
	// @var string The mCASH Merchant ID
	public static $MerchantId;
	
	// @var string the mCASH User ID
	public static $UserID;
	
    // @var string The base URL for the  API.
    public static $apiBase = 'https://api.mca.sh/merchant/v1';
    
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