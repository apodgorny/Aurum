<?
    /**
      * Validatable Class
      * @author Alexander Podgorny <ap.coding@gmail.com>
      * @license http://opensource.org/licenses/gpl-license.php GNU Public License 
      */

    class Validatable extends Renderable {
        
        /************** PROTECTED **************/
        
        protected $_bUseDefaultValidation = true;
        protected $_bUseCustomValidation = true;
        protected $_aValidationRules = array();
		protected $_aCustomValidationRules = array();
		protected $_aErrors = array();
		protected $_sErrorLabel = '';
        
        /************** PUBLIC ***************/
        
        public function addValidationRule($nValidationRule, $sErrorMessage=null) {
		    if (is_numeric($nValidationRule) || is_string($nValidationRule)) {
			    $this->_aValidationRules[$nValidationRule] = $sErrorMessage;
		    } else { // custom validation function
		        $this->_aCustomValidationRules[] = array(
		            'message'   => $sErrorMessage,
		            'function'  => $nValidationRule
		        );
	        }
		}
		public function removeValidationRule($nValidationRule) {
			unset($this->_aValidationRules[$nValidationRule]);
		}
		public function validate($bOneAtATime=true) {
		    // Process predefined validation
			if ($this->_bUseDefaultValidation) {
			    if ($this->_aErrors = Validation::getErrors($this['value'], $this->_aValidationRules)) {
    				foreach ($this->_aErrors as $sKey=>$sValue) {
    					$this->_aErrors[$sKey] = $this->_sErrorLabel.' '.$sValue;
    					if ($bOneAtATime) { return; }
    				}
				}
			}
			// Process custom validation
			if ($this->_bUseCustomValidation) {
    			foreach ($this->_aCustomValidationRules as $aRule) {
    			    if (!call_user_func($aRule['function'])) {
    			        $this->addError($aRule['message']);
    			        if ($bOneAtATime) { return; }
    		        }
    		    }
		    }
		}
		public function isValid($bOneAtATime=true) {
			$this->validate($bOneAtATime);
			return !$this->hasErrors();
		}
		public function hasErrors() {
			return count($this->_aErrors) > 0;
		}
		public function getErrors() {
			return $this->_aErrors;
		}
		public function addError($sError) {
		    $this->_aErrors[] = $sError;
	    }
		public function setErrorLabel($sLabel) {
		    $this->_sErrorLabel = $sLabel;
	    }
    }

?>