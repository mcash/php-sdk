<?php
	
namespace mCASH;

/**
 * Report class.
 * 
 * @extends Ledger
 */
class Report extends Ledger {
	
	protected static $updateParams = array( 'callback_uri' );
	
	/**
	 * endpointUrlAppend
	 * 
	 * (default value: "outcome")
	 * 
	 * @var string
	 * @access protected
	 */
	protected static $endpointUrlAppend = "report";	    
	
	/**
	 * setLedgerId function.
	 * 
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function setLedgerId($id){
		$this->endpointUrlAppend( "$id/report/" );
	}
	
    /**
     * retrieve function.
     * 
     * @access public
     * @static
     * @param mixed $id (default: null)
     * @param mixed $opts (default: null)
     * @return Report
     */
    public static function retrieve($id = null, $opts = null){
        return self::_retrieve($id, $opts);
    } 
    
    /**
     * all function.
     * 
     * @access public
     * @static
     * @return void
     */
    public static function all(){
	    return self::_all();
    }   
    
	/**
	 * close function.
	 * 
	 * @access public
	 * @return boolean
	 */
	public function close(){
		$result = $this->_save();
		return Utilities\Utilities::handleResponseCode( $result->_opts, $result->_values );				
	}        
	
}

?>