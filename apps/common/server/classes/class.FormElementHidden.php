<?

 	class FormElementHidden extends FormElement {}

 	class FormElementHiddenRenderer extends Renderer {
 	    public function render($oFormElement) {
			return Dom::INPUT(array(
				'name'  => $oFormElement['name'],
				'type'  => 'hidden', 
				'value' => $oFormElement['value']
			))."\n";
		}
		public function renderReadOnly($oFormElement) {
			return '';
		}
	}


?>