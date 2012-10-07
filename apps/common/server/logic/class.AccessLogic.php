<?
    /**
     * Access Logic
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class AccessLogic {

        /**************** PRIVATE *******************/

        private static function _getAccessFileNames() {
            return array_keys(
                Files::getFiles(
                    $_ENV['PATH_TO_APPS'].'common/server/xml/',
                    array("^\."),
                    array("^access\.[^\.]+\.xml$")
                )
            );
        }
        private static function _match($sAccessExp, $aLevels) {
            $aExp = explode('/', $sAccessExp);
            for ($i=0; $i<count($aLevels); $i++) {
                if ($aExp[$i] == '*') {
                    continue;
                } else if ($aExp[$i] == $aLevels[$i]) {
                    continue;
                } else {
                    return false;
                }
            }
            return true;
        }
        private static function _groupHasAccess($sAccessFileName, $aLevels) {
            $sAccessFileName = $_ENV['PATH_TO_APPS'].'common/server/xml/'.$sAccessFileName;
            $oXML = simplexml_load_file($sAccessFileName);

            $bAllowed = false;
            foreach ($oXML as $sModifier=>$sExp) {
                $sExp = strtolower((string)$sExp);
                $sModifier = strtolower($sModifier);

                if (self::_match($sExp, $aLevels)) {
                    if ($sModifier == 'allow') { $bAllowed = true;  }
                    else                       { $bAllowed = false; }
                }
            }
            return $bAllowed;
        }
        private static function _isInGroupFile($sGroupName) {
            if (LoginLogic::hasLogin()) {
                $oMember = LoginLogic::getLoggedInMember();
                $sFilePath = $_ENV['PATH_TO_APPS'].'common/server/xml/group.'.ucfirst(strtolower($sGroupName)).'.xml';
                if (file_exists($sFilePath)) {
                    $oXML = simplexml_load_file($sFilePath);
                    foreach ($oXML as $sEmail) {
                        if ($oMember['email'] == $sEmail) {
                            return true;
                        }
                    }
                }
            }
            return false;
        }

        /**************** PUBLIC *******************/

        //////// PAGE LEVEL ACCESS RIGHTS ////////
        
        public static function hasGroup($sGroupName) {
            switch ($sGroupName) {
                case 'anonymous':
                    return true;
                case 'member':
                    return LoginLogic::hasLogin();
                default:
                    return self::_isInGroupFile($sGroupName);
            }
            return false;
        }

        public static function hasAccess($aLevels) {
            array_walk($aLevels, function(&$oElement) {
                $oElement = strtolower($oElement);
            });
            $aAccessFiles = self::_getAccessFileNames();
            foreach ($aAccessFiles as $sFileName) {
                list($sPrefix, $sGroupName) = explode('.', strtolower($sFileName));
                if (self::hasGroup($sGroupName)) {
                    if (self::_groupHasAccess($sFileName, $aLevels)) {
                        return true;
                    }
                }
            }
            return false;
        }
        public static function hasAccessHere() {
            return self::hasAccess(array($_ENV['APP'], $_ENV['CONTROLLER'],$_ENV['ACTION']));
        }
        public static function authorize() {
            if (!self::hasAccessHere()) {
                if (LoginLogic::hasLogin()) {
                    LoginLogic::extendLogin();
                    die (Dom::H1('Access denied'));
                } else {
                    LoginLogic::authenticate();
                }
            } else {
                if (LoginLogic::hasLogin()) {
                    LoginLogic::authenticate();
                }
            }
        }
        
        //////// RESOURCE LEVEL ACCESS RIGHTS ////////

       	public static function canCreate($sResourceType, $nMemberId=null) {
       	    $sModelClass = $sResourceType.'Model';
       	    $oModel = new $sModelClass();
       	    return $oModel->canCreate($nMemberId);
        }
        public static function canRead($sResourceType, $nResourceId, $nMemberId=null) {
       	    $sModelClass = $sResourceType.'Model';
       	    $oModel = new $sModelClass();
       	    return $oModel->canRead($nResourceId, $nMemberId);
        }
        public static function canUpdate($sResourceType, $nResourceId, $nMemberId=null) {
       	    $sModelClass = $sResourceType.'Model';
       	    $oModel = new $sModelClass();
       	    return $oModel->canUpdate($nResourceId, $nMemberId);
        }
        public static function canDelete($sResourceType, $nResourceId, $nMemberId=null) {
       	    $sModelClass = $sResourceType.'Model';
       	    $oModel = new $sModelClass();
       	    return $oModel->canDelete($nResourceId, $nMemberId);
        }
       
    }

?>