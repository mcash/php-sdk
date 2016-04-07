<?php
	
namespace mCASH\HttpClient;

use mCASH\mCASH;
use mCASH\Error;

/**
 * CurlClient class.
 * 
 * @implements ClientInterface
 */
class CurlClient implements ClientInterface {
	
    /**
     * instance
     * 
     * @var mixed
     * @access private
     * @static
     */
    private static $instance;
	
    /**
     * instance function.
     * 
     * @access public
     * @static
     * @return static
     */
    public static function instance(){
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * request function.
     * 
     * @access public
     * @param mixed $method
     * @param mixed $absUrl
     * @param mixed $params
     * @param mixed $headers
     * @return array($response, $code, $rheaders)
     */
    public function request($method, $absUrl, $params, $headers){
	    // Initialize a new curl session
	    $curl = curl_init();
	    // Alters $method string to lower case
	    $method = strtolower( $method );
	    // The $opts array will contain the options for the curl session
	    $opts = array();
	    // Check the method of the request and set up the query accordingly
	    if( $method == "get" ){
		    $opts[CURLOPT_HTTPGET] = 1;
	    } else if ( $method == "post" ) {
		    $opts[CURLOPT_POST] = 1;
		    $opts[CURLOPT_POSTFIELDS] = self::encode($params);
	    } else if( $method == "delete" ){
		    $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
	    } else if( $method == "put" ){
		    $opts[CURLOPT_CUSTOMREQUEST] = 'PUT';
			$opts[CURLOPT_POST] = 1;
		    $opts[CURLOPT_POSTFIELDS] = self::encode($params);
	
	    } else {
		    // If the requested method isnt recognized, throw an error
		    throw new Error\Api( "Unrecognized method {$method}" );
	    }
	    
        // Create a callback to capture HTTP headers for the response
        $rheaders = array();
        $headerCallback = function ($curl, $header_line) use (&$rheaders) {
            // Ignore the HTTP request line (HTTP/1.1 200 OK)
            if (strpos($header_line, ":") === false) {
                return strlen($header_line);
            }
            list($key, $value) = explode(":", trim($header_line), 2);
            $rheaders[trim($key)] = trim($value);
            return strlen($header_line);
        };	    
	    
	    // Set deafult content type and accept type headers
	    $headers['Accept'] = 'application/vnd.mcash.api.merchant.v1+json';
	    $headers['Content-Type'] = 'application/json';	    
	    
	    // Check if we are using Key as signature, then we need to SHA encrypt this
	    if( mCASH::getApiLevel() == "KEY" ){
 		    $headers['X-Mcash-Timestamp'] = date( 'Y-m-d H:i:s' );
		    $headers['X-Mcash-Content-Digest'] = ( empty( $params ) ) ? \mCASH\Utilities\Encryption::contentDigest("") : \mCASH\Utilities\Encryption::contentDigest(self::encode( $params ));
		    $headers['Authorization'] = "RSA-SHA256 " . \mCASH\Utilities\Encryption::sign(strtoupper($method), $absUrl, $headers, mCASH::getApiSecret());
	    }
	    // When using secret as signature
	    if( mCASH::getApiLevel() == "SECRET" ){
		    $headers['Authorization'] = "SECRET " . mCASH::getApiSecret();
	    }

		
        $opts[CURLOPT_URL] = $absUrl;
        $opts[CURLOPT_RETURNTRANSFER] = true;
        $opts[CURLOPT_CONNECTTIMEOUT] = 30;
        $opts[CURLOPT_TIMEOUT] = 80;
        $opts[CURLOPT_HEADERFUNCTION] = $headerCallback;
        // Translate the associative headers array to a readable header array for the API
        $sheaders = array();
        foreach( $headers AS $headKey => $headVal ){
	        $sheaders[] = "{$headKey}: {$headVal}";
        }

        $opts[CURLOPT_HTTPHEADER] = $sheaders;
		$opts[CURLOPT_SSL_VERIFYPEER] = false;
		$opts[CURLOPT_FOLLOWLOCATION] = true;
		
        curl_setopt_array($curl, $opts);
        
        $response = curl_exec($curl);
        $errno = curl_errno($curl);
		
		// Handle any error from the response
        if ($response === false) {
            $errno = curl_errno($curl);
            $message = curl_error($curl);
            curl_close($curl);
            $this->handleCurlError($absUrl, $errno, $message);
        }                    	
		
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		
		// Close the curl connection
        curl_close($curl);
		
		// Run a check on the response to validate its data
		\mCASH\Utilities\Utilities::handleResponseCode( $code, $response );
		
		// Returns the result from the query as an array consisting of (data, httpcode, response headers)
        return array($response, $code, $rheaders);		
	
    }

    /**
     * handleCurlError function.
     * 
     * @access private
     * @param mixed $url
     * @param mixed $errno
     * @param mixed $message
     * @return void
     *
     * Throws Error\ApiConnection
     */
    private function handleCurlError($url, $errno, $message){
        switch ($errno) {
	        case CURLE_OPERATION_TIMEOUTED:
            case CURLE_COULDNT_CONNECT:
            case CURLE_COULDNT_RESOLVE_HOST:
            	$msg = "Could not connect to {$url}. This might be a problem with your internet connection, or the service might be unavailable.";
                break;
            case CURLE_SSL_CACERT:
            case CURLE_SSL_PEER_CERTIFICATE:
            	$msg = "Could not verify SSL Certificate";
                break;
            default:
                $msg = "Unexpected error communicating with the mCASH API. If the problem persists, ";
        }
        $msg .= " let us know at support@mca.sh.";

        $msg .= "\n\n(Network error [errno $errno]: $message)";
        throw new Error\ApiConnection($msg);
    }

    /**
     * encode function.
     * 
     * @access private
     * @static
     * @param mixed $arr
     * @return json
     */
    private static function encode($arr){
        return json_encode($arr);
    }
    
    	
}

?>