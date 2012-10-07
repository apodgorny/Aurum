<?

 	class FormElement extends Validatable implements IteratorAggregate, ArrayAccess {
	
		/****************** PRIVATE *******************/
		
		private static $_nCounter = 0;
		
		/****************** PUBLIC ********************/
		
		public $_sDefaultRendererClass = '';
		
		public function __construct($aDefinition=array()) {
		    parent::__construct($aDefinition);
			self::$_nCounter ++;
			$this->_aDefinition['id'] = QA::assureArrayValue($aDefinition, 'id', 'form-element-'.self::$_nCounter);
			$this->setErrorLabel(isset($this->_aDefinition['label']) && $this->_aDefinition['label'] 
			    ? $this->_aDefinition['label'] 
				: Text::getText('This value')
		    );
		}
		
		/******* Access Methods *******/
		public function getIterator()				{ return new ArrayIterator($this->_aDefinition); }
		public function offsetSet($sKey, $sValue) 	{ 
		    if ($sKey == 'value') {
		        $sValue = str_replace('<', '< ', $sValue);
	        }
		    $this->_aDefinition[$sKey] = $sValue;
		}
	    public function offsetExists($sKey) 		{ return isset($this->_aDefinition[$sKey]); }
	    public function offsetUnset($sKey) 			{ unset($this->_aDefinition[$sKey]); }
	    public function offsetGet($sKey) 			{ return isset($this->_aDefinition[$sKey]) ? $this->_aDefinition[$sKey] : null; }
	    public function __toString()                { return get_class($this).' '.print_r($this->_aDefinition, 1); }
		/********************************/
	}
	
	class FormElementRenderer extends Renderer {
	    public function render($oFormElement) {
			return Dom::INPUT(array(
				'type'=>'submit',
				'value'=> ($oFormElement['value'] ? $oFormElement['value'] : $oFormElement['label'])
			))."\n";
		}
		public function renderReadOnly($oFormElement) {
			return $oFormElement['value'];
		}
    }

?>