<?

 	class FormElementButton extends FormElement {}
 	
 	class FormElementButtonRenderer extends Renderer {
 		public function render($oFormElement) {
			return Dom::BUTTON(
			    array(
			        'type'  => 'button',
			        'name'  => $oFormElement['name'],
			        'class' => 'button '.$oFormElement['class'],
			        'onclick' => $oFormElement['onclick'],
			    ),
				$oFormElement['value']
			);
		}
		public function renderReadOnly($oFormElement) {
			return '';
		}
    }
 	

?>