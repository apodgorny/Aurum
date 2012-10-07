/**
 * class Cropper based on JCrop
 * @author Alexander Podgorny <ap.coding@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */


App.FormElementCropper = function(oImgElement) {
	var _oJCrop		= null;
	var _oImg 		= null;
	var _oHidden	= null;
	var _nSize 		= 0;
	var _nX			= 0;
	var _nY			= 0;

	function _saveCoords(oCoords) {
		_nSize 	= oCoords.w;
		_nX		= oCoords.x;
		_nY		= oCoords.y;
		
		_oHidden.value = [_nX, _nY, _nSize, _nSize].join(',');
	}
	
	this.init = function(oElement) {
		_oImg = $$(oElement);
		if (oElement) {
			_oHidden = _oImg.parent().find('input')[0];
			this.registerImage(oElement);
		}
	}
	this.registerImage = function(oElement) {
		_oImg = $$(oElement);
		var aValue = [];
		if (_oHidden.value) {
			aValue = _oHidden.value.split(',');
			console.debug(aValue);
			aValue[2] += aValue[0];
			aValue[3] += aValue[1];
		} else {
			if (_oImg.width() > _oImg.height()) {
				_nSize 	= _oImg.height();
				_nX		= Math.floor((_oImg.width() + _nSize) / 2);
			} else {
				_nSize 	= _oImg.width();
				_nY		= Math.floor((_oImg.height() + _nSize) / 2);
			}
			aValue = [_nX, _nY, _nSize, _nSize];			
		}
		_oJCrop = _oImg.Jcrop({
			onChange: _saveCoords,
			onSelect: _saveCoords,
			aspectRatio	: 1,
			setSelect	: aValue
		});	
	}

	this.init(oImgElement);
}