<?
    /**
     * class FormElementCropper
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */


 	class FormElementCropper extends FormElement {}

 	class FormElementCropperRenderer extends Renderer {
 	    public function render($oFormElement) {
 	        $aDefinition = $oFormElement->getDefinition();
            $oHiddenElement = new FormElementHidden($aDefinition);
            $sHtml = Dom::IMG(array(
                'src' => $aDefinition['src'],
                'id'  => $oFormElement->getJsId()
            ));
            
            $oFormElement->registerJsComponent();
            return Dom::DIV(array(), $sHtml . $oHiddenElement->render());
		}
		public function renderReadOnly($oFormElement) {
			return $oFormElement['value'];
		}
	}


?>