<?php
	
namespace mCASH;

/**
 * mCASHObject class.
 */
class mCASHObject {
	
    /**
     * _opts
     * 
     * @var mixed
     * @access protected
     */
    protected $_opts;
    /**
     * _values
     * 
     * @var mixed
     * @access protected
     */
    protected $_values;
    /**
     * _unsavedValues
     * 
     * @var mixed
     * @access protected
     */
    protected $_unsavedValues;
    /**
     * _retrieveOptions
     * 
     * @var mixed
     * @access protected
     */
    protected $_retrieveOptions;

    /**
     * __construct function.
     * 
     * @access public
     * @param mixed $id (default: null)
     * @param mixed $opts (default: null)
     * @return void
     */
    public function __construct($id = null, $opts = null){
        $this->_opts = $opts ? $opts : null;
        $this->_values = array();
        $this->_unsavedValues = null;
        $this->_retrieveOptions = array();
        if (is_array($id)) {
            foreach ($id as $key => $value) {
                if ($key != 'id') {
                    $this->_retrieveOptions[$key] = $value;
                }
            }
            $id = $id['id'];
        }

        if ($id !== null) {
            $this->id = $id;
        } 
    }

    /**
     * __set function.
     * 
     * @access public
     * @param mixed $k
     * @param mixed $v
     * @return void
     */
    public function __set($k, $v){
        $this->_values[$k] = $v;
    }

    /**
     * __isset function.
     * 
     * @access public
     * @param mixed $k
     * @return void
     */
    public function __isset($k){
        return isset($this->_values[$k]);
    }
    
    /**
     * __unset function.
     * 
     * @access public
     * @param mixed $k
     * @return void
     */
    public function __unset($k){
        unset($this->_values[$k]);
    }
    
    /**
     * __get function.
     * 
     * @access public
     * @param mixed $k
     * @return void
     */
    public function &__get($k){
        // function should return a reference, using $nullval to return a reference to null
        $nullval = null;
        if (array_key_exists($k, $this->_values)) {
            return $this->_values[$k];
        } else {
            $class = get_class($this);
            error_log("Notice: Undefined property of $class instance: $k");
            return $nullval;
        }
    }

    /**
     * offsetSet function.
     * 
     * @access public
     * @param mixed $k
     * @param mixed $v
     * @return void
     */
    public function offsetSet($k, $v){
        $this->$k = $v;
    }

    /**
     * offsetExists function.
     * 
     * @access public
     * @param mixed $k
     * @return void
     */
    public function offsetExists($k){
        return array_key_exists($k, $this->_values);
    }

    /**
     * offsetUnset function.
     * 
     * @access public
     * @param mixed $k
     * @return void
     */
    public function offsetUnset($k){
        unset($this->$k);
    }
    
    /**
     * offsetGet function.
     * 
     * @access public
     * @param mixed $k
     * @return void
     */
    public function offsetGet($k){
        return array_key_exists($k, $this->_values) ? $this->_values[$k] : null;
    }

    /**
     * keys function.
     * 
     * @access public
     * @return void
     */
    public function keys(){
        return array_keys($this->_values);
    }

    /**
     * constructFrom function.
     * 
     * @access public
     * @static
     * @param mixed $values
     * @param mixed $opts
     * @return void
     */
    public static function constructFrom($values, $opts){
        $obj = new static(isset($values['id']) ? $values['id'] : null);
        $obj->refreshFrom($values, $opts);
        return $obj;
    }

    /**
     * refreshFrom function.
     * 
     * @access public
     * @param mixed $values
     * @param mixed $opts
     * @param bool $partial (default: false)
     * @return void
     */
    public function refreshFrom($values, $opts, $partial = false){
	    
        $this->_opts = $opts;
		
		if( is_array( $values ) ){
	        if ($partial) {
	            $removed = null;
	        } else {
	            $removed = array_diff(array_keys($this->_values), array_keys($values));
	        }			
		}
		
		if( !empty( $removed ) ){
	        foreach ($removed as $k) {
	            unset($this->$k);
	        }			
		}

		if( !empty( $values ) ){
	        foreach ($values as $k => $v) {
	            $this->_values[$k] = $v;
	        }			
		}

    }

    /**
     * serializeParameters function.
     * 
     * @access public
     * @return void
     */
    public function serializeParameters(){
	    $return = array();
	    foreach( $this->_values AS $key => $val ){
		    if( !in_array($key, array('uri','object','id') ) ) $return[$key] = $val;
	    }
		return $return;	
	}

    /**
     * jsonSerialize function.
     * 
     * @access public
     * @return void
     */
    public function jsonSerialize(){
        return $this->__toArray(true);
    }

    /**
     * __toJSON function.
     * 
     * @access public
     * @return void
     */
    public function __toJSON(){
        if (defined('JSON_PRETTY_PRINT')) {
            return json_encode($this->__toArray(true), JSON_PRETTY_PRINT);
        } else {
            return json_encode($this->__toArray(true));
        }
    }

    /**
     * __toString function.
     * 
     * @access public
     * @return void
     */
    public function __toString(){
        $class = get_class($this);
        return $class . ' JSON: ' . $this->__toJSON();
    }

    /**
     * __toArray function.
     * 
     * @access public
     * @param bool $recursive (default: false)
     * @return void
     */
    public function __toArray($recursive = false){
       	return $this->_values;
    }
	
}

?>