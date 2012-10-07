<?

	/**
     * Class OAuth
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

	require_once 'OAuth.php';

	class OAuth {

	    const VERSION = '1.0';

	    const METHOD_GET   = 'GET';
        const METHOD_POST  = 'POST';

	    const SIGNATURE_METHOD_HMAC_SHA1 = 'HMAC_SHA1';
	    const SIGNATURE_METHOD_PLAINTEXT = 'PLAINTEXT';
	    const SIGNATURE_METHOD_RSA_SHA1  = 'RSA_SHA1';

	    /************** PRIVATE ***************/

	    private $_sSessionKey = 'oauth';

		/************** PUBLIC ****************/

		public function __construct($sSessionKey) {
		    $this->_sSessionKey = $sSessionKey;
	    }

		public function requestToken($aConfig) {
		    unset($_SESSION[$this->_sSessionKey]);
			$oConsumer = new OAuthConsumer(
				$aConfig['key'],
				$aConfig['key_secret'],
				NULL
			);
			$oRequest = new OAuthRequest(
			    OAuth::METHOD_POST,
				$aConfig['url'],
				$oConsumer,
				NULL,
				array('oauth_callback' => $aConfig['url_callback'])
			);
			$oRequest->sign(
			    $aConfig['signature_method'],
			    $oConsumer,
			    NULL
			);
			$sContents = $oRequest->execute();
			$aParams = array();
			parse_str($sContents, $aParams);

			$_SESSION[$this->_sSessionKey]                          = $aParams;
			$_SESSION[$this->_sSessionKey]['consumer_key']          = $aConfig['key'];
			$_SESSION[$this->_sSessionKey]['consumer_key_secret']   = $aConfig['key_secret'];
			$_SESSION[$this->_sSessionKey]['signature_method']      = $aConfig['signature_method'];
			$_SESSION[$this->_sSessionKey]['url_start']             = $_SERVER['REQUEST_URI'];

			return $aParams;
		}

		public function authorize($sAuthorizeUrl) {
			header(
			    'Location: ' . $sAuthorizeUrl.
			    '?oauth_token=' . $_SESSION[$this->_sSessionKey]['oauth_token']
			);
			exit();
		}

		public function accessToken($sAccessTokenUrl) {
		    $sVerifier = (isset($aConfig['verifier'])
			    ? $aConfig['verifier']
			    : $_REQUEST['oauth_verifier']
			);
			$oConsumer = new OAuthConsumer(
				$_SESSION[$this->_sSessionKey]['consumer_key'],
				$_SESSION[$this->_sSessionKey]['consumer_key_secret']
			);
			$oToken = new OAuthToken(
				$_SESSION[$this->_sSessionKey]['oauth_token'],
				$_SESSION[$this->_sSessionKey]['oauth_token_secret']
			);

			$oRequest = new OAuthRequest(
			    OAuth::METHOD_POST,
			    $sAccessTokenUrl,
				$oConsumer,
				$oToken,
				array('oauth_verifier' => $sVerifier)
			);
			$oRequest->sign(
				$_SESSION[$this->_sSessionKey]['signature_method'],
				$oConsumer,
				$oToken
			);
			$sContents = $oRequest->execute();
			$aParams = array();
			parse_str($sContents, $aParams);

			// If request is stale, go back to authorize
			//if (!isset($aParams['oauth_token'])) {
			  //  header('Location: '.$_SESSION[$this->_sSessionKey]['url_start']);
		    //}

			$_SESSION[$this->_sSessionKey]['oauth_verifier']            = $sVerifier;
			$_SESSION[$this->_sSessionKey]['oauth_access_token']        = $aParams['oauth_token'];
        	$_SESSION[$this->_sSessionKey]['oauth_access_token_secret'] = $aParams['oauth_token_secret'];

			return $aParams;
		}


		public function sendRequest($sUrl, $sBody='', $sMethod=OAuth::METHOD_GET) {
			$oConsumer = new OAuthConsumer(
				$_SESSION[$this->_sSessionKey]['consumer_key'],
				$_SESSION[$this->_sSessionKey]['consumer_key_secret']
			);
			$oAccessToken = new OAuthToken(
				$_SESSION[$this->_sSessionKey]['oauth_access_token'],
				$_SESSION[$this->_sSessionKey]['oauth_access_token_secret']
			);
			$oRequest = new OAuthRequest(
			    $sMethod,
				$sUrl,
				$oConsumer,
				$oAccessToken
			);
			$oRequest->sign(
			    $_SESSION[$this->_sSessionKey]['signature_method'],
			    $oConsumer,
			    $oAccessToken
			);
			return $oRequest->execute($sBody);
		}
	}

?>