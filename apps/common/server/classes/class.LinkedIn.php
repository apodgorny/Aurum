<?
    /**
     * class LinkedIn
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class LinkedIn extends Object {

        const SESSION_KEY       = '___linkedin___';
        const URL_REQUEST_TOKEN = 'https://api.linkedin.com/uas/oauth/requestToken';
        const URL_AUTHORIZE     = 'https://api.linkedin.com/uas/oauth/authorize';
        const URL_ACCESS_TOKEN  = 'https://api.linkedin.com/uas/oauth/accessToken';
        const SIGNATURE_METHOD  = OAuth::SIGNATURE_METHOD_HMAC_SHA1;
        const CONSUMER_KEY      = '';
        const CONSUMER_SECRET   = '';

        const URL_GET_PROFILE       = 'https://api.linkedin.com/v1/people/~';
        const URL_GET_CONNECTIONS   = 'https://api.linkedin.com/v1/people/~/connections';
        const URL_SEND_MESSAGE      = 'https://api.linkedin.com/v1/people/~/mailbox';

    	/**************************** PRIVATE ******************************/

        private $_aDefaultProfileFields = array(
    		'first-name',
    		'last-name',
    		'headline',
    		'location',
    		'industry',
    		'summary',
    		'specialties',
    		'associations',
    		'honors',
    		'interests',
    		'positions',
    		'educations',
    		'member-url-resources',
    		'picture-url',
    		'public-profile-url'
    	);

    	private function _getFieldSelector($aFieldNames=array()) {
    	    $sFieldSelector = '';
    	    if ($aFieldNames) {
    	        $sFieldSelector = ':('.implode(',', $aFieldNames).')';
	        }
	        return $sFieldSelector;
	    }
	    
	    private static function _getEducationList($oXml) {
            $aEducations = array();
            $aData = array (
                'school-name'    => 'school-name',
                'degree'         => 'degree',
                'field-of-study' => 'field-of-study',
                'start-date'     => 'start-date/year',
                'end-date'       => 'end-date/year'
            );
            foreach($oXml->educations->education as $oItem) {
            	$aEdu = array();
                foreach($aData as $sKey => $sValue) {
                    $oTmp = $oItem->xpath($sValue);
                    if (isset($oTmp[0])) {
                        $aEdu[$sKey] = (string) $oTmp[0];
                    }
                }
                $aEducations[] = $aEdu;
            }
            return $aEducations;
        }

        private static function _getEducationString($aEducations) {
            $sColleges = '';
            $nNum = 1;
            foreach($aEducations as $aEdu) {
                $sColleges .= $nNum .'. ';
                if (isset($aEdu['school-name'])) {
                	$sColleges .= $aEdu['school-name'];
                }
                if (isset($aEdu['degree']) && trim($aEdu['degree'])) {
                    $sColleges .= ' / '.$aEdu['degree'].' degree';
                }
                if (isset($aEdu['field-of-study'])) {
                    $sColleges .= ' / '.$aEdu['field-of-study'];
                }
                if (isset($aEdu['start-date'])) {
                    $sColleges .= ' (' . $aEdu['start-date'];
                    if (isset($aEdu['end-date'])) {
                        $sColleges .= ' - ' . $aEdu['end-date'] . ")\n";
                    }
                }
                
                $nNum++;
            }
            return $sColleges;
        }

        private static function _getPositionList($oXml) {
            $aPositions = array();
            $aData = array (
                'company-name'   => 'company/name',
                'title'          => 'title',
                'start-year'     => 'start-date/year',
                'start-month'    => 'start-date/month',
                'end-year'       => 'end-date/year',
                'end-month'      => 'end-date/month'
            );
            foreach($oXml->positions->position as $oItem) {
                $aPos = array();
                foreach($aData as $sKey => $sValue) {
                    $oTmp = $oItem->xpath($sValue);
                    if (isset($oTmp[0])) {
                        $aPos[$sKey] = (string) $oTmp[0];
                    }
                }
                $aPositions[] = $aPos;
            }
            return $aPositions;
        }

        private static function _getPositionString($aPositions) {
            $sPositions = '';
            $nNum = 1;
            foreach($aPositions as $aPosition) {
                $sPosition = $nNum .'. ';
                if (isset($aPosition['company-name'])) {
                    $sPosition .= $aPosition['company-name'];
                }
                if (isset($aPosition['title'])) {
                    $sPosition .= ' / ' . $aPosition['title'];
                }
                if (isset($aPosition['start-year'])  && 
                   isset($aPosition['end-year'])
                ) {
                    $sStartMonth = isset($aPosition['start-month']) 
                        ? '/'.$aPosition['start-month']
                        : '';
                    $sEndMonth = isset($aPosition['start-month']) 
                        ? '/'.$aPosition['end-month']
                        : '';
                    
                    $sPosition .= 
                        ' ('.
                        $aPosition['start-year'].$sStartMonth.
                        ' - '.
                        $aPosition['end-year'].$sEndMonth.
                        ')';
                        
                }
                $nNum++;
                $sPositions .= $sPosition."\n";
            }
            return $sPositions;
        }
        
        private static function _getSummary($oXml) {
            $oTmp = $oXml->xpath('/person/summary');
            if (isset($oTmp[0])) {
                $sSummary = (string) $oTmp[0];
            } else {
            	$sSummary = "";
            }
            $sCompanies = self::_getPositionString(self::_getPositionList($oXml));
            $sColleges = self::_getEducationString(self::_getEducationList($oXml));
            $sCompleteSummary = "\n$sSummary\n\nI worked at:\n\n$sCompanies\nI studied at:\n\n$sColleges\n";
            return $sCompleteSummary;
        }

    	/**************************** PUBLIC ******************************/

        public function __construct() {}

        public function authorizeBegin($sUrlCallback) {
            $oAuth = new OAuth(self::SESSION_KEY);
            $oAuth->requestToken(
                array(
        		    'url'				=> self::URL_REQUEST_TOKEN,
            		'url_callback'		=> $sUrlCallback,
            		'key'				=> self::CONSUMER_KEY,
            		'key_secret'		=> self::CONSUMER_SECRET,
            		'signature_method'	=> self::SIGNATURE_METHOD
            	)
            );
        	$oAuth->authorize(self::URL_AUTHORIZE);
        }

        public function authorizeEnd() {
            $oAuth = new OAuth(self::SESSION_KEY);
            $aParams = $oAuth->accessToken(self::URL_ACCESS_TOKEN);
        }

        public function getProfileAsXml($aFieldNames=null) {
            $aFieldNames = $aFieldNames
                ? $aFieldNames
                : $this->_aDefaultProfileFields;

            $sFieldSelector = $this->_getFieldSelector($aFieldNames);
            $oAuth = new OAuth(self::SESSION_KEY);
            $oResponse = $oAuth->sendRequest(self::URL_GET_PROFILE.$sFieldSelector);
        	return $oResponse;
        }

        public function getProfileAsArray($aFieldNames=null) {
            $oProfile = $this->getProfileAsXml($aFieldNames);
            $aData = array();
            $oXml = simplexml_load_string($oProfile);

            $oTmp = $oXml->xpath('/person/industry');
            if (isset($oTmp[0])) {
                $aData['category'] = Text::toSeoName((string) $oTmp[0]);
            }
            $oTmp = $oXml->xpath('/person/first-name');
            if (isset($oTmp[0])) {
                $aData['first_name'] = (string) $oTmp[0];
            }
            $oTmp = $oXml->xpath('/person/last-name');
            if (isset($oTmp[0])) {
                $aData['last_name'] = (string) $oTmp[0];
            }
            $oTmp = $oXml->xpath('/person/headline');
            if (isset($oTmp[0])) {
                $aData['headline'] = (string) $oTmp[0];
            }
            $aData['summary'] = LinkedIn::_getSummary($oXml);
           return $aData;
        }

        public function getConnectionsAsXml($aFieldNames=null) {
            $sFieldSelector = $this->_getFieldSelector($aFieldNames);
            $oAuth = new OAuth(self::SESSION_KEY);
            $oResponse = $oAuth->sendRequest(self::URL_GET_CONNECTIONS.$sFieldSelector);
            return $oResponse;
        }

        public static function sendMessage($aRecipientIds, $sSubject, $sMessage) {

            $sRecipients = '';
            foreach ($aRecipientIds as $sId) {
                $sRecipients .= "
                    <recipient>
                      <person path=\"/people/id=$sId\" />
                    </recipient>
                ";
            }

            $sXml =
                "<?xml version='1.0' encoding='UTF-8'?>
                <mailbox-item>
                  <recipients>
                    <recipient>
                      <person path='/people/~'/>
                    </recipient>
                    $sRecipients
                  </recipients>
                  <subject>$sSubject</subject>
                  <body>$sMessage</body>
                </mailbox-item>";
            Debug::show($sXml);
            $oAuth = new OAuth(self::SESSION_KEY);
            $oResponse = $oAuth->sendRequest(self::URL_SEND_MESSAGE, OAuth::METHOD_POST, $sXml);
            Debug::show($oResponse);
        }
    }

?>
