<?
	// TODO: Add support for mobile devices
	class UserAgent extends Object {
		
		const OPERA 	= 'OPERA';
		const WEBKIT	= 'WEBKIT';
		const IE		= 'IE';
		const IE_6		= 'IE_6';
		const IE_7		= 'IE_7';
		const IE_8		= 'IE_8';
		const GECKO		= 'GECKO';
		const UNKNOWN	= 'UNKNOWN';
		const ALL		= 'ALL';
		
		private static $_sBrowserCode = null;
		
		private static function _detectUserAgent() {
		    $sAgentString = '';
			if (isset($_SERVER['HTTP_USER_AGENT'])) {
	        	$sAgentString = strtolower($_SERVER['HTTP_USER_AGENT']);
			}
			
	        // Identify the browser. Check Opera and Safari first in case of spoof. Let Google Chrome be identified as Safari.
	        if (preg_match('/opera/', $sAgentString)) {
	            $sName = self::OPERA;
	        } else if (preg_match('/webkit/', $sAgentString)) {
	            $sName = self::WEBKIT;
	        } else if (preg_match('/msie/', $sAgentString)) {
	            $sName = self::IE;
	        } else if (preg_match('/mozilla/', $sAgentString) && !preg_match('/compatible/', $sAgentString)) {
	            $sName = self::GECKO;
	        } else {
	            $sName = self::UNKNOWN;
	        }
	        
			// What version?
	        if (preg_match('/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/', $sAgentString, $aMatches)) {
	            $nVersion = $aMatches[1];
	        } else {
	            $nVersion = self::UNKNOWN;
	        }

			self::$_sBrowserCode = $sName.'_'.$nVersion;
		}
		/************ PUBLIC **************/
		
		public static function is($sBrowserCode) {
			if (self::$_sBrowserCode === null) {
				self::_detectUserAgent();
			}
			return stripos($this->_sBrowserCode, $sBrowserCode) == 0;
		}
		
		public static function getCode() {
			if (self::$_sBrowserCode === null) {
				self::_detectUserAgent();
			}
			return self::$_sBrowserCode;
		}
	}
?>