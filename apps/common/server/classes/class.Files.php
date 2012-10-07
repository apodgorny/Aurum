<?

 	class Files extends Object {

 		/****************** PRIVATE *******************/
 		
 		/**
 		 * Does $s match at least one of the patterns in $aPatterns?
 		 * @param array    $aPatterns   Array of pattern strings
 		 * @param string   $s           String to match
 		 * @return boolean
 		 */
		private static function _arrayMatch($aPatterns, $s) {
			foreach ($aPatterns as $sPattern) {
				if (preg_match('/'.$sPattern.'/', $s, $a)) {
					return true;
				}
			}
			return false;
		}
		
		private static function _normalizePath($sPath) {
		    return preg_replace('/\/$/', '', $sPath);
	    }
 		/****************** PUBLIC ********************/
 		
 		/**
 		 * Retrieves contents of a file or dies
 		 * @param string   $sFilePath 
 		 * @param boolean  $bVerbose 
 		 * @return void
 		 */
		public static function openFile($sFilePath, $bVerbose=false) {
			if (file_exists($sFilePath)) {
				return file_get_contents($sFilePath);
			}
			if ($bVerbose) { print ('File '.$sFilePath.' does not exist'); }
			die();
		}
		
		/**
		 * Save contents to file? If file exists it will be overwritten
		 * @param string   $sFilePath 
		 * @param string   $sContents 
		 * @param boolean  $bVerbose 
		 * @return void
		 */
		public static function saveFile($sFilePath, $sContents, $bVerbose=false) {
			if ($oFile = fopen($sFilePath, 'w')) {
				fwrite($oFile, $sContents);
				fclose($oFile);
			} else {
				if ($bVerbose) { print ('Failed to open file for writing: '.$sFilePath); }
				die();
			}
		}
		
		/**
		 * Renames file
		 * @param string   $sOldFileName 
		 * @param string   $sNewFileName 
		 * @return void
		 */
		public static function renameFile($sParentDir, $sOldFileName, $sNewFileName) {
		    return rename($sParentDir.$sOldFileName, $sParentDir.$sNewFileName);
	    }
		
		/**
		 * Creates directories and sets the mod to 777
		 * @param array    $aDirs       Array of directories
		 * @param boolean  $bVerbose    Describe what's happening to STDOUT?
		 * @return void
		 */
		public static function createDirectories($aDirs, $bVerbose=false) {
			if ($bVerbose) { print "<br><b>Creating directories</b><br><br>"; }

			foreach ($aDirs as $sDir) {
				if (!file_exists($sDir)) {
					if ($bVerbose) { print 'Creating <i>'.$sDir.'</i> ... '; }
					if (mkdir($sDir, 0777, true)) {
						if ($bVerbose) { print 'Success!'; }
					} else {
						if ($bVerbose) { print 'Failed!'; }
					}
				} else {
					if ($bVerbose) { print 'Already exists: <i>'.$sDir.'</i>'; }
				}
				if ($bVerbose) { print '<br>'; }
			}
		}
		
		/**
		 * Create files and write contents
		 * @param array    $aFiles      array of filename=>filecontents
		 * @param boolean  $bVerbose    if set to true, description of actions performed are sent to stdout
		 * @return void
		 */
		public static function createFiles($aFiles, $bVerbose=false) {
			if ($bVerbose) { print "<br><b>Creating files</b><br><br>"; }

			foreach ($aFiles as $sFileName=>$sContents) {
				if (!file_exists($sFileName)) {
					if ($bVerbose) { print 'Creating <i>'.$sFileName.'</i> ... '; }
					if ($oFile = fopen($sFileName, 'w')) {
						if (fwrite($oFile, $sContents)) {
							if ($bVerbose) { print 'Success!'; }
						} else {
							if ($bVerbose) { print 'Failed!'; }
						}
						fclose($oFile);
					} else {
						if ($bVerbose) { print 'Failed!'; }
					}
				} else {
					if ($bVerbose) { print 'Already exists: <i>'.$sFileName.'</i>'; }
				}
				if ($bVerbose) { print '<br>'; }
			}
		}
		
		/**
		 * Obtains a list of subdirectories in the sParentDir
		 * @param string   $sParentDir          Directory path
		 * @param array    $aExcludedEntries    Array of patterns of files to exclude
		 * @return Array with keys set as directory names
		 */
		public static function getDirectories($sParentDir, $aExcludedEntries=array(), $aMustHaveEntries=array()) {
			$aDirs = array();
			$oParentDir = dir($sParentDir);
			while (false !== ($sEntry = $oParentDir->read())) {
				if (self::_arrayMatch($aExcludedEntries, $sEntry)) { continue; }
				if ($aMustHaveEntries && !self::_arrayMatch($aMustHaveEntries, $sEntry)) { continue; }
				if (!is_dir($sParentDir.$sEntry)) { continue; }
				$aDirs[$sEntry] = array();
			}
			$oParentDir->close();
			return $aDirs;
		}
		
		/**
		 * Obtains a list of filenames in the sParentDir 
		 * @param string   $sParentDir          Directory to find files in
		 * @param array    $aExcludedEntries    Array of patterns of files to skip
		 * @param array    $aMustHaveEntries    If present only those matching the patterns in this array will be returned
		 * @return  Array with keys set as matching filenames
		 */
		public static function getFiles($sParentDir, $aExcludedEntries=array(), $aMustHaveEntries=array()) {
			$aFiles = array();
			$oParentDir = dir($sParentDir);
			while (false !== ($sEntry = $oParentDir->read())) {
				if (self::_arrayMatch($aExcludedEntries, $sEntry)) { continue; }
				if ($aMustHaveEntries && !self::_arrayMatch($aMustHaveEntries, $sEntry)) { continue; }
				if (is_dir($sParentDir.$sEntry)) { continue; }
				$aFiles[$sEntry] = array();
			}
			$oParentDir->close();
			return $aFiles;
		}
		
		/**
		 * Derives extension of a file
		 * @param string   $sFileName   File name
		 * @return string lowercased file extension
		 */
		public static function getExtension($sFileName) {
		    preg_match('/\.([^\.]*)$/', $sFileName, $aMatch);
		    if (isset($aMatch[1])) {
		        return strtolower($aMatch[1]);
	        }
		    return null;
	    }
	    
	    /**
	     * Evaluates php tags embedded into a text file
	     * @param string   $sFileName   Flie name
	     * @return string   Evaluated contents of the file
	     */
	    public static function evalFile($sFileName) {
			ob_start();
			ob_flush();
			eval('?>' . file_get_contents($sFileName) . '<?');
			$s = ob_get_contents();
			ob_clean();
			return $s;  
		}
		
		public static function copy($sSrc, $sDest, $aExcludedEntries=array(), $aMustHaveEntries=array()) {
		    Debug::log($sSrc, $sDest);
		    $sSrc  = self::_normalizePath($sSrc);
		    $sDest = self::_normalizePath($sDest);
		    if (!file_exists($sDest)) { throw new Exception('Destination directory '.$sDest.' does not exist'); }
		    if (!is_dir($sDest))      { throw new Exception('Destination must be a directory'); }
		    
		    $aExcludedEntries = array_unique(array_merge($aExcludedEntries, array('^\.$', '^\.\.$')));
		    $aPathPieces      = explode('/', $sSrc);
	        $sEndOfPath       = end($aPathPieces);
		    
		    if (is_dir($sSrc)) {
		        $aEntries = array_merge(
		            array_keys(self::getFiles($sSrc, $aExcludedEntries, $aMustHaveEntries)),
		            array_keys(self::getDirectories($sSrc, $aExcludedEntries, $aMustHaveEntries))
		        );
		        
		        $sNewDest = $sDest.'/'.$sEndOfPath;
		        self::createDirectories(array($sNewDest));
		        foreach ($aEntries as $sEntry) {
		            self::copy($sSrc.'/'.$sEntry, $sNewDest, $aExcludedEntries, $aMustHaveEntries);
	            }
	        } else if (is_file($sSrc)) {
	            copy($sSrc, $sDest.'/'.$sEndOfPath);
            }
	    }
	    
 	}
 	

?>