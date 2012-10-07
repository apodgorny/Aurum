<?
    /**
     * Class Cookie
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class Cookie extends Object {
        public static function set($sName, $aValue, $aSettings=array()) {
            if (!is_array($aValue)) { 
                throw new Exception(Text::getText('Cookie values must be an array')); 
            }
            $aDefaultSettings = array(
                'expire'    => 0,
                'path'      => '',
                'domain'    => $_ENV['COOKIE_DOMAIN'],
                'secure'    => 0,
                'httponly'  => 0
            );
            $aSettings = array_merge($aDefaultSettings, $aSettings);
            $aValue = array(
                '__s'   => $aSettings,  // settins
                '__v'   => $aValue      // value
            );
            $sValue = base64_encode(json_encode($aValue));
            
            setrawcookie(
                $sName, 
                $sValue, 
                $aSettings['expire'],
                $aSettings['path'],
                $aSettings['domain'],
                $aSettings['secure'],
                $aSettings['httponly']
            );
        }
        public static function update($sName, $aValue) {
            if (!is_array($aValue)) { 
                throw new Exception(Text::getText('Cookie values must be an array')); 
            }
            $aOldValues = Cookie::get($sName);
            $aSettings  = Cookie::getSettings($sName);
            
            $aValue = array_merge($aOldValues, $aValue);
            Cookie::set($sName, $aValue, $aSettings);
        }
        public static function get($sName) {
            if (isset($_COOKIE[$sName])) {
                $aValue = json_decode(base64_decode($_COOKIE[$sName]), 1);
                if (isset($aValue['__v'])) {
                    return $aValue['__v'];
                }
            }
            return array();
        }
        public static function getSettings($sName) {
            if (isset($_COOKIE[$sName])) {
                $aValue = json_decode(base64_decode($_COOKIE[$sName]), 1);
                if (isset($aValue['__s'])) {
                    return $aValue['__s'];
                }
            }
            return array();
        }
        public static function delete($sName) {
            setcookie($sName, '', time()-3600);
        }
    }

?>  