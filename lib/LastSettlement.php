<?php
	
namespace mCASH;

/**
 * LastSettlement class.
 * 
 * @extends Resource
 */
class LastSettlement extends Resource {
	
	/**
	 * endpointUrl
	 * 
	 * (default value: "last_settlement")
	 * 
	 * @var string
	 * @access protected
	 */
	protected $endpointUrl = "last_settlement";	    

    /**
     * retrieve function.
     * 
     * @access public
     * @static
     * @return LastSettlement
     */
    public static function retrieve(){
        return self::_all();
    } 

 
	
}