<?
    /**
      * FormHorizontalRenderer Class
      * @author Alexander Podgorny <ap.coding@gmail.com>
      * @license http://opensource.org/licenses/gpl-license.php GNU Public License 
      */

 	class FormHorizontalRenderer extends Renderer {
		public function render($oForm) {
			$aDefinition = $oForm->getDefinition();
			$s = '';
			// Render hidden elements first
		    foreach ($oForm as $oElement) {
		        if ($oElement['type'] == 'hidden') {
			        $s .= $oElement->render();
		        }
	        }
			foreach ($oForm as $oElement) {
			    if ($oElement['type']== 'hidden') { continue; }
				$s .= Dom::SPAN(
					Dom::SPAN(array('class'=>'label'),
						Dom::LABEL(array('for'=>$oElement['id']),	$oElement['label'])
					),
					Dom::SPAN(array('class'=>'value'),
						$oElement->render()
					),
					Dom::SPAN(array('class'=>'error'),
						implode(',',(array)$oElement->getErrors())
					)
				);
			}
			$s = Dom::FORM(
				array(
					'name' 	 => $aDefinition['name'], 
					'action' => $aDefinition['action'], 
					'method' => $aDefinition['method']
				),
				Dom::SPAN(array('class' => $oForm->getCssClass()), $s)
			);
			return $s;
		}
		public function renderReadOnly($oForm) {
		    $s = '';
            foreach ($oForm as $oElement) {
				$s .= Dom::SPAN(
					Dom::SPAN(array('class'=>'label'),
						$oElement['label']
					),
					Dom::SPAN(array('class'=>'value'),
						$oElement->renderReadOnly()
					)
				);
			}
			$s = Dom::SPAN(array('class'=>$oElement->getCssClass()), $s);			
			return $s;
		}
	}

?>