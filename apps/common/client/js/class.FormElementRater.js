/**
 * module FormElementRater
 * @author Alexander Podgorny <ap.coding@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

App.FormElementRater = function(sId, aData) {
	var _oElement     = $('#' + sId);
	if (!_oElement[0]) { return; }
	
	var _oHidden	  = _oElement.find('input')[0];
	var _aData	  	  = aData;
	var _aRaterBlocks = _oElement.find('li');
	var _oForm 		  = _getForm();	

	function _refresh() {
		_aRaterBlocks.each(function(n, oElement) {
			oElement = $(oElement);
			if (_oHidden.value > n) {
				oElement.addClass('selected');
			} else {
				oElement.removeClass('selected');
			}
		});
	}
	function _setValue(nValue) {
		_oHidden.value = nValue;
		_refresh();
	}
	function _getForm() {
		var oParent = _oElement;
		for (var n=0; n<100; n++) {
			if (oParent[0].tagName.toLowerCase() == 'form') {
				return oParent;
			}
			oParent = oParent.parent();
		}
		return null;
	}
	function _registerIO() {
		_aRaterBlocks.each(_registerRaterBlock);
		_oElement.mouseout(_refresh);
		App.Event.observe(App.Form.Events.FORM_RESPONSE, function(oEvent, oMemo) {
			if (oMemo.id == _oForm[0].id) {
				App.Notification.flash('Your rating has been saved');
			}
		});
	}
	function _registerRaterBlock(n, oElement) {
		$(oElement).click(function() {
			_setValue(n+1);
			_oForm.submit();
		});
		$(oElement).mouseover(function() {
			_aRaterBlocks.each(function(n1, oElementN) {
				oElementN = $(oElementN);
				if (n >= n1) {
					oElementN.addClass('selected');
				} else {
					oElementN.removeClass('selected');
				}
			});
		});
	}
	
	/**************** PUBLIC ****************/
	
	this.setValue = function(nValue) {
		_setValue(nValue);
	};
	
	_registerIO();
};