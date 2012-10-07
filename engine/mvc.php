<?
	class MVC {

		/**************** PRIVATE ****************/

		/**
		 * Serve css file through php
		 */
		private static function _css($sModule) {
			$sBrowserCode = isset($_REQUEST['ua'])
				? $_REQUEST['ua']
				: UserAgent::getCode();
			$bMinify = isset($_REQUEST['min']) && $_REQUEST['min'];

			if (!preg_match('/\.css$/', $sModule)) {
				$sModule .= '.css';
			}
			header("Content-Type: text/css");
			foreach ($_ENV['AUTOLOAD_CSS_PATHS'] as $sPath) {
				$sFileName = $sPath.$sModule;
				if (file_exists($sFileName)) {
				    Debug::log('Requesting: '.$sFileName);
				    $sCss = Files::evalFile($sFileName);
					$oCSSParser = new CSSParser($sFileName, $sCss);
					$nExiprationInterval = 60*60*24*14;
                    header('Pragma: public');
                    header('Cache-Control: maxage='.$nExiprationInterval);
                    header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$nExiprationInterval) . ' GMT');
					print $oCSSParser->getCSSForBrowser($sBrowserCode, $bMinify);
				}
			}
			exit();
		}
		/**
		 * Serve js file through php
		 */
		private static function _js($sModule) {
			$bMinify = isset($_REQUEST['min']) && $_REQUEST['min'];

			if (!preg_match('/\.js$/', $sModule)) {
				$sModule .= '.js';
			}
			header("Content-Type: text/javascript");
			foreach ($_ENV['AUTOLOAD_JS_PATHS'] as $sPath) {
				$sFileName = $sPath.$sModule;
				if (file_exists($sFileName)) {
				    $nExiprationInterval = 60*60*24*14;
                    header('Pragma: public');
                    header('Cache-Control: maxage='.$nExiprationInterval);
                    header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$nExiprationInterval) . ' GMT');
                    if (!$bMinify) {
						include $sFileName;
                    } else {
                    	$sJs = file_get_contents($sFileName);
                        $sJs = JsMinifier::minify($sJs);
                        //$sJs = JavaScriptPacker::construct($sJs, 'Normal', true, false)->pack();
                        //$sJs = str_replace(chr(239), '', $sJs);
                    	print $sJs;
                    }
					exit();
				}
			}
			exit();
		}
		/**
		 * Serve img file through php
		 */
		private static function _img($sFileName) {
		    //Debug::profileClear();
		    //Debug::profile($sFileName);
		    $sExtension = 'jpeg';
		    preg_match('/\.([^\.]*)$/', $sFileName, $aMatches);
		    if (isset($aMatches[1]))    { $sExtension = strtolower($aMatches['1']);}
		    if ($sExtension == 'jpg')   { $sExtension = 'jpeg'; }
			foreach ($_ENV['AUTOLOAD_IMG_PATHS'] as $sPath) {
				$sFilePath = $sPath.$sFileName;
				if (file_exists($sFilePath)) {
                    header('Content-Type: image/'.$sExtension);
                    header('Content-Length: 100' . sprintf('%u', filesize($sFilePath)));
                    $nExiprationInterval = 60*60*24*14;
                    header('Pragma: public');
                    header('Cache-Control: maxage='.$nExiprationInterval);
                    header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$nExiprationInterval) . ' GMT');
                    fpassthru(fopen($sFilePath, 'rb'));
              //    Debug::profile($sFileName.'_end');
              //    Debug::profileShow();
    				ob_flush();
    				ob_end_clean();
                    exit();
				}
			}
		}
		/**
		 *	Turns $_SERVER['REQUEST_URI'] into correct request variables
		 */
		private static function _processRequest() {
		    $sPathQuery = '';
		    $sQueryQuery = '';

			$sRequestPath = preg_replace('/\/[^\/]*$/', '/', $_SERVER['SCRIPT_NAME']);
			list(,$sQuery) = explode($sRequestPath, $_SERVER['REQUEST_URI'], 2);
			$sQuery = trim($sQuery, '/');
			list($sPathQuery, $sQueryQuery) = explode('?', $sQuery);
			$aElements = explode('/', trim($sPathQuery, '/'));

			// DERIVE APPLICATION NAME
			if (!isset($_REQUEST['app']) || !$_REQUEST['app']) { $_REQUEST['app'] = 'site'; }
			if (isset($aElements[0]) && $aElements[0]) {
				list($sAppName) = explode('.', $aElements[0]);
				$_REQUEST['app'] = strtolower($sAppName);
				array_shift($aElements);
			}

			// DERIVE CONTROLLER NAME
			if (!isset($_REQUEST['page']) || !$_REQUEST['page']) { $_REQUEST['page'] = 'home'; }
			if (isset($aElements[0]) && $aElements[0]) {
				$_REQUEST['page'] = $aElements[0];
				array_shift($aElements);
			}

			// DERIVE CONTROLLER NAME
			if (!isset($_REQUEST['action']) || !$_REQUEST['action']) { $_REQUEST['action'] = 'index'; }
			if (isset($aElements[0])) {
				$_REQUEST['action'] = $aElements[0];
				array_shift($aElements);
			}
			$aQueryPairs = explode('&', $sQueryQuery);
			$aQueryElements = array();
			foreach ($aQueryPairs as $sPair) {
				list($sKey, $sValue) = explode('=', $sPair);
				if (trim($sValue)) {
					$aQueryElements[$sKey] = $sValue;
					if (trim($sKey) != 'page' && trim($sKey) != 'action' && trim($sKey) != 'app' && trim($sValue)) {
						$aElements[] = $sValue;
					}
				}
			}
			$_REQUEST = array_merge($_REQUEST, $aElements, $aQueryElements);
		}

		private static function _getCompiledCssFile() {
		    $sBrowserCode = strtolower(UserAgent::getCode());
		    $sCssDir  = $_ENV['PATH_TO_APP'].'/client/css';
		    $aFiles   = array_keys(Files::getFiles($sCssDir, array(), array('^compiled\.')));
		    if (count($aFiles) == 1) { return $aFiles[0]; }

	        $sBestMatchFile = 'compiled.unknown.css';
	        $nBestMatch = 0;
		    foreach ($aFiles as $sFile) {
		        list($sCompiled, $sBrowser) = explode('.', $sFile);
		        $nCount = min(count($sBrowser), count($sBrowserCode));
		        $n = 0;
		        for (; $n<$nCount; $n++) {
		            if ($sBrowser[$n] != $sBrowserCode[$n]) {
		                break;
	                }
	            }
	            if ($n > $nBestMatch) {
	                $sBestMatchFile = $sFile;
	                $nBestMatch = $n;
                }
	        }
	        return $sBestMatchFile;
	    }

		/**************** PUBLIC ****************/

		public static $oController = null;

		public static function autoload($sClassName) {
		    $bLoaded = false;
                    if(false && stristr($sClassName, 'Model')) {
                         print('trying to load class '.$sClassName.'<br>');
                    }

    		foreach ($_ENV['AUTOLOAD_PATHS'] as $sAutoloadPath) {
    		    if ($bLoaded) { break; }
    			foreach ($_ENV['AUTOLOAD_PREFIXES'] as $sPrefix) {
    				$sFilePath = $sAutoloadPath.$sPrefix.$sClassName.'.php';
    				if (file_exists($sFilePath)) {
    					require_once $sFilePath;
    					$bLoaded = true;
    					break;
    				}
    			}
    		}
    		if (!$bLoaded) {
    		    // Implied classes
    		    if (preg_match('/Record$/', $sClassName)) {
    		        eval("class $sClassName extends Record {}");
    		    } else if (preg_match('/RecordList$/', $sClassName)) {
        		    eval("class $sClassName extends RecordList {}");
        		} else if (preg_match('/Model$/', $sClassName)) {
        		    eval("class $sClassName extends Model {}");
    		    } else if (preg_match('/ModelList$/', $sClassName)) {
            	    eval("class $sClassName extends ModelList {}");
        		}
		    }
		    if (!class_exists($sClassName)) {
		        throw new Exception('Class '.$sClassName.' not found');
	        }
		}

		public static function getHttpRoot() {
			/*** DERIVE MVC_ROOT DIRECTORY ***/
			list($sProtocol, $sVersion) = explode('/', $_SERVER['SERVER_PROTOCOL']);
			$sProtocol = strtolower($sProtocol);
			$sPort = ($_SERVER['SERVER_PORT'] == '80' ? '' : ':'.$_SERVER['SERVER_PORT']);
			list($_ENV['HTTP_HOST']) = explode(':', $_SERVER['HTTP_HOST']);
			return $sProtocol.'://'.$_ENV['HTTP_HOST'].$sPort;
		}

		public static function getMvcRoot() {
			/*** DERIVE MVC_ROOT RELATIVE PATHS ***/
			$aScriptPathPieces = explode('/',$_SERVER['SCRIPT_NAME']);
			array_pop($aScriptPathPieces);
			return $_ENV['HTTP_ROOT'].implode('/', $aScriptPathPieces).'/';
		}

		public static function getEnvironmentName() {
			if (!file_exists($_ENV['ENVIRONMENT_FILENAME'])) {
				die ('Please create file: '.$_ENV['ENVIRONMENT_FILENAME'].' containing the name of your environment');
			}
			return strtolower(trim(file_get_contents($_ENV['ENVIRONMENT_FILENAME'])));
		}

		public static function includeEnvironmentConfiguration() {
			if (!file_exists('engine/config.'.$_ENV['ENVIRONMENT'].'.php')) {
				die ('Please create file: engine/config.'.$_ENV['ENVIRONMENT'].'.php, containing optional settings for your environment');
			}
			require_once 'engine/config.'.$_ENV['ENVIRONMENT'].'.php';
		}

		public static function getBuildId() {
		    if (!file_exists($_ENV['BUILD_FILENAME'])) {
		        die ('Please create file: '.$_ENV['BUILD_FILENAME'].' containing the number id of the current build');
	        }
	        $nBuildId = file_get_contents($_ENV['BUILD_FILENAME']);
	        return str_pad((int)$nBuildId, 6, '0', STR_PAD_LEFT);
	    }

	    public static function setBuildId($nBuildId) {
	        $oFile = fopen($_ENV['BUILD_FILENAME'], 'w');
	        fwrite($oFile, str_pad((int)$nBuildId, 6, '0', STR_PAD_LEFT));
	        fclose($oFile);
        }

		public static function includeApplicationConfiguration() {
			/*** APPLICATION-SPECIFIC CONFIGURATION ***/
			require_once $_ENV['PATH_TO_APP'].'server/settings/config.php';
			/*** COMPANY-SPECIFIC CONFIGURATION ***/
			require_once $_ENV['PATH_TO_COMMON'].'server/settings/config.php';
		}

		public static function addIncludePaths() {
		    set_include_path(
		        get_include_path() .
		        PATH_SEPARATOR .
		        implode(PATH_SEPARATOR, $_ENV['INCLUDE_PATHS'])
		    );
	    }

		public static function run() {
		    error_reporting(E_ALL ^ E_NOTICE);
		    session_start();
			ob_start();

			self::_processRequest();

			require_once 'errors.php';
			require_once 'config.php';

			/*** This is a request to a resource ***/
			if ($_REQUEST['page'] == 'resource') {
			    $sExtension = Files::getExtension($_REQUEST['action']);
				switch ($sExtension) {
					default:
					case 'css':
						self::_css($_REQUEST['action']);
						break;
					case 'js':
						self::_js($_REQUEST['action']);
						break;
					case 'png':
					case 'gif':
					case 'jpg':
					case 'jpeg':
					default:
					    self::_img($_REQUEST['action'], $sExtension);
						break;
				}
				ob_end_clean();
				ob_flush();
				exit();
			}

			/*** This is a request to a page ***/
			try {

    			require_once $_ENV['PATH_TO_APP'].'server/settings/cssincludes.php';
    			require_once $_ENV['PATH_TO_APP'].'server/settings/jsincludes.php';

    			// Check if a controller file exists
    			QA::assertFileExists(
    				$_ENV['PATH_TO_CONTROLLER'],
    				Text::getText('Controller does not exist: '.$_ENV['PATH_TO_CONTROLLER'])
    			);
    			// Include controller file
    			require_once $_ENV['PATH_TO_CONTROLLER'];

    			// Check if controller class is present in controller file
    			QA::assertClassExists($_ENV['CONTROLLER_CLASS']);

    	        // Instantiate controller
    			self::$oController = new $_ENV['CONTROLLER_CLASS'];

    			AccessLogic::authorize();

    			// Call action
    			$aActionParams = Request::numberedParams();
    			call_user_func_array(array(self::$oController, '_'.$_ENV['ACTION']), $aActionParams);

            } catch (Exception $oException) {
    		    if ($_ENV['DISPLAY_EXCEPTIONS']) {
    		        throw $oException;
		        } else {
                    Debug::log(
                        'Error: ',
                        '==========================================',
                        array(
                            'Message' => $oException->getMessage(),
                            'File'    => $oException->getFile(),
                            'Line'    => $oException->getLine(),
                        ),
                        'Trace:',
                        '==========================================',
                        $oException->getTrace()
                    );
                    Navigation::redirect($_ENV['APP'], $_ENV['DEFAULT_CONTROLLER'], $_ENV['DEFAULT_ACTION']);
	            }
		    }

			ob_flush();
		}

		public static function getFullUrl() {
			$sPort = $_SERVER['SERVER_PORT'] != '80'
				? ':'.$_SERVER['SERVER_PORT']
				: '';

			$sProtocol = strstr('https', strtolower($_SERVER['SERVER_PROTOCOL']))
			 	? 'https'
				: 'http';

			return $sProtocol.'://'.$_SERVER['SERVER_NAME'].$sPort.$_SERVER['REQUEST_URI'];
		}

		public static function getCSSIncludes() {

			$sCSSTag = '';
			if (isset($_ENV['USE_COMPILED_CSS']) && $_ENV['USE_COMPILED_CSS']) {
			    $sFileName = self::_getCompiledCssFile();
			    $sSrc = $_ENV['MVC_ROOT'].$_ENV['APP'].'/resource/'.$sFileName;
				$sCSSTag .= "\n\t\t" . Dom::LINK(array(
					'rel'  => 'stylesheet',
					'type' => 'text/css',
					'href'  => $sSrc
				));
		    } else {
    			foreach ($_ENV['CSS_INCLUDES'] as $sCSSFile) {
    				$sSrc = $_ENV['MVC_ROOT'].$_ENV['APP'].'/resource/'.$sCSSFile;
    				$sCSSTag .= "\n\t\t" . Dom::LINK(array(
    					'rel'  => 'stylesheet',
    					'type' => 'text/css',
    					'href'  => $sSrc
    				));
    			}
			}
			return $sCSSTag;
		}

		public static function getJSIncludes() {
			$sJSTags = '';
			if (isset($_ENV['USE_COMPILED_JS']) && $_ENV['USE_COMPILED_JS']) {
			    $sSrc = $_ENV['MVC_ROOT'].$_ENV['APP'].'/resource/compiled.js';
				$sJSTags .= "\n\t\t" . Dom::SCRIPT(array(
					'src'  => $sSrc
				), '');
		    } else {
    			foreach ($_ENV['JS_INCLUDES'] as $sJSFile) {
    				$sSrc = $_ENV['MVC_ROOT'].$_ENV['APP'].'/resource/'.$sJSFile;
    				$sJSTags .= "\n\t\t" . Dom::SCRIPT(array(
    					'src'  => $sSrc
    				), '');
    			}
    		}
			return $sJSTags;
		}
	}

?>
