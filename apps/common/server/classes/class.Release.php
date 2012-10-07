<?
    /**
     * class Release
     * Handles all release-related routines
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

 	class Release {

 		/***************** CSS Handling **************/

 		public static function getCssIncludes($sAppName) {
 			$aTemp = $_ENV['CSS_INCLUDES'];
 			include $_ENV['PATH_TO_APPS'].$sAppName.'/server/settings/cssincludes.php';
 			$aCssIncludes = $_ENV['CSS_INCLUDES'];
 			$_ENV['CSS_INCLUDES'] = $aTemp;
 			return $aCssIncludes;
 		}
 		public static function getUserAgentVariations($sAppName) {
 			$sCss = self::getCompiledCss($sAppName, UserAgent::ALL);
 			$aVariations = array();
 			while (preg_match('/\[([^\]]+)\]/', $sCss, $aMatches)) {
 				$aVariations[] = $aMatches[1];
 				$sCss = str_replace($aMatches[0], '', $sCss);
 			}
 			if (!in_array(UserAgent::UNKNOWN, $aVariations)) {
 				$aVariations[] = UserAgent::UNKNOWN;
 			}
 			return $aVariations; 
 		}
 		public static function getCompiledCss($sAppName, $sBrowserCode=UserAgent::UNKNOWN, $bMinify=false) {
 			$aCssIncludes = self::getCssIncludes($sAppName);
 			$sCss = '';
 			$sMinify = $bMinify ? '&min=1' : '';
 			foreach ($aCssIncludes as $sCssFileName) {
 				$sCssFileName = $_ENV['MVC_ROOT'].$sAppName.'/resource/'.$sCssFileName.'?ua='.$sBrowserCode.$sMinify;
 				$sCss .= file_get_contents($sCssFileName);
 			}
 			return $sCss;
 		}
 		public static function saveCompiledCss($sAppName, $sBrowserCode=UserAgent::UNKNOWN, $bMinify=true) {
 			$sCompiledFileName = $_ENV['PATH_TO_APPS'].$sAppName.'/client/css/compiled.'.strtolower($sBrowserCode).'.css';
 			$sCss = self::getCompiledCss($sAppName, $sBrowserCode, $bMinify);
 			Files::saveFile($sCompiledFileName, $sCss);
 			Debug::show('Writing ... '.$sCompiledFileName);
 		}
 		public static function compileAllCss() {
 			$aAppNames = array_keys(Aurum::getApplications());
 			foreach ($aAppNames as $sAppName) {
 				$aUserAgentVariations = self::getUserAgentVariations($sAppName);
 				foreach ($aUserAgentVariations as $sBrowserCode) {
 					self::saveCompiledCss($sAppName, $sBrowserCode);
 				}
 			}
 		}

 		/***************** JS Handling *****************/

 		public static function getJsIncludes($sAppName) {
 			$aTemp = $_ENV['JS_INCLUDES'];
 			include $_ENV['PATH_TO_APPS'].$sAppName.'/server/settings/jsincludes.php';
 			$aJsIncludes = $_ENV['JS_INCLUDES'];
 			$_ENV['JS_INCLUDES'] = $aTemp;
 			return $aJsIncludes;
 		}
 		public static function getCompiledJs($sAppName, $bMinify=false) {
 			$aJsIncludes = self::getJsIncludes($sAppName);
 			$sJs = '';
 			$sMinify = $bMinify ? '?min=1' : '';
 			foreach ($aJsIncludes as $sJsFileName) {
 				$sJsFileName = $_ENV['MVC_ROOT'].$sAppName.'/resource/'.$sJsFileName.$sMinify;
 				$sContents = file_get_contents($sJsFileName);
 				$sJs .= "\n\n".$sContents;
 			}
 			return $sJs;
 		}
 		public static function saveCompiledJs($sAppName, $bMinify=true) {
 			$sCompiledFileName = $_ENV['PATH_TO_APPS'].$sAppName.'/client/js/compiled.js';
 			$sJs = self::getCompiledJs($sAppName, $bMinify);
 			Files::saveFile($sCompiledFileName, $sJs);
 			Debug::show('Writing ... '.$sCompiledFileName);
 		}
 		public static function compileAllJs() {
 			$aAppNames = array_keys(Aurum::getApplications());
 			foreach ($aAppNames as $sAppName) {
 				self::saveCompiledJs($sAppName);
 			}
 		}
 		public static function build() {
 		    Release::compileAllCss();
			Release::compileAllJs();
	    }
	    public static function stage() {
        }
        public static function production() {
        }
        
 	}

?>