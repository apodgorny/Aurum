<?
    /**
     * Class JavaScript - does routines related to generating javascript
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class JavaScript extends Object {
        
        const SESSION_KEY = '___javascript___';
        
        /*************** PRIVATE ***************/
        
        private static $_nTotalComponents = 0;
        private static function __generateComponentName() {
            return 'component'.self::$_nTotalComponents;
        }
        private static function _formatObject($aObject, $nIndent=0) {
            $aKeys = array_keys($aObject);
            $nPad = 0;
            foreach ($aKeys as $sKey) {
                if ($nPad < strlen($sKey)) {
                    $nPad = strlen($sKey);
                }
            }
                
            $aObjectPairs = array();
            foreach ($aObject as $sKey=>$sValue) {
                $aObjectPairs[] = str_pad(Text::toCamelCase($sKey).':', $nPad).$sValue;
            }
            return 
                'App.Components = {'."\n\t".
                    implode(",\n\t", $aObjectPairs).
                "\n}\n";
        }

        /*************** PUBLIC ****************/
        
        public static function getComponents() {
            if (!isset($_SESSION[self::SESSION_KEY])) {
                $_SESSION[self::SESSION_KEY] = array();
            }
            $sInits = self::_formatObject($_SESSION[self::SESSION_KEY]);
            unset($_SESSION[self::SESSION_KEY]);
            
            return $sInits;
        }
        public static function initComponent($sComponentId, $sComponentType, $aParams=array()) {
            self::$_nTotalComponents ++;
            
            if (!trim($sComponentId)) {
                $sComponentId = self::_generateComponentName();
            }
            if (!isset($_SESSION[self::SESSION_KEY])) {
                $_SESSION[self::SESSION_KEY] = array();
            }
            $aProcessedParams = array();
            foreach ($aParams as $sKey=>$sValue) {
                if (is_string($sValue) && !preg_match('/[\[{]/', $sValue)) {
                    $aProcessedParams[$sKey] = '\''.$sValue.'\'';
                } else {
                    $aProcessedParams[$sKey] = $sValue;
                }
            }
            
            $sParams = implode(', ', $aProcessedParams);
            $_SESSION[self::SESSION_KEY][$sComponentId] = 'new '.$sComponentType.'('.$sParams.')';
        }
    }

?>