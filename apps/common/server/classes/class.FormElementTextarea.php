<?

 	class FormElementTextarea extends FormElement {}
 	
 	class FormElementTextareaRenderer extends Renderer {
 	    public function render($oFormElement) {
			return Dom::TEXTAREA(
			    array(
			        'name'  => $oFormElement['name'],
			        'id'    => $oFormElement['id'],
			        'class' => $oFormElement['class']
			    ),
				$oFormElement['value']
			);
		}
		public function renderReadOnly($oFormElement) {
			return $oFormElement['value'];
		}
	}
 	

?>