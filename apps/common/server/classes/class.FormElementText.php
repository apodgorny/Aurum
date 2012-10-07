<?

 	class FormElementText extends FormElement {}

 	class FormElementTextRenderer extends Renderer {
 	    public function render($oFormElement) {
			return Dom::INPUT(array(
				'name'  => $oFormElement['name'],
				'type'  => 'text', 
				'value' => $oFormElement['value'],
				'class' => $oFormElement['class']
			))."\n";
		}
		public function renderReadOnly($oFormElement) {
			return $oFormElement['value'];
		}
	}


?>