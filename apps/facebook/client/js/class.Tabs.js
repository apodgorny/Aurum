/**
 * Module Tabs
 * @author Alexander Podgorny <ap.coding@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

App.Tabs = function(sId, aData) {
	aData = aData.items;
	
	/************* PRIVATE ************/
	var _aData = aData;
	var _oElement = $('#' + sId);
	var _aElements = null;
	
	function _registerTab(oTab, oTabData) {
		$(oTab).click(function() {
			if (oTabData.link) {
				//self.location = oTabData.link;
			}
		});
	};
	function _registerIO() {
		_aElements = _oElement.find('li');
		_aElements.each(function(n) {
			_registerTab(this, _aData[n]);
		});
	};
	
	/************* PUBLIC *************/
	_registerIO();
	
};