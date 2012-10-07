/**
 * JS library devoted to navigation routines
 * @author Alexander Podgorny <ap.coding@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

App.Navigation = new function() {
	this.urlToObject = function(sUrl, sBaseUrl) {
		sBaseUrl = sBaseUrl || App.Env.MVC_ROOT;
		sUrl 	 = sUrl 	|| window.location.href;
		sUrl 	 = sUrl.replace(sBaseUrl, '');
		
		var oUrl = {};
		var aUrlComponents = sUrl.split('?');
		
		var sUrlPath  = aUrlComponents[0] ? aUrlComponents[0] : '';
		var aUrlPathParams = sUrlPath.split('/');
		for (var n=0; n<aUrlPathParams.length; n++) {
			oUrl[n.toString()] = aUrlPathParams[n];
		}
		
		var sUrlQuery = aUrlComponents[1] ? aUrlComponents[1] : '';
		var aUrlQueryParams = sUrlQuery.split('&');
		for (n = 0; n < aUrlQueryParams.length; n++) {
			var aPair = aUrlQueryParams[n].split('=');
			if (aPair[0]) {
				oUrl[aPair[0]] = aPair[1] ? aPair[1] : '';
			}
		}
		return oUrl;
	};
	this.objectToUrl = function(oUrl, sBaseUrl) {
		sBaseUrl = sBaseUrl || App.Env.MVC_ROOT;
		
		var aUrlPath 	= [];
		var aUrlQuery 	= [];
		
		for (var n=0; n<1000; n++) {
			if (!oUrl[n.toString()]) {
				break;
			}
			aUrlPath.push(oUrl[n.toString()]);
		}
		for (var sKey in oUrl) {
			if (!sKey.match(/^[0-9]+$/)) {
				aUrlQuery.push(sKey + '=' + oUrl[sKey]);
			}
		}
		var sUrlPath   	= aUrlPath.join('/');
		var sUrlQuery  	= aUrlQuery.join('&');
		var sUrl 		= sBaseUrl + sUrlPath + (sUrlQuery ? ('?' + sUrlQuery) : '');
		return sUrl;
	};
	this.setUrlParams = function(oParams, sUrl, sBaseUrl) {
		sBaseUrl = sBaseUrl || App.Env.MVC_ROOT;
		sUrl 	 = sUrl 	|| window.location.href;
		oUrl 	 = App.Navigation.urlToObject(sUrl, sBaseUrl);
		
		for (var sKey in oParams) {
			oUrl[sKey] = oParams[sKey];
		}
		return App.Navigation.objectToUrl(oUrl, sBaseUrl);
	};
	this.link = function(sApp, sController, sAction, oParams) {
		var aParams = [];
		for (var sKey in oParams) {
			aParams.push(sKey + '=' + oParams[sKey]);
		}
		var sParams = aParams.join('&');
		
		return sApp + '/' + sController + '/' + sAction + '?' + sParams;
	};
};