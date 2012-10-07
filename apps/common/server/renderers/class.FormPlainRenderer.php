<?
    /**
     * FormPlainRenderer Class
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License 
     */

 	class FormPlainRenderer extends Renderer {
		public function render($oForm) {
			$aDefinition = $oForm->getDefinition();
		    $s = '';
			foreach ($oForm as $oElement) {
				$s .= $oElement->render();
			}
			$s = Dom::FORM(
				array(
					'name' 	    => $aDefinition['name'],
					'id'        => $oForm->getJsId(),
					'action'    => $aDefinition['action'], 
					'method'    => $aDefinition['method'],
					'enctype'   => $aDefinition['enctype'],
				),
				$s
			);
			$oForm->registerJsComponent();
			return $s;
		}
		public function renderReadOnly($oForm) {
			$aDefinition = $oForm->getDefinition();
		    $s = '';
			foreach ($oForm as $oElement) {
				$s .= $oElement->renderReadOnly();
			}
			$s = Dom::FORM(
				array(
					'name' 	    => $aDefinition['name'],
					'id'        => $oForm->getJsId(),
					'action'    => $aDefinition['action'], 
					'method'    => $aDefinition['method'],
					'enctype'   => $aDefinition['enctype'],
				),
				$s
			);
			$oForm->registerJsComponent();
			return $s;
		}
	}

?>