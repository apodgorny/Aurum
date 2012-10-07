/**
 * Dom generator
 * @author Alexander Podgorny <ap.coding@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

Dom = new function() {

	/*************** PRIVATE *************/

	var _aNodes = [ 
	   'table', 'tbody', 'thead', 'tr', 'th', 'td', 'a', 'strong', 'script', 'link',
	   'div', 'img', 'br', 'b', 'i', 'u', 'span', 'li', 'ul', 'ol',
	   'iframe', 'form', 'h1', 'input', 'button', 'h2', 'h3', 'h4', 'p',
	   'br', 'select', 'option', 'optgroup', 'label', 'textarea', 'em', 'object', 'param', 'embed'
	];

	/**
	 * Creates style attribute string from object
	 * @param oStyles (Object) Key value set of styles like 
	 * 	{'background-color': '#FF0000', 'color': 'black'}
	 * @return
	 */
	function _styleAttribute(oStyles) {
		if (typeof oStyles == 'string') {
			return oStyles;
		} else {
			var aStyleProps = [];
			for (var sKey in oStyles) {
				aStyleProps.push(String(sKey) + ':' + String(oStyles[sKey]));
			}
			return aStyleProps.join(";");
		}
	};
	/**
	 * Accurately setting attributes
	 * @param oElement (Element Object) Element to set attribute to
	 * @param sAttr (String) Attribute name
	 * @param sValue (String) Attribute value
	 * @return
	 */
	function _attribute(oElement, sAttr, sValue) {
	    //sAttr = sAttr.toLowerCase();
	    switch (typeof sValue) {
    	    case 'string':
			case 'number':
        		if (sAttr == 'class') {
        			var aClasses = sValue.split(' ');
        			for ( var i = 0; i < aClasses.length; i++) {
        			    if (aClasses[i] != '') {
        			        $(oElement).addClass(aClasses[i]);
        			    }
        			}
        		} else if (sAttr == 'style') {
        		    oElement.setAttribute('style', _styleAttribute(sValue));
        		} else {
        			oElement.setAttribute(sAttr, sValue);
        		}
        		break;
    	    case 'function':
    	        oElement[sAttr] = sValue;
    	        break;
	    }
	};

	/**
	 * Create element, or execute function
	 * @param oElement (Mixed) 
	 * @return (Mixed)
	 */
	function _element(oElement) {
		if (typeof (oElement) == 'function' && !oElement.tagName) {
			return oElement(); 
		} else if ((typeof (oElement) == 'string') || (typeof (oElement) == 'number')) { 
			return (document.createTextNode(oElement)); 
		} else {
			return (oElement); 
		}
	};

	/**
	 * Create dom
	 * @param sName (String) Tag name
	 * @param aAttrs (Array) Attributes
	 * @return (Element Object) Dom
	 */
	function _dom(sName, aAttrs) {
		var oElement;
		if (document.all && (sName == 'input') && aAttrs.name) {
			oElement = document.createElement('<input name="' + aAttrs.name + '"/>');
		} else {
			oElement = document.createElement(sName);
		}
		if (sName == 'a') {
		    aAttrs['href'] = aAttrs['href'] || '';
		}
		for ( var sAttrName in aAttrs) {
			_attribute(oElement, sAttrName, aAttrs[sAttrName]);
		}
		if ((arguments != null) && (arguments.length > 2)) {
			var aNodeArgs = arguments[2];
			for (var i=1; i<aNodeArgs.length; ++i) {
				var obj = aNodeArgs[i];
				if (typeof (obj) == 'object' && obj.each) {
					obj.each(function(el) {
						oElement.appendChild(_element(el));
					});
				} else {
					oElement.appendChild(_element(obj));
				}
			}
		}
		return oElement;
	}

	/*************** INITIALIZATION ******************/
	/**
	 * Create node functions in the scope of the window
	 */ 
	for (var i=0; i<_aNodes.length; i++) {
		var sNodeName = _aNodes[i];
		var fCreateNode = function(sNodeName) {
			return function(aAttrs) {
				return _dom(sNodeName, aAttrs, arguments);
			};
		};
		this[sNodeName.toUpperCase()] = fCreateNode(sNodeName);
	}
};