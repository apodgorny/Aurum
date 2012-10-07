<?

 	class FormElementPassword extends FormElement {}

 	class FormElementPasswordRenderer extends Renderer {
 	    public function render($oFormElement) {
			return Dom::INPUT(array(
				'name'  => $oFormElement['name'],
				'type'  => 'password', 
				'value' => $oFormElement['value']
			))."\n";
		}
		public function renderReadOnly($oFormElement) {
			return $oFormElement['value'];
		}
	}


?>