<?php
	
namespace mCASH\Utilities;

/**
 * Abstract Utilities class.
 * 
 * @abstract
 */
abstract class Utilities {

	/**
	 * convertToMCASHObject function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $resp
	 * @param mixed $opts
	 * @return void
	 */
	public static function convertToMCASHObject($resp, $opts){

		$types = array(
			'paymentrequest' 			=> 'mCASH\\PaymentRequest',
			'paymentrequestoutcome' 	=> 'mCASH\\PaymentRequestOutcome',
			'report' 					=> 'mCASH\\Report',
			'ticket' 					=> 'mCASH\\Ticket'
		);
		
		if (is_array($resp) || is_object($resp)) {
			if (isset($resp['object']) && is_string($resp['object']) && isset($types[$resp['object']])) {
				$class = $types[$resp['object']];
			} else {
				$class = 'mCASH\\mCASHObject';
			}			
			return $class::constructFrom($resp, $opts);
		} else {
			return $resp;
		}
		
	}	

	/**
	 * handleResponseCode function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $code
	 * @param mixed $response (default: null)
	 * @return boolean
	 *
	 * Throws Error\Request
	 * Throws Error\Api
	 */
	public static function handleResponseCode( $code, $response = null ){
		
		// Convert $response from json string to array
		$response = ( empty( $response ) ) ? array() : json_decode( $response, true );

		switch( $code ){
			case 200:
			case 201:
			case 204:
				return true;
				break;
			case 400:
				throw new \mCASH\Error\Request("Illegal input. " . self::handleErrorResponse( $response ) );
				break;
			case 404:
				throw new \mCASH\Error\Api("Object not found. " . self::handleErrorResponse( $response ) );
				break;
			case 405:
				throw new \mCASH\Error\Request("Method not allowed. " . self::handleErrorResponse( $response ) );
				break;
			case 409:
				throw new \mCASH\Error\Request("Conflict. The action might not be legal at this moment, or some data might be duplicated. " . self::handleErrorResponse( $response ) );
				break;
			case 500:
				throw new \mCASH\Error\Request("500 Error. Malformed data. " . self::handleErrorResponse( $response ) );
				break;
		}
	}
	
	public static function handleErrorResponse( $response ){
		
		if( isset( $response['error_type'] ) && isset( $response['error_description'] ) ) return "{$response['error_type']}: {$response['error_description']}";
		
	}
	
}