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
     * @param mixed $id (default: null)
     * @param mixed $opts (default: null)
     * @return LastSettlement
     */
    public static function retrieve($id = null, $opts = null){
        return self::_retrieve($id, $opts);
    } 

 
	
}