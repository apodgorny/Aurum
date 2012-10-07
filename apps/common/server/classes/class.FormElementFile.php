<?
    /**
     * class FormElementFile
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */


 	class FormElementFile extends FormElement {}

 	class FormElementFileRenderer extends Renderer {
 	    public function render($oFormElement) {
			return Dom::INPUT(array(
				'name'  => $oFormElement['name'],
				'type'  => 'file', 
				'value' => $oFormElement['value']
			))."\n";
		}
		public function renderReadOnly($oFormElement) {
			return $oFormElement['value'];
		}
	}


?>