<?
    /**
     * class Facebook
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class Facebook extends Object {

        const SESSION_KEY           = '___facebook___';
        
        public static APP_NAME      = $_ENV['FB_APP_NAME'];
        public static APP_ID        = $_ENV['FB_APP_ID'];
        public static APP_KEY       = $_ENV['FB_APP_KEY'];
        public static APP_SECRET    = $_ENV['FB_APP_SECRET'];
        
    	/**************************** PRIVATE ******************************/
    	/**************************** PUBLIC *******************************/
    	
    	public static function getCookie() {
            $aArguments = array();
            parse_str(trim($_COOKIE['fbs_' . self::APP_ID], '\\"'), $aArguments);
            ksort($aArguments);
            $sPayload = '';
            foreach ($aArguments as $sKey => $sValue) {
                if ($sKey != 'sig') {
                    $sPayload .= $sKey . '=' . $sValue;
                }
            }
            if (md5($sPayload . self::APP_SECRET) != $aArguments['sig']) {
                return null;
            }
            return $aArguments;
        }
  }
  
?>