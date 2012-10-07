<?

	class Text extends Object {
		public static $aSingularToPlural = array(
			'fungus' 	    => 'fungi',
			'matrix'	    => 'matrices',
			'hippocampus'   => 'hippocampi'
		);
		public static $aPluralToSingular = array();
		public static $aNonSeoTokens = array(
		    'or', 'and'
		);

		public static function isUpperCase($s) {
			return ctype_upper($s);
		}
		public static function getCase($s) {
			$aCase = array();
			for ($i=0; $i<strlen($s); $i++) {
				if (ctype_upper($s[$i])) {
					$aCase[$i] = 1;
				} else {
					$aCase[$i] = 0;
				}
			}
			return $aCase;
		}
		public static function setCase($s, $aCase) {
			$sResult = '';
			$nLastCase = 0;
			for ($i=0; $i<strlen($s); $i++) {
				if ($i < count($aCase)) {
					$nLastCase = $aCase[$i];
				}
				if ($nLastCase == 1) {
					$sResult .= strtoupper($s[$i]);
				} else {
					$sResult .= strtolower($s[$i]);
				}
			}
			return $sResult;
		}
		public static function toDashedCase($s, $sDelimiter='_') {
			$sResult = '';
			for ($i=0; $i<strlen($s); $i++) {
				if (self::isUpperCase($s[$i])) {
					if ($i > 0) {
						$sResult .= $sDelimiter;
					}
				}
				$sResult .= $s[$i];
			}
			$sResult = strtolower(preg_replace('/[\.,_-]+/', $sDelimiter, $sResult));
			return $sResult;
		}
		public static function toCamelCase($s, $bCapitalize=false) {
			$a = preg_split('/[_\-]+/', $s);
			$nStart = $bCapitalize ? 0 : 1;
			for ($i=$nStart; $i<count($a); $i++) {
				$a[$i] = ucfirst($a[$i]);
			}
			return implode('', $a);
		}
		public static function assureVarName($s, $sDefaultValue) {
			$s = strtolower($s);
			$s = preg_replace('/^[^a-z_]/', '', $s);
			$s = preg_replace('/[^a-z0-9_]*/', '', $s);
			if ($s == '') {
				$s = $sDefaultValue;
			}
			return $s;
		}

		public static function getText($s) {
			return $s;
		}

		public static function toSingularForm($sPlural) {
			$aCase = Text::getCase($sPlural);
			$sPlural = strtolower($sPlural);
			$sResult = '';
			if (!Text::$aPluralToSingular) {
				Text::$aPluralToSingular = array_flip(Text::$aSingularToPlural);
			}
			if (isset(Text::$aSingularToPlural[$sPlural])) {
			    return $sPlural;
		    }
			if (isset(Text::$aPluralToSingular[$sPlural])) {
				$sResult = Text::$aPluralToSingular[$sPlural];
			} else {
				$sResult = preg_replace("/s$/", '', $sPlural);
			}
			return Text::setCase($sResult, $aCase);
		}

		public static function toPluralForm($sSingular) {
			$aCase = Text::getCase($sSingular);
			$sSingular = strtolower($sSingular);
			$sResult = '';
			if (isset(Text::$aSingularToPlural[$sSingular])) {
				$sResult = Text::$aSingularToPlural[$sSingular];
			} else {
				$sResult = $sSingular . 's';
			}
			return Text::setCase($sResult, $aCase);
		}

		public static function toCorrectForm($sSingular, $nCount) {
		    if ($nCount == 1) {
		        return $sSingular;
		    }
		    return self::toPluralForm($sSingular);
		}

		public static function toAlphanumeric($s, $bPreserveWS=true) {
		    if ($bPreserveWS) {
		        return preg_replace('/[^a-z0-9\s]/i', '', $s);
		    }
		    return preg_replace('/[^a-z0-9]/i', '', $s);
	    }
		public static function toTokens($s) {
            $s = Text::toAlphanumeric(strtolower($s));
            return array_filter(preg_split('/[,\/\s]+/', $s));
	    }
	    public static function highlight($sText, $aWords) {
	        if (!$aWords) { return $sText; }
	        foreach ($aWords as $sWord) {
	            $sPluralWord = self::toPluralForm($sWord);
	            $sText = preg_replace("/($sPluralWord)/i", '<b>$1</b>', $sText);
	            if (strlen($sWord) > 2) {
	                $sText = preg_replace("/($sWord)/i", '<b>$1</b>', $sText);
                }
            }
            return $sText;
        }
        public static function toExcerpt($sText, $nLength=80, $aWords=array()) {
            if (!trim($sText)) { return ''; }
            $sText = $sText;
            $aPositions = array();
            $nPosition = '';
            if (!$aWords) {
                if (strlen($sText) >= $nLength) {
                    return substr($sText, 0, $nLength).'&hellip;';
                }
                return $sText;
            }
            foreach ($aWords as $sWord) {
                $nLastPosition = -1;
                while (($nPosition = stripos($sText, $sWord, $nLastPosition+1)) !== false) {
                    $nLastPosition = $nPosition;
                    $aPositions[] = $nPosition;
                }
            }
            $nBestPosition = 0;
            $nPrevPosition = isset($aPositions[0]) ? $aPositions[0] : 0;
            $nBestDistance = strlen($sText);
            foreach ($aPositions as $nPosition) {
                $nDistance = abs($nPrevPosition - $nPosition);
                if ($nDistance < $nBestDistance) {
                    $nBestDistance = $nDistance;
                    $nBestPosition = $nPrevPosition;
                }
                $nPrevPosition = $nPosition;
            }
            return ($nBestPosition == 0 ? '' : '&hellip;').substr($sText, $nBestPosition, $nLength).'&hellip;';
        }
        public static function toSeoName($sText) {
            // Warning: modifying this function may break matching linkedin imports to existing categories.
            // Please ask Alex if you need to change this function

            $aTokens =  array_filter(preg_split('/[,\/\s]+/', (strtolower(str_replace('-', ' ', $sText)))));
            $aCleanTokens = array();
            foreach ($aTokens as $sToken) {
                $sToken = trim($sToken);
                if (preg_match('/[^a-z0-9]+/', $sToken))     { continue; }
                if (in_array($sToken, Text::$aNonSeoTokens)) { continue; }
                $aCleanTokens[] = $sToken;
            }
            return implode('-', $aCleanTokens);
        }
	}

?>