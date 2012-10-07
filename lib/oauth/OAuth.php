<?

    class OAuthException extends Exception {}

    /*****************************************************/
    
    class OAuthConsumer {
        public $key;
        public $secret;

        function __construct($key, $secret, $callback_url=NULL) {
            $this->key = $key;
            $this->secret = $secret;
            $this->callback_url = $callback_url;
        }

        function __toString() {
            return "OAuthConsumer[key=$this->key,secret=$this->secret]";
        }
    }

    /*****************************************************/
    
    class OAuthToken {
        // access tokens and request tokens
        public $key;
        public $secret;

        /**
         * key = the token
         * secret = the token secret
         */
        function __construct($key, $secret) {
            $this->key = $key;
            $this->secret = $secret;
        }

        /**
         * generates the basic string serialization of a token that a server
         * would respond to request_token and access_token calls with
         */
        function to_string() {
            return "oauth_token=" .
                         OAuthUtil::urlencode_rfc3986($this->key) .
                         "&oauth_token_secret=" .
                         OAuthUtil::urlencode_rfc3986($this->secret);
        }

        function __toString() {
            return $this->to_string();
        }
    }

    /*****************************************************/
    
    /**
     * A class for implementing a Signature Method
     * See section 9 ("Signing Requests") in the spec
     */
    abstract class OAuthSignatureMethod {
        /**
         * Needs to return the name of the Signature Method (ie HMAC-SHA1)
         * @return string
         */
        abstract public function get_name();

        /**
         * Build up the signature
         * NOTE: The output of this function MUST NOT be urlencoded.
         * the encoding is handled in OAuthRequest when the final
         * request is serialized
         * @param OAuthRequest $request
         * @param OAuthConsumer $consumer
         * @param OAuthToken $token
         * @return string
         */
        abstract public function generateSignature($request, $consumer, $token);

        /**
         * Verifies that a given signature is correct
         * @param OAuthRequest $request
         * @param OAuthConsumer $consumer
         * @param OAuthToken $token
         * @param string $signature
         * @return bool
         */
        public function check_signature($request, $consumer, $token, $signature) {
            $built = $this->generateSignature($request, $consumer, $token);
            return $built == $signature;
        }
    }
    
    /*****************************************************/

    /**
     * The HMAC-SHA1 signature method uses the HMAC-SHA1 signature algorithm as defined in [RFC2104] 
     * where the Signature Base String is the text and the key is the concatenated values (each first 
     * encoded per Parameter Encoding) of the Consumer Secret and Token Secret, separated by an '&' 
     * character (ASCII code 38) even if empty.
     *   - Chapter 9.2 ("HMAC-SHA1")
     */
    class OAuthSignatureMethod_HMAC_SHA1 extends OAuthSignatureMethod {
        function get_name() {
            return "HMAC-SHA1";
        }

        public function generateSignature($request, $consumer, $token) {
            $base_string = $request->get_signature_base_string();
            $request->base_string = $base_string;

            $key_parts = array(
                $consumer->secret,
                ($token) ? $token->secret : ""
            );

            $key_parts = OAuthUtil::urlencode_rfc3986($key_parts);
            $key = implode('&', $key_parts);

            return base64_encode(hash_hmac('sha1', $base_string, $key, true));
        }
    }
    
    /*****************************************************/

    /**
     * The PLAINTEXT method does not provide any security protection and SHOULD only be used 
     * over a secure channel such as HTTPS. It does not use the Signature Base String.
     *   - Chapter 9.4 ("PLAINTEXT")
     */
    class OAuthSignatureMethod_PLAINTEXT extends OAuthSignatureMethod {
        public function get_name() {
            return "PLAINTEXT";
        }

        /**
         * oauth_signature is set to the concatenated encoded values of the Consumer Secret and 
         * Token Secret, separated by a '&' character (ASCII code 38), even if either secret is 
         * empty. The result MUST be encoded again.
         *   - Chapter 9.4.1 ("Generating Signatures")
         *
         * Please note that the second encoding MUST NOT happen in the SignatureMethod, as
         * OAuthRequest handles this!
         */
        public function generateSignature($request, $consumer, $token) {
            $key_parts = array(
                $consumer->secret,
                ($token) ? $token->secret : ""
            );

            $key_parts = OAuthUtil::urlencode_rfc3986($key_parts);
            $key = implode('&', $key_parts);
            $request->base_string = $key;

            return $key;
        }
    }
    
    /*****************************************************/

    /**
     * The RSA-SHA1 signature method uses the RSASSA-PKCS1-v1_5 signature algorithm as defined in 
     * [RFC3447] section 8.2 (more simply known as PKCS#1), using SHA-1 as the hash function for 
     * EMSA-PKCS1-v1_5. It is assumed that the Consumer has provided its RSA public key in a 
     * verified way to the Service Provider, in a manner which is beyond the scope of this 
     * specification.
     *   - Chapter 9.3 ("RSA-SHA1")
     */
    abstract class OAuthSignatureMethod_RSA_SHA1 extends OAuthSignatureMethod {
        public function get_name() {
            return "RSA-SHA1";
        }

        // Up to the SP to implement this lookup of keys. Possible ideas are:
        // (1) do a lookup in a table of trusted certs keyed off of consumer
        // (2) fetch via http using a url provided by the requester
        // (3) some sort of specific discovery code based on request
        //
        // Either way should return a string representation of the certificate
        protected abstract function fetch_public_cert(&$request);

        // Up to the SP to implement this lookup of keys. Possible ideas are:
        // (1) do a lookup in a table of trusted certs keyed off of consumer
        //
        // Either way should return a string representation of the certificate
        protected abstract function fetch_private_cert(&$request);

        public function generateSignature($request, $consumer, $token) {
            $base_string = $request->get_signature_base_string();
            $request->base_string = $base_string;

            // Fetch the private key cert based on the request
            $cert = $this->fetch_private_cert($request);

            // Pull the private key ID from the certificate
            $privatekeyid = openssl_get_privatekey($cert);

            // Sign using the key
            $ok = openssl_sign($base_string, $signature, $privatekeyid);

            // Release the key resource
            openssl_free_key($privatekeyid);

            return base64_encode($signature);
        }

        public function check_signature($request, $consumer, $token, $signature) {
            $decoded_sig = base64_decode($signature);

            $base_string = $request->get_signature_base_string();

            // Fetch the public key cert based on the request
            $cert = $this->fetch_public_cert($request);

            // Pull the public key ID from the certificate
            $publickeyid = openssl_get_publickey($cert);

            // Check the computed signature against the one passed in the query
            $ok = openssl_verify($base_string, $decoded_sig, $publickeyid);

            // Release the key resource
            openssl_free_key($publickeyid);

            return $ok == 1;
        }
    }

    /*****************************************************/

    class OAuthUtil {
        
        public static function urlencode_rfc3986($mValue) {
            if (is_array($mValue)) {
                return array_map(array('OAuthUtil', 'urlencode_rfc3986'), $mValue);
            } else if (is_scalar($mValue)) {
                return str_replace('+', ' ', str_replace('%7E', '~', rawurlencode($mValue)));
            } else {
                return '';
            }
        }

        // This decode function isn't taking into consideration the above
        // modifications to the encoding process. However, this method doesn't
        // seem to be used anywhere so leaving it as is.
        public static function urldecode_rfc3986($string) {
            return urldecode($string);
        }

        // Utility function for turning the Authorization: header into
        // parameters, has to do some unescaping
        // Can filter out any non-oauth parameters if needed (default behaviour)
        public static function split_header($header, $only_allow_oauth_parameters = true) {
            $pattern = '/(([-_a-z]*)=("([^"]*)"|([^,]*)),?)/';
            $offset = 0;
            $params = array();
            while (preg_match($pattern, $header, $matches, PREG_OFFSET_CAPTURE, $offset) > 0) {
                $match = $matches[0];
                $header_name = $matches[2][0];
                $header_content = (isset($matches[5])) ? $matches[5][0] : $matches[4][0];
                if (preg_match('/^oauth_/', $header_name) || !$only_allow_oauth_parameters) {
                    $params[$header_name] = OAuthUtil::urldecode_rfc3986($header_content);
                }
                $offset = $match[1] + strlen($match[0]);
            }

            if (isset($params['realm'])) {
                unset($params['realm']);
            }

            return $params;
        }

        // helper to try to sort out headers for people who aren't running apache
        public static function get_headers() {
            if (function_exists('apache_request_headers')) {
                // we need this to get the actual Authorization: header
                // because apache tends to tell us it doesn't exist
                $headers = apache_request_headers();

                // sanitize the output of apache_request_headers because
                // we always want the keys to be Cased-Like-This and arh()
                // returns the headers in the same case as they are in the
                // request
                $out = array();
                foreach( $headers AS $key => $value ) {
                    $key = str_replace(
                        ' ',
                        '-',
                        ucwords(strtolower(str_replace('-', ' ', $key)))
                    );
                    $out[$key] = $value;
                }
            } else {
                // otherwise we don't have apache and are just going to have to hope
                // that $_SERVER actually contains what we need
                $out = array();
                if( isset($_SERVER['CONTENT_TYPE']) )
                    $out['Content-Type'] = $_SERVER['CONTENT_TYPE'];
                if( isset($_ENV['CONTENT_TYPE']) )
                    $out['Content-Type'] = $_ENV['CONTENT_TYPE'];

                foreach ($_SERVER as $key => $value) {
                    if (substr($key, 0, 5) == "HTTP_") {
                        // this is chaos, basically it is just there to capitalize the first
                        // letter of every word that is not an initial HTTP and strip HTTP
                        // code from przemek
                        $key = str_replace(
                          ' ',
                          '-',
                          ucwords(strtolower(str_replace('_', ' ', substr($key, 5))))
                        );
                        $out[$key] = $value;
                    }
                }
            }
            return $out;
        }

        // This function takes a input like a=b&a=c&d=e and returns the parsed
        // parameters like this
        // array('a' => array('b','c'), 'd' => 'e')
        public static function parse_parameters( $input ) {
            if (!isset($input) || !$input) { return array(); }

            $pairs = explode('&', $input);
            $parsed_parameters = array();
            foreach ($pairs as $pair) {
                $split = explode('=', $pair, 2);
                $parameter = OAuthUtil::urldecode_rfc3986($split[0]);
                $value = isset($split[1]) ? OAuthUtil::urldecode_rfc3986($split[1]) : '';

                if (isset($parsed_parameters[$parameter])) {
                    // We have already recieved parameter(s) with this name, so add to the list
                    // of parameters with this name
                    if (is_scalar($parsed_parameters[$parameter])) {
                        // This is the first duplicate, so transform scalar (string) into an array
                        // so we can add the duplicates
                        $parsed_parameters[$parameter] = array($parsed_parameters[$parameter]);
                    }
                    $parsed_parameters[$parameter][] = $value;
                } else {
                    $parsed_parameters[$parameter] = $value;
                }
            }
            return $parsed_parameters;
        }

        public static function generateHttpQuery($params) {
            if (!$params) { return ''; }

            // Urlencode both keys and values
            $keys = OAuthUtil::urlencode_rfc3986(array_keys($params));
            $values = OAuthUtil::urlencode_rfc3986(array_values($params));
            $params = array_combine($keys, $values);

            // Parameters are sorted by name, using lexicographical byte value ordering.
            // Ref: Spec: 9.1.1 (1)
            uksort($params, 'strcmp');

            $pairs = array();
            foreach ($params as $parameter => $value) {
                if (is_array($value)) {
                    // If two or more parameters share the same name, they are sorted by their value
                    // Ref: Spec: 9.1.1 (1)
                    natsort($value);
                    foreach ($value as $duplicate_value) {
                        $pairs[] = $parameter . '=' . $duplicate_value;
                    }
                } else {
                    $pairs[] = $parameter . '=' . $value;
                }
            }
            // For each parameter, the name is separated from the corresponding value by an '=' character (ASCII code 61)
            // Each name-value pair is separated by an '&' character (ASCII code 38)
            return implode('&', $pairs);
        }
        
        /**
          * parses the url and rebuilds it to be
          * scheme://host/path
          */
         public static function normalizeUrl($sUrl) {
             $aUrlPieces = parse_url($sUrl);

             $port = isset($aUrlPieces['port']) ? $aUrlPieces['port'] : '';
             $scheme = $aUrlPieces['scheme'];
             $host = $aUrlPieces['host'];
             $path = isset($aUrlPieces['path']) ? $aUrlPieces['path'] : '';

             $port or $port = ($scheme == 'https') ? '443' : '80';

             if (($scheme == 'https' && $port != '443')
                     || ($scheme == 'http' && $port != '80')) {
                 $host = "$host:$port";
             }
             return "$scheme://$host$path";
         }
    }
    
    /*****************************************************/

?>
