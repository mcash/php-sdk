<?php
	
namespace mCASH;

/**
 * User class.
 * 
 * @extends Resource
 */
class User extends Resource {
	
	/**
	 * endpointUrl
	 * 
	 * (default value: "user")
	 * 
	 * @var string
	 * @access protected
	 */
	protected $endpointUrl = "user";

    /**
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @return PaymentRequest
     */
    public static function create($params = null, $opts = null){
        return self::_create($params, $opts);
    }	    

    /**
     * retrieve function.
     * 
     * @access public
     * @static
     * @param mixed $id (default: null)
     * @param mixed $opts (default: null)
     * @return PaymentRequest
     */
    public static function retrieve($id = null, $opts = null){
        return self::_retrieve($id, $opts);
    } 


	/**
	 * update function.
	 * 
	 * @access public
	 * @return boolean
	 */
	public function update(){
		$result = $this->_save();
		return Utilities\Utilities::handleResponseCode( $result->_opts, $result->_values );		
	}
	
}