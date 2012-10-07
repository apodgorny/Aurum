<?

	class QA extends Object {
	    
	    ////////// ASSURANCE METHODS /////////
	    
		public static function assure($mVariable, $mValue) {
			if (!$mVariable) {
				return $mValue;
			}
			return $mVariable;
		}
		public static function assureArrayValue($a, $sKey, $mValue=false) {
			$mReturn = $mValue;
			if (isset($a[$sKey]) && $a[$sKey]) {
				$mReturn = $a[$sKey];
			}
			return $mReturn;
		}
		public static function assureRequestValue($sKey, $mValue) {
			return self::assureArrayValue($_REQUEST, $sKey, $mValue);
		}
		public static function assureFilePath($sFilePath, $sDefaultFilePath) {
    		if (!file_exists($sFilePath)) {
    			$sFilePath = $sDefaultFilePath;
    		}
    		return $sFilePath;
    	}
    	/**
		 * Filters out values in $_REQUEST that are not expected
		 */
		public static function filterRequest($aExpectedValues) {
		    $_REQUEST = array_intersect_key($_REQUEST, $aExpectedValues);
	    }
		
		//////// ASSERTION METHODS /////////
		
		public static function assert($bValue, $sExceptionText=null) {
		    $sExceptionText = $sExceptionText ? $sExceptionText : 'Assert failed'; 
		    if (!$bValue) {
		       throw new Exception($sExceptionText); 
	        }
	    }
	    public static function assertDBConnection($sExceptionText=null) {
	        $sExceptionText = $sExceptionText 
	            ? $sExceptionText
	            : Text::getText('Database connection is not present');
	            
	        if (!DB::isConnected()) {
	            throw new Exception($sExceptionText);
            }
        }
	    public static function assertRequestValue($sKey, $sExceptionText=null) {
	        $sExceptionText = QA::assure($sExceptionText, Text::getText('Request parameter is missing').': '.$sKey);
	        return self::assertArrayValue($_REQUEST, $sKey, $sExceptionText);
        }
		public static function assertArrayValue($aArray, $sKey, $sExceptionText=null) {
			if (!isset($aArray[$sKey]) || trim($aArray[$sKey]) == '') {
				if (!$sExceptionText) { 
					$sExceptionText = Text::getText('Array parameter is missing').': '.$sKey;
				}
				throw new Exception($sExceptionText);
			}
			return $aArray[$sKey];
		}
		public static function assertRequestValues($aKeys) {
		    foreach ($aKeys as $sKey) {
		        QA::assertRequestValue($sKey);
	        }
	    }
	    public static function assertArrayValues($aArray, $aKeys) {
		    foreach ($aKeys as $sKey) {
		        QA::assertArrayValue($aArray, $sKey);
	        }
	    }
		public static function assertFileExists($sFilePath, $sExceptionText=null) {
			if (!file_exists($sFilePath)) {
				if (!$sExceptionText) {
					$sExceptionText = Text::getText('File does not exist').': '.$sFilePath;
				}
				throw new Exception($sExceptionText);
			}
		}
		public static function assertMethodExists($mObject, $sMethod) {
			if (!method_exists($mObject, $sMethod)) {
				$sClass = gettype($mObject) == 'object' ? get_class($mObject) : $mObject;
				$sExceptionText = Text::getText('Method does not exist').' '.$sClass.'::'.$sMethod.'()';
				throw new Exception($sExceptionText);
			}
		}
		public static function assertMethodsExist($mObject, $aMethods) {
			$aMethods = array_unique($aMethods);
			if (count($aMethods) == 1) {
				return self::assertMethodExists($mObject, $aMethods[0]);
			}
			$bFoundMethod = false;
			$sExceptionText = Text::getText('Neither of the following methods exists');
			$aMethodNames = array();
			foreach ($aMethods as $sMethod) {
				if (!method_exists($mObject, $sMethod)) {
					$sClass = gettype($mObject) == 'object' ? get_class($mObject) : $mObject;
					$aMethodNames[] = ' '.$sClass.'::'.$sMethod.'()';
				} else {
					$bFoundMethod = true;
				}
			}
			if (!$bFoundMethod) {
				throw new Exception($sExceptionText.implode(',', $aMethodNames));
			}
		}
		public static function assertClassExists($sClass) {
			if (!class_exists($sClass)) {
				$sExceptionText = Text::getText('Class does not exist').': '.$sClass;
				throw new Exception($sExceptionText);
			}
		}
		public static function assertConstantExists($sConstant) {
			if (!defined($sConstant)) {
				$sExceptionText = Text::getText('Constant does not exist').': '.$sConstant;
				throw new Exception($sExceptionText);
			}
		}
		
		// Model Access Assertion Methods
		public static function assertCanCreate(Model $oModel, $nMemberId=null) {
		    if ($oModel->canCreate($nMemberId)) {
		        return true;
	        }
	        throw new AccessException(Text::getText('Transaction denied'));
	    }
	    public static function assertCanRead(Model $oModel, $nMemberId=null) {
		    if ($oModel->canRead($oModel['id'], $nMemberId)) {
		        return true;
	        }
	        throw new AccessException(Text::getText('Transaction denied'));
	    }
	    public static function assertCanUpdate(Model $oModel, $nMemberId=null) {
		    if ($oModel->canUpdate($oModel['id'], $nMemberId)) {
		        return true;
	        }
	        throw new AccessException(Text::getText('Transaction denied'));
	    }
	    public static function assertCanDelete(Model $oModel, $nMemberId=null) {
		    if ($oModel->canDelete($oModel['id'], $nMemberId)) {
		        return true;
	        }
	        throw new AccessException(Text::getText('Transaction denied'));
	    }
	}

?>