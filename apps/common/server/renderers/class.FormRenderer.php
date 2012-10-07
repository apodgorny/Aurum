<?
    /**
     * FormRenderer Class
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License 
     */

 	class FormRenderer extends Renderer {
		public function render($oForm) {
			$aDefinition = $oForm->getDefinition();
			$aElementGroups = $oForm->getElementGroups();
		    $s = '';
		    // Render hidden elements first
		    foreach ($oForm as $oElement) {
		        if ($oElement['type'] == 'hidden') {
			        $s .= $oElement->render();
		        }
	        }
	        $s .= Dom::LI(array('class'=>'form-error'),
				implode('<br>',(array)$oForm->getErrors())
			);
			foreach ($aElementGroups as $aElementGroup) {
			    $sGroupElements = '';
			    $sGroupErrors   = '';
			    foreach ($aElementGroup as $oElement) {
			        $sGroupElements .= $oElement->render();
			        $sGroupErrors   .= implode('<br>',(array)$oElement->getErrors());
		        }
				$s .= Dom::LI(
					Dom::DIV(array('class'=>'label'),
						Dom::LABEL(array('for'=>$aElementGroup[0]['id']), $aElementGroup[0]['label']).'&nbsp;',
						Dom::BR(),
						Dom::SPAN(array('class'=>'note'), $aElementGroup[0]['note']).'&nbsp;'
					),
					Dom::DIV(array('class'=>'value'),
						$sGroupElements
					),
					Dom::DIV(array('class'=>'error'),
						$sGroupErrors
					)
				);
			}
			$s = Dom::FORM(
				array(
					'name' 	    => $aDefinition['name'],
					'id'        => $oForm->getJsId(),
					'action'    => $aDefinition['action'], 
					'method'    => $aDefinition['method'],
					'enctype'   => $aDefinition['enctype'],
				),
				Dom::UL(array('class' => $oForm->getCssClass()), $s)
			);
			$oForm->registerJsComponent();
			return $s;
		}
		public function renderReadOnly($oForm) {
			$s = '';
			foreach ($oForm as $oElement) {
				$sElement = $oElement->renderReadOnly();
				if (trim($sElement)) {
					$s .= Dom::LI(
						Dom::DIV(array('class'=>'label'),
							Dom::LABEL($oElement['label'])
						),
						Dom::DIV(array('class'=>'value'),
							$sElement
						)
					);
				}
			}
			$s = Dom::UL(array('class'=>$oForm->getCssClass()), $s);
			return $s;
		}
	}

?>