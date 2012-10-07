<?
    /**
     * class Navigation
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

 	class Navigation extends Object {
		public static function toQueryString(Array $a) {
			$aPairs = array();
			foreach ($a as $sKey=>$sValue) {
				$aPairs[] = trim($sKey).'='.trim($sValue);
			}
			return implode('&', $aPairs);
		}
		public static function setUrlParams($sUrl, $aParams) {
		    if (strstr($sUrl, '?')) {
		        list($sBaseUrl, $sQuery) = explode('?', $sUrl);
    		    if (trim($sQuery)) {
        		    $aParamPairs = explode('&', $sQuery);
        		    $aBaseParams = array();
        		    foreach ($aParamPairs as $sParamPair) {
        		        list($sKey, $sValue) = explode('=', $sParamPair);
        		        $aBaseParams[$sKey] = $sValue;
        	        }
        	        $aParams = array_merge($aBaseParams, $aParams);
    	        }
    	    } else {
    	        $sBaseUrl = $sUrl;
	        }
	        return $sBaseUrl.'?'.self::toQueryString($aParams);
	    }
 		public static function link($sApp, $sPage='', $sAction='', $aParams=array()) {
 			if (isset($aParams['app'])) 	{ $sApp     = $aParams['app'];      }
 			else if (!$sApp)                { $sApp     = $_ENV['APP'];         }
 			if (isset($aParams['page'])) 	{ $sPage    = $aParams['page'];     }
 			else if (!$sPage)               { $sPage    = $_ENV['CONTROLLER'];  }
 			if (isset($aParams['action'])) 	{ $sAction  = $aParams['action'];   }
 			else if (!$sAction)             { $sAction  = $_ENV['ACTION'];      }
 			
 			$sApp    = trim(strtolower($sApp));
			$sPage   = trim(strtolower($sPage));
			$sAction = trim(strtolower($sAction));
			
			$sPage   = $sPage   ? $sPage    : $_ENV['DEFAULT_CONTROLLER'];
			$sAction = $sAction ? $sAction  : $_ENV['DEFAULT_ACTION'];
			
			$aNumberedParams = array();
			
			for($i=count($aParams); $i>=0; $i--) {
			    if (isset($aParams[$i])) {
			        $aNumberedParams[] = $aParams[$i];
			        unset($aParams[$i]);
		        }
		    }
		    
			$sQuery = $aParams ? self::toQueryString($aParams) : '';
			$sQuery = $sQuery ? '?'.$sQuery : ''; 
			
			$sNumberedParams = implode('/', array_reverse($aNumberedParams));
			
			return $_ENV['MVC_ROOT'].$sApp.'/'.$sPage.'/'.$sAction.'/'.$sNumberedParams.$sQuery;
 		}
 		/**
 		 * Same as link() except the servername is replaced with 127.0.0.1
 		 * For parameters see link()
 		 * @return void
 		 */
 		public static function localLink($sApp, $sPage='', $sAction='', $aParams=array()) {
 		    $sLink = self::link($sApp, $sPage, $sAction, $aParams);
 		    if (preg_match('/http[s]?:\/\/([^:\/]+)/', $sLink, $aMatches)) {
 		        $aLinkParts = explode($aMatches[1], $sLink, 2);
 		        $sLink = implode('127.0.0.1', $aLinkParts);
	        }
	        return $sLink;
	    }
	    /**
 		 * Same as link() except the servername is replaced with $_ENV['HOST_NAME']
 		 * Used in emails
 		 * For parameters see link()
 		 * @return void
 		 */
 		public static function externalLink($sApp, $sPage='', $sAction='', $aParams=array()) {
 		    $sLink = self::link($sApp, $sPage, $sAction, $aParams);
 		    if (preg_match('/http[s]?:\/\/([^:\/]+)/', $sLink, $aMatches)) {
 		        $aLinkParts = explode($aMatches[1], $sLink, 2);
 		        $sLink = implode($_ENV['HOST_NAME'], $aLinkParts);
	        }
	        return $sLink;
	    }
		public static function redirect($sApp, $sPage='', $sAction='', $aParams=array()) {
			if (count(func_get_args()) == 1 && preg_match('/^http/', $sApp)) {
				$sURL = $sApp;
			} else {
				$sURL = self::link($sApp, $sPage, $sAction, $aParams);
			}
			Navigation::redirectToUrl($sURL);
		}
		public static function redirectToUrl($sURL) {
		    if (strstr($sURL, "\n")) {
		        throw new Exception('Url contains newline');
	        }
		    header('Location: '.trim($sURL));
			exit();
	    }
		public static function linkHere() {
		    return $_ENV['FULL_URL'];
	    }
 	}
 	

?>