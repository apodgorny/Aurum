<?
    /**
     * class OAuthRequest
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

     class OAuthRequest {
         
         /******************* PRIVATE ********************/
         
         private $_aParameters;
         private $_sHttpMethod;
         private $_sUrl;
         private $_sResponse;
         
         /******************* PUBLIC ********************/
         
         public function __construct($sHttpMethod, $sUrl, $oConsumer, $oToken, $aParameters=NULL) {
            $aParameters || $aParameters = array();
            $aDefaults = array(
                'oauth_version'       => OAuth::VERSION,
                'oauth_nonce'         => OAuthRequest::generateNonce(),
                'oauth_timestamp'     => OAuthRequest::generateTimestamp(),
                'oauth_consumer_key'  => $oConsumer->key
            );
            if ($oToken) {
                $aDefaults['oauth_token'] = $oToken->key;
            }
            $this->_aParameters = array_merge(
                $aDefaults, 
                OAuthUtil::parse_parameters(parse_url($sUrl, PHP_URL_QUERY)), 
                $aParameters
            );
            $this->_sHttpMethod = strtoupper($sHttpMethod);
             $this->_sUrl = OAuthUtil::normalizeUrl($sUrl);
         }
         public function sign($sSignatureMethod, $oConsumer, $oToken) {
              $sSignatureMethodClass = 'OAuthSignatureMethod_'.$sSignatureMethod;
              $oSignatureMethod = new $sSignatureMethodClass();
              $this->setParameter(
                  'oauth_signature_method',
                  $oSignatureMethod->get_name(),
                  false
              );
              $sSignature = $this->generateSignature($oSignatureMethod, $oConsumer, $oToken);
              $this->setParameter('oauth_signature', $sSignature, false);
         }
   	     public function execute($sPostData='') {
              $sHeader = $this->toHeader();
              $bIsPost = $this->_sHttpMethod==OAuth::METHOD_POST;
   		      $aOptions = array(
                  CURLOPT_POST           => $bIsPost ? 1 : 0,
                  CURLOPT_HTTPHEADER     => $sHeader,
                  CURLOPT_USERAGENT      => "Mozilla 2.0",
                  CURLOPT_CONNECTTIMEOUT => 120,
                  CURLOPT_TIMEOUT        => 120,
                  CURLOPT_URL            => $this->_sUrl,
                  CURLOPT_SSL_VERIFYHOST => 2,
                  CURLOPT_SSL_VERIFYPEER => 0,
                  CURLOPT_VERBOSE        => 1,
                  CURLOPT_RETURNTRANSFER => 1,
                  CURLOPT_HEADER         => 1,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_ENCODING       => '',
                  CURLOPT_AUTOREFERER    => true,
                  CURLOPT_MAXREDIRS      => 10
             );
             
             if ($bIsPost) {
                 $aOptions[CURLOPT_POSTFIELDS] = $sPostData;
             }

             $oCurl = curl_init();
             curl_setopt_array($oCurl, $aOptions);
             //curl_setopt($ch, CURLOPT_CAINFO, getcwd() . '/data/CAcerts/curl-ca-bundle.crt');

             $this->_sResponse = curl_exec($oCurl);
             $err      = curl_errno($oCurl);
             $errmsg   = curl_error($oCurl) ;
             $header   = curl_getinfo($oCurl);
             curl_close($oCurl);
             return $this->getResponse();
   	     }
   	     public function getResponse() {
   	         if (!$this->_sResponse) {
   	             Notification::set('Linkedin network is temporarily unavailable');
   	             Navigation::redirect(null, 'home', 'myhome');
   	             return;
             }
   	         list($sHeader, $sBody) = preg_split('/\n(\r)?\n(\r)?/', $this->_sResponse, 2);
   	         return $sBody;
         }
         public function setParameter($sName, $sValue, $bAllowDuplicates=true) {
             if ($bAllowDuplicates && isset($this->_aParameters[$sName])) {
                 if (is_scalar($this->_aParameters[$sName])) {
                     $this->_aParameters[$sName] = array($this->_aParameters[$sName]);
                 }
                 $this->_aParameters[$sName][] = $sValue;
             } else {
                 $this->_aParameters[$sName] = $sValue;
             }
         }
         public function getParameter($name) {
             return isset($this->_aParameters[$name]) ? $this->_aParameters[$name] : null;
         }
         
         /**
           * Returns the base string of this request
           *
           * The base string defined as the method, the url
           * and the parameters (normalized), each urlencoded
           * and the concated with &.
           */
         public function get_signature_base_string() {
             $aSignableParams = $this->_aParameters;

             // Remove oauth_signature if present
             // Ref: Spec: 9.1.1 ("The oauth_signature parameter MUST be excluded.")
             if (isset($aSignableParams['oauth_signature'])) {
                 unset($aSignableParams['oauth_signature']);
             }
             $parts = array(
                 $this->_sHttpMethod,
                 $this->_sUrl,
                 OAuthUtil::generateHttpQuery($aSignableParams)
             );
             return implode('&', OAuthUtil::urlencode_rfc3986($parts));
         }
         public function toUrl() {
             $sQuery = OAuthUtil::generateHttpQuery($this->_aParameters);
             $sUrl = $this->_sUrl;
             if ($sQuery) {
                 $sUrl .= '?'.$sQuery;
             }
             return $sUrl;
         }
         public function toHeader($sRealm=null) {
             $aHeader = array();
             array_push($aHeader, 'Authorization: OAuth realm="'.$sRealm.'"');
             foreach($this->_aParameters as $sKey=>$sValue) {
         	     if (substr($sKey, 0, 5) == 'oauth') {
             	     array_push($aHeader, $sKey.'="'.OAuthUtil::urlencode_rfc3986($sValue).'"');
                 }
             }
             return array(implode(', ', $aHeader));
         }
         public function __toString() {
             return $this->toUrl();
         }
         public function generateSignature($oSignatureMethod, $oConsumer, $oToken) {
             return $oSignatureMethod->generateSignature($this, $oConsumer, $oToken);
         }
         private static function generateTimestamp() {
             return time();
         }
         private static function generateNonce() {
             return md5(microtime().mt_rand()); 
         }
    }

?>