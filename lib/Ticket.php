<?php
	
namespace mCASH;

/**
 * Ticket class.
 * 
 * @extends PaymentRequest
 */
class Ticket extends PaymentRequest {
	
	/**
	 * endpointUrlAppend
	 * 
	 * (default value: "ticket")
	 * 
	 * @var string
	 * @access protected
	 */
	protected static $endpointUrlAppend = "ticket";	    
	
	/**
	 * setPaymentId function.
	 * 
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function setPaymentId($id){
		$this->endpointUrlAppend( "$id/ticket/" );
	}

    
    /**
     * create function.
     * 
     * @access public
     * @static
     * @param mixed $params (default: null)
     * @param mixed $opts (default: null)
     * @return void
     */
    public static function create($params = null, $opts = null){
	    $opts['method'] = "PUT";
        return self::_create($params, $opts);
    }	
	
}

?>