<?php
	
namespace mCASH;

/**
 * PaymentRequest class.
 * 
 * @extends Resource
 */
class PaymentRequest extends Resource {
	
	protected static $updateParams = array( 'action', 'ledger', 'display_message_uri', 'callback_uri', 'amount', 'additional_amount', 'refund_id', 'capture_id', 'currency', 'text' );
	
	/**
	 * endpointUrl
	 * 
	 * (default value: "payment_request")
	 * 
	 * @var string
	 * @access protected
	 */
	protected $endpointUrl = "payment_request";

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
     * reauthorize function.
     * 
     * @access public
     * @return Boolean
     *
     * @throws Error\Request
     */
    public function reauthorize(){
	    $temp_text = $this->text;
	    $this->text = "";
	    $this->action = 'reauth';
		$result = $this->_save();
		$this->text = $temp_text;
		return Utilities\Utilities::handleResponseCode( $result->_opts, $result->_values );
    }  
       
    /**
     * capture function.
     * 
     * @access public
     * @return Boolean
     *
     * @throws Error\Request
     */
    public function capture(){
	    self::$updateParams = array( 'ledger', 'action',  'display_message_uri', 'callback_uri' );
	    $this->action = 'capture';
		$result = $this->_save();
		return Utilities\Utilities::handleResponseCode( $result->_opts, $result->_values );
    }     
    
    /**
     * release function.
     * 
     * @access public
     * @return Boolean
     *
     * @throws Error\Request
     */
    public function release(){
	    $this->action = 'release';
		$result = $this->_save();
		return Utilities\Utilities::handleResponseCode( $result->_opts, $result->_values );
    }     
  
    /**
     * refund function.
     * 
     * @access public
     * @return Boolean
     *
     * @throws Error\Request
     */
    public function refund(){
	    self::$updateParams = array( 'amount', 'text', 'refund_id', 'additional_amount', 'currency', 'action' );
	    $this->action = 'refund';
		$result = $this->_save();
		return Utilities\Utilities::handleResponseCode( $result->_opts, $result->_values );
    }    
    
    /**
     * outcome function.
     * 
     * @access public
     * @return PaymentRequestOutcome
     */
    public function outcome(){
	    return PaymentRequestOutcome::retrieve($this->id);
    }     
	
    /**
     * ticket function.
     * 
     * @access public
     * @return Ticket
     */
    public function ticket(){
		$ticket = new Ticket;
		$ticket->setPaymentId( $this->id );
		return $ticket;
    }  	
	
}