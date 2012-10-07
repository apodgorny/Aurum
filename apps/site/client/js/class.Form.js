/**
 * Module Form
 * @author Alexander Podgorny <ap.coding@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

App.Form = function(sId, aData) {
	
	/************* PRIVATE ************/
	var _aData 		= aData;
	var _oElement 	= $('#' + sId);
	
	_aData.method = (_aData.method || 'get').toLowerCase();
	_aData.action = _aData.action || window.location.href;
	
	/************* PUBLIC *************/
	
	this.init = function(oElement) {
		oElement.submit(function() {
			try {
				if (_aData.ajax) {
					var onSuccess = function(sResponse, sStatus) {
						App.Event.fire(App.Form.Events.FORM_RESPONSE, {
							'id'	: sId, 
							'data'	: sResponse, 
							'status': sStatus
						});
						return;
					}
					var sRequest = _oElement.serialize();
					switch (_aData.method) {
						case 'post':
							$.post(_aData.action, sRequest,	onSuccess);
							break;
						case 'get':
						default:
							$.get(_aData.action, sRequest, onSuccess);
							break;
					}
					return false;
				}
			} catch (e) {
				return false;
			}
			return true;
		});
		
		App.Event.observe(App.Form.Events.FORM_RESPONSE, function(oEvent, oMemo) {
			if (oMemo.id == this.id) {
				this.onResponse(oEvent, oMemo);
			}
		});
	}
	this.onResponse = function(oEvent, oMemo) {
		
	};
	this.id = sId;
	this.init(_oElement);
};

App.Form.Events = {
	FORM_REQUEST	: 'form:request',
	FORM_RESPONSE	: 'form:response'
};