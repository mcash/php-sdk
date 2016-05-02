<?php
	
namespace mCASH\Utilities;

/**
 * Encryption class.
 */
abstract class Encryption {
	
    /**
     * buildSignatureMessage function.
     * 
     * @access public
     * @param mixed $requestMethod
     * @param mixed $url
     * @param mixed $headers
     * @return string
     */
    public static function buildSignatureMessage($requestMethod, $url, $headers) {
        // Find headers that start with X-MCASH
        $mcashHeaders = array();
        foreach ($headers as $key => $value) {
            $ucKey = strtoupper($key);
            if (substr($ucKey, 0, 7) === "X-MCASH") {
                $mcashHeaders[$ucKey] = $value;
            }
        }

        // Sort headers by key
        ksort($mcashHeaders);

        // Create key value pairs 'key=value'
        $headerPairs = array();
        foreach ($mcashHeaders as $key => $value) {
            $headerPairs[] = sprintf("%s=%s", $key, $value);
        }

        // Join header pairs
        $headerString = implode("&", $headerPairs);

        return sprintf(
            "%s|%s|%s", strtoupper($requestMethod), $url, $headerString
        );
    }

    /**
     * contentDigest function.
     * 
     * @access private
     * @param string $data (default: "")
     * @return string
     */
    public static function contentDigest($data) {
        $digest = "SHA256=" . base64_encode(hash("sha256", $data, true));
		return $digest;
    }

    /**
     * sign_pkcs1 function.
     * 
     * @access public
     * @param mixed $key
     * @param mixed $data
     * @return string
     *
     * Throws Error\Base
     */
    public static function sign_pkcs1($key, $data) {
        if (!openssl_sign($data, $signature, $key, "sha256")) {
          	throw new \mCASH\Error\Api("Could not create a signed key pair");
        }
        return base64_encode($signature);
    }

    
    /**
     * verify_signature_pkcs1 function.
     * 
     * @access public
     * @static
     * @param mixed $key
     * @param mixed $data
     * @param mixed $signature
     * @return boolean
     */
    public static function verify_signature_pkcs1($key, $data, $signature) {
        return openssl_verify($data, base64_decode($signature), $key, "sha256");
    }    
    
	/**
	 * validateHeaders function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $method
	 * @param mixed $uri
	 * @param mixed $headers
	 * @param mixed $body
	 * @return boolean
	 */
	public static function validateHeaders($method, $uri, $headers, $body){
		
		$pubkey = ( \mCASH\mCASH::$live ) ? \mCASH\mCASH::$mCASHPubKey : \mCASH\mCASH::$mCASHPubTestKey;
		
		list( $headerKey, $signature ) = explode( " ", $headers['Authorization'] );
		
		$validHeader = self::verify_signature_pkcs1( $pubkey, self::buildSignatureMessage($method, $uri, $headers), $signature );
		
		$validPayload = ( self::contentDigest($body) === $headers['X-Mcash-Content-Digest'] );
		
		return ( $validHeader );
		
	}    
    
    /**
     * sign function.
     * 
     * @access private
     * @param mixed $requestMethod
     * @param mixed $url
     * @param mixed $headers
     * @param mixed $priv_key
     * @return string
     */
    public static function sign($requestMethod, $url, $headers, $priv_key) {
        return self::sign_pkcs1($priv_key, self::buildSignatureMessage($requestMethod, $url, $headers));
    }	
	
}
	
?>