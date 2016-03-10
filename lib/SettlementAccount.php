<?php
	
namespace mCASH;

/**
 * SettlementAccount class.
 * 
 * @extends Resource
 */
class SettlementAccount extends Resource {
	
	/**
	 * endpointUrl
	 * 
	 * (default value: "settlement_account")
	 * 
	 * @var string
	 * @access protected
	 */
	protected $endpointUrl = "settlement_account";

    /**
     * retrieve function.
     * 
     * @access public
     * @static
     * @param mixed $id (default: null)
     * @param mixed $opts (default: null)
     * @return Settlement
     */
    public static function retrieve($id = null, $opts = null){
        return self::_retrieve($id, $opts);
    }   
	
}