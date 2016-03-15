<?php
	
namespace mCASH;

/**
 * Abstract Resource class.
 * 
 * @abstract
 * @extends mCASHObject
 */
abstract class Resource extends mCASHObject {
	
	/**
	 * endpointUrlAppend
	 *
	 * String that will be appended to the API instance url
	 * 
	 * @var mixed
	 * @access protected
	 * @static
	 */
	protected static $endpointUrlAppend;
	/**
	 * updateParams
	 * 
	 * (default value: array())
	 * Contains strings of allowed object values to be updated upon save
	 *
	 * 
	 * @var array
	 * @access protected
	 * @static
	 */
	protected static $updateParams = array();
	
	/**
	 * endpointUrlAppend function.
	 *
	 * Used to change the static endpointUrlAppend variable
	 * 
	 * @access public
	 * @param mixed $apnd
	 * @return void
	 */
	public function endpointUrlAppend($apnd){
		self::$endpointUrlAppend = $apnd;
	}
    /**
     * baseUrl function.
     * 
     * Returns the base url for the api based on api status (live / test).
     *
     * @access public
     * @static
     * @return string
     */
    public static function baseUrl(){
        return ( mCASH::$live ) ? mCASH::$apiBase : mCASH::$testApiBase;
    }   

    /**
     * instanceUrl function.
     *
     * Returns the full url for the instance that is called. 
     * 
     * @access public
     * @return string
     */
    public function instanceUrl(){ 
	   
        $id = $this->id;
        if ($id === null) {
            $class = get_called_class();
            $message = "Could not determine which URL to request: "
               . "$class instance has invalid ID: $id";
            throw new Error\Api($message, null);
        }
        $base = static::classUrl();
        $extn = urlencode($id);
        $apnd = static::classAppendUrl();
        return "$base$extn/$apnd";
    }       

    /**
     * classUrl function.
     *
     * Returns the api url for the current class (fallback to class name if endpointUrl is not defined)
     * 
     * @access public
     * @static
     * @return string
     */
    public static function classUrl(){
	    $stat = new static();
        $base = ( !empty( $stat->endpointUrl ) ) ? $stat->endpointUrl : static::className();
        return static::baseUrl() . "/${base}/" . self::$endpointUrlAppend;
    }

    /**
     * clasAppendsUrl function.
     *
     * Returns a string to append on the classUrl() if $endpointUrlAppend is defined in the current class
     * 
     * @access public
     * @static
     * @return string
     */
    public static function classAppendUrl(){
        $base = ( !empty( static::$endpointUrlAppend ) ) ? static::$endpointUrlAppend : "";
        return ( !empty($base) ) ? "${base}/" : "";
    }

    /**
     * className function.
     *
     * Returns a classname without namespaces 
     * 
     * @access public
     * @static
     * @return string
     */
    public static function className(){
        $class = get_called_class();
        // Useful for namespaces: Foo\Charge
        if ($postfixNamespaces = strrchr($class, '\\')) {
            $class = substr($postfixNamespaces, 1);
        }
        // Useful for underscored 'namespaces': Foo_Charge
        if ($postfixFakeNamespaces = strrchr($class, '')) {
            $class = $postfixFakeNamespaces;
        }
        $name = urlencode($class);
        $name = strtolower($name);
        return $name;
    }    

    /**
     * refresh function.
     *
     * Reloads the current instance from the mCASH Api
     * 
     * @access public
     * @return mCASHObject|static
     */
    public function refresh(){
	    
        $requestor = new Requestor(mCASH::getApiSecret(), static::baseUrl());
        	
        $url = $this->instanceUrl();

        list($response, $this->_opts->apiKey) = $requestor->request(
            'get',
            $url,
            $this->_retrieveOptions,
			array_merge( mCASH::defaultHeaders()->Headers, Utilities\Headers::parse( $opts )->Headers )
        );
        
        $this->refreshFrom(json_decode( $response ), $this->_opts);
        return $this;
    }

    /**
     * _validateParams function.
     * 
     * Function used to validate that a params array have been passed
     * 
     * @access private
     * @static
     * @param mixed $params (default: null)
     * @return void
     *
     * Throws Error\Base
     */
    private static function _validateParams($params = null){
        if ($params && !is_array($params)) {
            throw new Error\Base( "You must pass a array as parameter" );
        }
    }
    
    /**
     * _request function.
     *
     * Handles request to the mCASH API and returns and instance of mCASHObject or the current class
     * 
     * @access protected
     * @param mixed $method
     * @param mixed $url
     * @param array $params (default: array())
     * @param mixed $opts (default: null)
     * @return mCASHObject|static
     */
    protected function _request( $method, $url, $params = array(), $opts = null ){
	    $opts = array_merge( mCASH::defaultHeaders()->Headers, Utilities\Headers::parse( $opts )->Headers );
		$requestor = new Requestor(mCASH::getApiSecret(), static::baseUrl());
		$result = $requestor->request( $method, $url, $params, $opts );
		return $result;
    }	
    
    /**
     * _create function.
     *
     * Sends POST with parameters to mCASH Api. Used when creating new objects
     * 
     * @access protected
     * @static
     * @param mixed $params (default: null)
     * @param mixed $opts (default: null)
     * @return mCASHObject|Static
     */
    protected static function _create($params = null, $opts = null) {
	    $url = static::classUrl();
	    $method = ( isset( $opts['method'] ) ) ? $opts['method'] : "POST";
	    list( $response, $opts ) = static::_request( $method, $url, $params, $opts );
	    $response = json_decode( $response, true ); 
		$response['object'] = static::className();
		return Utilities\Utilities::convertToMCASHObject($response, $opts);
    }
    
    /**
     * _all function.
     *
     * Fetches all object of given type from mCASH Api
     * 
     * @access protected
     * @static
     * @param mixed $params (default: null)
     * @param mixed $opts (default: null)
     * @return mCASHObject|Static
     */
    protected static function _all($params = null, $opts = null) {
	    self::_validateParams($params);
	    $url = static::classUrl();
	    list($response, $opts) = static::_request('GET', $url, $params, $options);	   
	    $response = json_decode( $response, true ); 
	    $response['object'] = static::className(); 
		return Utilities\Utilities::convertToMCASHObject($response, $opts);
    }
    
    /**
     * _retrieve function.
     *
     * Retrieves a specific object from mCASH Api based on current object class and ID
     * 
     * @access protected
     * @static
     * @param mixed $id
     * @param mixed $opts (default: null)
     * @return Static
     */
    protected static function _retrieve($id, $opts = null) {
		$instance = new static($id, $opts);
		$instance->refresh();
		return $instance; 	    
    }
    
	/**
	 * _save function.
	 *
	 * Performs a PUT request to mCASH Api, saving the current object
	 * 
	 * @access protected
	 * @param mixed $opts (default: null)
	 * @return Static
	 */
	protected function _save($opts = null){
        $params = $this->serializeParameters();
        
        $sendParams = array();

        foreach( $params AS $key => $value ){
	        if( in_array( $key, static::$updateParams ) ) $sendParams[$key] = $value;
        }
	
        if (count($params) > 0) {
            $url = $this->instanceUrl();
            list($response, $opts) = $this->_request('PUT', $url, $sendParams, $opts);
            $this->refreshFrom($response, $opts);
        }
        return $this;		
	}
	
	/**
	 * _delete function.
	 *
	 * Performs a DELETE request to mCASH Api, deleting the current object
	 * 
	 * @access protected
	 * @param mixed $params (default: null)
	 * @param mixed $opts (default: null)
	 * @return Boolean
	 */
	protected function _delete($params = null, $opts = null){
		$url = $this->instanceUrl();
		list($response, $opts) = $this->_request('DELETE', $url, $params, $opts);
		$this->refreshFrom($response, $opts);
		return $this;
	}
	
}

?>