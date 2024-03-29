<?

	/**
	*  TAG EXAMPLES:
	*  [IE] - All IE Versions
	*  [IE6] - IE Versions from 6.000 to 6.999
	*  [IE7+] - All IE Versions from 7.000 up
	*  [IE7-] - All IE Versions 6.999 and down
	*
	*  Browser classes supported:
	*  IE - Internet Explorer
	*  GECKO - FireFox, Netscape
	*  WEBKIT - Safari, Chrome
	*  OPERA - Opera
	*
	*	Example of use:
	*  .myclass {
	*    width: 32px [IE6];
	*    width: 33px [IE7];
	*    width: 34px [IE8+];
	*    width: 35px; /* All other browsers excluding the above
	*    height: 100px; /* All browsers
	*  }
	*  NOTE: order matters - the last matching property will be inserted, all previous will be ignored
	*  NOTE: use of css hacks is strongly discouraged - brackets [], braces {}, semicolons ;, and colons :
	*  		 used in other than standard way WILL result in incorrect parsing.
	*
	*  Will render 32,33,34px for IEs respectively and 35px for all other browsers.
	*
	*/

 	class CSSParser extends Object {
 		
 		/******************** PRIVATE ********************/
 		
		private $_sCss 	    = '';
		private $_sRawCss   = '';
		private $_aBlocks   = array();
		private $_sUnique   = '~&&~%%~';
		private $_sFileName = '';

		private function _removeNewLines() {
			$this->_sCss = str_replace("\n", ' ', $this->_sCss);
			$this->_sCss = str_replace("\r", ' ', $this->_sCss);
		}
		private function _removeComments() {
			$this->_sCss = str_replace('/*', $this->_sUnique, ' '.$this->_sCss);
			$this->_sCss = str_replace('*/', $this->_sUnique, $this->_sCss);
			$aChunks = explode($this->_sUnique, $this->_sCss);
			$this->_sCss = '';
			for ($i=0; $i<count($aChunks); $i+=2) {
				$this->_sCss .= $aChunks[$i];
			}
		}
		private function _removeExtraSpace() {
			$this->_sCss = trim(preg_replace('/\s+/', ' ', $this->_sCss));
		}
		private function _getProperies($sRawProps) {
			$aRawProps = explode(';', $sRawProps);
			$aProps = array();
			foreach ($aRawProps as $sRawProp) {
				if (trim($sRawProp) == '') { continue; }
				preg_match('/[\s]*([^\s:]*)[\s]*:[\s]*([^\[]+)[\s]*(\[([^\]]+)\])?/', $sRawProp, $aRegs);
				$aProps[] = array(
					'name'	=> trim(isset($aRegs[1]) ? $aRegs[1] : null),
					'value' => trim(isset($aRegs[2]) ? $aRegs[2] : null),
					'bcode'	=> trim(isset($aRegs[4]) ? strtoupper($aRegs[4]) : null)
				);
			}
			return $aProps;
		}
		private function _getBlocks() {
			if (trim($this->_sCss)) {
				$aChunks = explode('}', $this->_sCss);
				foreach ($aChunks as $sChunk) {
					if (trim($sChunk) == '' || !strstr($sChunk, '{')) { continue; }
					list($sSelector, $sProps) = explode('{',$sChunk);
					$sSelector = trim(preg_replace('/,[\s]+/', ',', $sSelector));
					$this->_aBlocks[$sSelector] = $this->_getProperies($sProps);
				}
			}
		}
		
		/******************** PUBLIC ********************/
		
		public function __construct($sFileName, $sCss=null) {
			if (!$sCss) {
				$sCss = file_get_contents($sFileName);
			}
			$this->_sRawCss = $sCss;
			$this->_sCss = $sCss;
			$this->_sFileName = $sFileName;
			$this->_removeNewLines();
			$this->_removeComments();
			$this->_removeExtraSpace();
			$this->_getBlocks();
		}
		public function getBlocksForBrowser($sBrowserCode) {
			$aBlocks = array();
			
			foreach ($this->_aBlocks as $sSelector=>$aProps) {
				$aUniqProps = array();
				foreach ($aProps as $aProp) {
					$sPropName = $aProp['name'];
					$sPropValue = $aProp['value'];
					if ($aProp['bcode']) {
						if (stripos($sBrowserCode, $aProp['bcode']) === 0) {
							$aUniqProps[$sPropName] = $sPropValue;
						}
					} else {
						$aUniqProps[$sPropName] = $sPropValue;
					}

				}
				$aBlocks[$sSelector] = $aUniqProps;
			}
			return $aBlocks;
		}
		function getCSSForBrowser($sBrowserCode=UserAgent::UNKNOWN, $bMinify=false) {
			if ($sBrowserCode == UserAgent::ALL) {
				return $this->_sRawCss;
			}
			$aBlocks = $this->getBlocksForBrowser($sBrowserCode);

			if ($bMinify) {
			    Debug::log('minifying', $this->_sFileName);
				$sCss = '';
				foreach ($aBlocks as $sSelector=>$aBlock) {
					$sCss .= $sSelector.'{';
					foreach ($aBlock as $sProp=>$sValue) {
						$sCss .= $sProp.':'.$sValue.';';
					}
					$sCss .= '}';
				}
			} else {
				$sCss = '/**'."\n";
				$sCss .= ' *  CSS Generated by Aurum MVC'."\n";
				$sCss .= ' *  Date: '.@date('Y-m-d h:i:s')."\n";
				$sCss .= ' *  Target Browser: '.$sBrowserCode."\n";
				$sCss .= ' *  Source: '.$this->_sFileName."\n";
				$sCss .= ' */'."\n\n";
				
				foreach ($aBlocks as $sSelector=>$aBlock) {
					$sCss .= preg_replace('/[\s]*,[\s]*/', ",\n", $sSelector).' {'."\n";
					foreach ($aBlock as $sProp=>$sValue) {
						$sCss .= "\t".$sProp.': '.$sValue.";\n";
					}
					$sCss .= "}\n";
				}
			}
			return $sCss;
		}
	}

?>