/**
 * Module Tabs
 * @author Alexander Podgorny <ap.coding@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

App.Menu = function(sId, aData) {
	aData = aData;
	
	/************* PRIVATE ************/
	var _oThis	   = this;
	var _aData     = aData;
	var _oParent   = $('#' + aData.parent_id);	
	var _oElement  = $('#' + sId);
	var _aElements = $('#' + sId + '>li');
	
	var _oElementRect = new App.Rect();
	var _oParentRect  = new App.Rect();
	var _bCanClose	  = true;
	
	function _onMouseMove(oEvent) {
		_bCanClose = false;
		if (!_oElementRect.has(oEvent.clientX, oEvent.clientY, 20) &&
		 	!_oParentRect.has(oEvent.clientX, oEvent.clientY, 10)) {
			_bCanClose = true;
			_oThis.close();
		}
	}
	function _registerIO() {
		_oParentRect.x = _oParent.offset().left;
		_oParentRect.y = _oParent.offset().top;
		_oParentRect.w = _oParent.width();
		_oParentRect.h = _oParent.height();
		
		_oParent.bind('mouseover', function() {
			_oElement.css('left', _oParent.position().left + 'px');
			_oElement.css('top' , _oParent.position().top  + _oParent.height() + 'px');
			_oElement.css('opacity', 1).show();
			
			_oElementRect.x = _oParent.offset().left;
			_oElementRect.y = _oParent.offset().top  + _oParent.height();
			_oElementRect.w = _oElement.width();
			_oElementRect.h = _oElement.height();
			
			$(document).bind('mousemove', _onMouseMove);
		});
	};
	
	/************* PUBLIC *************/
	
	this.close = function() {
		if (_bCanClose) {
			_oElement.fadeOut({
				step : function() {
					if (!_bCanClose) {
						_oElement.css('opacity', '1');
					}
				},
				complete : function() { $(document).unbind('mousemove', _onMouseMove); }
			});
		}
	}
	_registerIO();
	
};