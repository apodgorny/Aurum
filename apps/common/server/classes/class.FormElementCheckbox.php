<?
    /**
     * class FormElementCheckbox
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

 	class FormElementCheckbox extends FormElement {}
 	
 	class FormElementCheckboxRenderer extends Renderer {
 		public function render($oFormElement) {
 			$aCheckboxAttributes = array(
 				'type' => 'checkbox', 
 				'id' => $oFormElement['id']
 			);
 			if ($oFormElement['checked'] || $oFormElement['selected']) {
 				$aCheckboxAttributes['checked'] = 'checked';
 			}
 			return Dom::DIV(array('class' => $oFormElement->getCssClass()),
 				Dom::INPUT($aCheckboxAttributes)
		 	);
 		}
 		public function renderReadOnly($oFormElement) {
 			if ($oFormElement['checked'] || $oFormElement['selected']) {
 				return Text::getText('Yes');
 			}
 			return Text::getText('No');
 		}
 	}

?>