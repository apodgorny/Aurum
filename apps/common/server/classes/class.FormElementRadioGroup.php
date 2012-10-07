<?
    /**
     * class FormElementRadioGroup
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

 	class FormElementRadioGroup extends FormElementChoice {}

 	class FormElementRadioGroupRenderer extends Renderer {
 	    public function render($oFormElement) {
 	    	$aDefinition = $oFormElement->getDefinition();
 	    	$sCssClass   = $oFormElement->getCssClass();
 	    	$sOptions = '';
 	    	$nOptionCount = 1;
 	    	foreach ($aDefinition['options'] as $aOption) {
 	    		$sOptionId = $oFormElement['name'].$nOptionCount;
 	    		$aInputAttributes = array(
    				'type'	=> 'radio',
    				'value'	=> $aOption['value'],
    				'name'	=> $oFormElement['name'],
    				'id'	=> $sOptionId
		    	);
		    	if ($aDefinition['value'] == $aOption['value']) {
		    		$aInputAttributes['checked'] = 'checked';
		    	}
 	    		$sOptions .= Dom::LI(
 	    			Dom::INPUT($aInputAttributes),
	 	    		Dom::LABEL(
	 	    			array(
	 	    				'for'	=> $sOptionId
	 	    			),
	 	    			$aOption['label']
	 	    		)
 	    		);
 	    		$nOptionCount ++;
 	    	}
			return Dom::UL(array(
				'class' => $sCssClass
			), $sOptions);
		}
		public function renderReadOnly($oFormElement) {
			$aDefinition = $oFormElement->getDefinition();
			foreach ($aDefinition['options'] as $aOption) {
 	    		if ($aDefinition['value'] == $aOption['value']) {
 	    			return $aOption['label'];
 	    		}
 	    	}
		}
	}


?>