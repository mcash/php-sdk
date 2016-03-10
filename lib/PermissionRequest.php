<?php
	
namespace mCASH;

/**
 * PermissionRequest class.
 * 
 * @extends Resource
 */
class PermissionRequest extends Resource {
	
	/**
	 * endpointUrl
	 * 
	 * (default value: "permission_request")
	 * 
	 * @var string
	 * @access protected
	 */
	protected $endpointUrl = "permission_request";

    /**
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @return PermissionRequest
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
     * @return PermissionRequest
     */
    public static function retrieve($id = null, $opts = null){
        return self::_retrieve($id, $opts);
    } 
	
}