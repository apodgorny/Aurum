<?

 	class Form extends Validatable implements IteratorAggregate, ArrayAccess {
	
	    /****************** PROTECTED *****************/
	    
	    protected $_bUseDefaultValidation = false;
	    
		/****************** PRIVATE *******************/
		
		private static $_nFormCount = 0;
		private $_aElements = array();
				
		private function _createElements() {
			$aDefinition = $this->getDefinition();
            if (!isset($aDefinition['elements'])) { return; }
	        foreach ($aDefinition['elements'] as $aElement) {
	            $sElementClassName = 'FormElement'.ucwords($aElement['type']);
	            QA::assertClassExists($sElementClassName);
	            $oElement = new $sElementClassName($aElement);
	            
	            if (isset($aElement['rules'])) {
		            foreach ($aElement['rules'] as $aRule) {
		                if ($aRule['rule']) {
		                    $sCustomErrorMessage = QA::assureArrayValue($aRule, 'message', null);
		                    $oElement->addValidationRule($aRule['rule'], $sCustomErrorMessage);
		                }
	                }
                }
	            $this->addElement($oElement);
            }
	    }
		
		/****************** PUBLIC ********************/
		
		public function __construct($mDefinition=null, $aValues=null) {
		    parent::__construct($mDefinition);
		    self::$_nFormCount ++;
		    		    
            // Set definition defaults
	        $this->_aDefinition['method']  = QA::assureArrayValue($this->_aDefinition, 'method', 'post');
	        $this->_aDefinition['name']	   = $this->getName().self::$_nFormCount;
	        $this->_aDefinition['action']  = QA::assureArrayValue($this->_aDefinition, 'action', '');
	        $this->_aDefinition['enctype'] = QA::assureArrayValue($this->_aDefinition, 'enctype', '');
	
		    $this->_createElements();        
	        //$this->setDefaultRenderer('FormVerticalRenderer');
	        
	        if ($aValues) { $this->setValues($aValues); }
		}
		public function addElement($oFormElement) {
			$this->_aElements[] = $oFormElement;
		}
		public function getElementGroups() {
		    $aElementGroups = array();
			foreach ($this->_aElements as $oElement) {
			    if ($oElement['type']== 'hidden') { continue; }
			    if (isset($oElement['group'])) {
			        $sGroup = $oElement['group'];
			        if (!isset($aElementGroups[$sGroup])) {
			            $aElementGroups[$sGroup] = array();
			        }
			        $aElementGroups[$sGroup][] = $oElement;
		        } else {
		            $aElementGroups[] = array($oElement);
	            }
		    }
		    return $aElementGroups;
	    }
		public function validate($bOneErrorAtATime=true) {
		    parent::validate($bOneErrorAtATime=true);
		    foreach ($this->_aElements as $oElement) {
				$oElement->validate();
			}
	    }
	    public function hasErrors() {
	        foreach ($this->_aElements as $oElement) {
				if ($oElement->hasErrors()) {
				    return true;
			    }
			}
			return parent::hasErrors();
		}
		public function setValues($aValues) {
			foreach ($this->_aElements as $oElement) {
				if (isset($aValues[$oElement['name']]) && trim($aValues[$oElement['name']]) != '') {
					$oElement['value'] = trim($aValues[$oElement['name']]);
				}
			}
		}
		public function getValues($aKeys=null) {
			$aValues = array();
			foreach ($this->_aElements as $oElement) {
				if (trim($oElement['value']) != '' && trim($oElement['name']) != '') {
				    if ($aKeys && !in_array($oElement['name'], $aKeys)) {
                        continue;
			        }
					$aValues[$oElement['name']] = htmlentities($oElement['value']);
				}
			}
			return $aValues;
		}
		public function getAllowedValues() {
			$aValues = array();
			foreach ($this->_aElements as $oElement) {
					$aValues[$oElement['name']] = '';
			}
			return $aValues;
		}
		
		/******* Access Methods *******/
		
		public function getIterator() { return new ArrayIterator($this->_aElements); }
	    public function offsetSet($sKey, $sValue) {
	        foreach ($this->_aElements as &$oElement) {
	            if ($oElement['name'] == $sKey) {
	                $oElement = $sValue;
                }
            }
	    }
	    public function offsetExists($sKey) {
	        foreach ($this->_aElements as $oElement) {
	            if ($oElement['name'] == $sKey) {
	                return true;
                }
            }
            return false;
	    }
	    public function offsetUnset($sKey)  { 
	        foreach ($this->_aElements as &$oElement) {
	            if ($oElement['name'] == $sKey) {
	                unset($oElement);
                }
            }
	    }
	    public function offsetGet($sKey)  { 
	        foreach ($this->_aElements as $oElement) {
	            if ($oElement['name'] == $sKey) {
	                return $oElement;
                }
            }
            throw new Exception(
                Text::getText('Form element').' "'.$sKey.'" '.
                Text::getText('does not exist in form').' "'.$this->name.'"'
            );
        }
	}
























?>