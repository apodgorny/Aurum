<?

 	class FormElementFieldset extends FormElement {
 		public $aElements = array();
 		public function __construct($aDefinition=array()) {
 			parent::__construct($aDefinition);
 			if (isset($aDefinition['fields'])) {
 				foreach ($aDefinition['fields'] as $aElement) {
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
		            $this->aElements[] = $oElement;
 				}
 			}
 		}
 	}
 	
 	class FormElementFieldsetRenderer extends Renderer {
 		public function render($oFormElement) {
 			$sHtml = '';
 			foreach ($oFormElement->aElements as $oField) {
 				$sHtml .= $oField->render();
 			} 
			return Dom::DIV(array('class'=>$oFormElement->getCssClass()), $sHtml);
		}
		public function renderReadOnly($oFormElement) {
			return '';
		}
    }
 	

?>