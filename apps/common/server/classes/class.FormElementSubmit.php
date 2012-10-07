<?

 	class FormElementSubmit extends FormElement {}
 	
 	class FormElementSubmitRenderer extends Renderer {
 	    public function render($oFormElement) {
			return Dom::BUTTON(
			    array(
			        'type'  =>'submit',
			        'name'  => $oFormElement['name'],
			        'class' => 'button button-secondary '.$oFormElement['class']
			    ),
				$oFormElement['value']
			);
		}
		public function renderReadOnly($oFormElement) {
			return '';
		}
    }
 	

?>