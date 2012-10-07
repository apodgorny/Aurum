<?
    /**
     * class FormElementSelect
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

 	class FormElementSelect extends FormElementChoice {}

 	class FormElementSelectRenderer extends Renderer {
 	    public function render($oFormElement) {
 	    	$aDefinition = $oFormElement->getDefinition();
 	    	$sOptions = '';
 	    	
 	    	if (isset($aDefinition['options'])) {
	 	    	foreach ($aDefinition['options'] as $aOption) {
	 	    		$aOptionAttributes = array('value' => $aOption['value']);
	 	    		if (isset($aDefinition['value']) && $aDefinition['value'] == $aOption['value']) {
	 	    			$aOptionAttributes['selected'] = 'selected';
	 	    		}
	 	    		$sOptions .= Dom::OPTION(
	 	    			$aOptionAttributes, 
	 	    			$aOption['label']
	 	    		);
	 	    	}
 	    	};
			return Dom::SELECT(array(
				'name'  => $oFormElement['name'],
				'type'  => 'text', 
				'value' => $oFormElement['value']
			), $sOptions)."\n";
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