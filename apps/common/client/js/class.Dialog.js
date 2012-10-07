/**
 * Library of all popup dialogs appearing on the site
 * @author Alexander Podgorny <ap.coding@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

App.Dialog = new function() {
	
	/************** PRIVATE  ***************/
	
	var _oDialogs = {};
	
	/************** PUBLIC  ****************/
	
	this.open = function(sDialogId, sHtml, bIframe){
		if (_oDialogs[sDialogId]) {
			this.close(sDialogId);
		}
		var oDialogContent = '';
		if (bIframe) {
			var sUrl = App.Navigation.setUrlParams({
				'template': 'dialog',
				'dialog_id': sDialogId
			}, sHtml);
			oDialogContent = Dom.IFRAME({
				'src': sUrl,
				'name': sDialogId,
				'id': sDialogId,
				'style': 'display: none; width: 100%; height: 100%; border: 0',
				'frameBorder': '0',
				'border': '0'
			});
		} else {
			oDialogContent = Dom.DIV({
				'id': sDialogId,
				'style': 'display: none;'
			}, sHtml);
		}
		$('body').append(oDialogContent);
		_oDialogs[sDialogId] = $('#' + sDialogId).dialog({
			'autoResize': true,
			close: function(ev, ui){
				App.Dialog.close(sDialogId);
			}
		});
		if (bIframe) { this.hide(sDialogId); }
	};
	
	this.close = function(sDialogId){
		var oElement = $('#' + sDialogId);
		if (oElement) {
			oElement.hide();
			oElement.remove();
		}
		delete _oDialogs[sDialogId];
	};
	
	this.set = function(sDialogId, oOptions, sValue){
		if (!_oDialogs[sDialogId]) { return; }
		var oDialogElement = $('#' + sDialogId).parent();
		if (sValue) {
			var sKey = oOptions;
			switch (sKey) {
				case 'top':
				case 'y':
					oDialogElement.css('top', sValue);
					break;
				case 'left':
				case 'x':
					oDialogElement.css('left', sValue);
					break;
				default:
					_oDialogs[sDialogId].dialog('option', oOptions, sValue);
			}
		} else {
			for (sKey in oOptions) {
				_oDialogs[sDialogId].dialog('option', sKey, oOptions[sKey]);
			}
		}
	};
	
	this.get = function(sDialogId, sOption){
		if (!_oDialogs[sDialogId]) { return; }
		return _oDialogs[sDialogId].dialog('option', sOption);
	};
	
	this.setSize = function(sDialogId, nWidth, nHeight, bAnimate) {
		if (!_oDialogs[sDialogId]) { return; }
		
		var oContent = $('#' + sDialogId);
		var oDialog = _oDialogs[sDialogId];
		
		var nDialogWidth = this.get(sDialogId, 'width');
		var nDialogHeight = this.get(sDialogId, 'height');
		var nContentWidth = oContent.width();
		var nContentHeight = oContent.height();
		var nDeltaWidth = nWidth - nContentWidth;
		var nDeltaHeight = nHeight - nContentHeight;
		
		nDialogWidth += nDeltaWidth;
		nDialogHeight += nDeltaHeight;
		
		oDialog.dialog('option', 'width', nDialogWidth);
		
		oContent.css('width', nWidth + 'px');
		oContent.css('height', nHeight + 'px');
		
		this.center(sDialogId);
	};
	
	this.center = function(sDialogId){
		if (!_oDialogs[sDialogId]) { return; }
		
		var nDialogWidth = this.get(sDialogId, 'width');
		var nDialogHeight = this.get(sDialogId, 'height');
		
		var nMaxTopOffset = 100;
		var nMinTopOffset = 50;
		
		if (nDialogHeight == 'auto') {
			nDialogHeight = $('#' + sDialogId).parent().height();
		}
		var nRelativeTop = ($(window).height() - nDialogHeight) / 2;
		if (nRelativeTop > nMaxTopOffset) {
			nRelativeTop = nMaxTopOffset;
		}
		if (nRelativeTop < nMinTopOffset) {
			nRelativeTop = nMinTopOffset;
		}
		var nAbsoluteTop = $(window).scrollTop() + nRelativeTop;
		
		var nMinLeftOffset = 50;
		
		var nRelativeLeft = ($(window).width() - nDialogWidth) / 2;
		if (nRelativeLeft < nMinLeftOffset) {
			nRelativeLeft = nMinLeftOffset;
		}
		var nAbsoluteLeft = $(window).scrollLeft() + nRelativeLeft;
		
		this.set(sDialogId, 'top', nAbsoluteTop);
		this.set(sDialogId, 'left', nAbsoluteLeft);
	};
	
	this.show = function(sDialogId) {
		if (!_oDialogs[sDialogId]) { return; }
		$$(sDialogId).parent().show();
	};
	
	this.hide = function(sDialogId) {
		if (!_oDialogs[sDialogId]) { return; }
		$$(sDialogId).parent().hide();
	};
	
	this.setTitle = function(sDialogId, sTitle) {
		if (!_oDialogs[sDialogId]) { return; }
		_oDialogs[sDialogId].dialog({'title': sTitle});
	};
};