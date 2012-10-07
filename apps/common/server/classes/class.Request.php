<?
    /**
     * Wrapper for http request data
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class Request {
    	const METHOD_POST = 'post';
    	const METHOD_GET  = 'get';
    	
        public static function method() {
            return strtolower($_SERVER['REQUEST_METHOD']);
        }
        public static function isPost() {
            return self::method() == 'post';
        }
        public static function isGet() {
            return self::method() == 'post';
        }
        public static function hasParam($sParamName) {
            return isset($_REQUEST[$sParamName]);
        }
        public static function requireParam($sParamName, $sDefaultValue=null, $sExceptionText=null) {
            if (isset($_REQUEST[$sParamName])) {
                return $_REQUEST[$sParamName];
            } else {
                if ($sDefaultValue) {
                    return $sDefaultValue;
                } else {
                    if ($sExceptionText) {
                        throw new Exception($sExceptionText);
                    } else {
                        throw new Exception(Text::getText('Expected parameter '.$sParamName));
                    }
                }
            }
        }
        public static function getParam($sParamName) {
            if (isset($_REQUEST[$sParamName])) {
                return $_REQUEST[$sParamName];
            }
            return null;
        }
        public static function numberedParams() {
        	$aNumberedParams = array();
        	for($i=0; $i<count($_REQUEST); $i++) {
        		if (!isset($_REQUEST[$i])) {
        			break;
        		}
        		$aNumberedParams[$i] = $_REQUEST[$i];
        	}
        	return $aNumberedParams;
        }
    	public static function keyedParams() {
        	$aKeyedParams = array();
        	foreach ($_REQUEST as $sKey=>$sValue) {
        		if (preg_match('/[^0-9]/', $sKey)) {
        			$aKeyedParams[$sKey] = $sValue;
        		}
        	}
        	return $aKeyedParams;
        }
        public static function data() {
            return $_REQUEST;
        }
    }

?>