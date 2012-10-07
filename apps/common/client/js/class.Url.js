/**
 * class Url
 * @author Alexander Podgorny <ap.coding@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

App.Url = function() {	
	this.protocol 	= null;
	this.host 		= null;
	this.port 		= null;
	this.path 		= null;
	this.params		= [];
	this.hash 		= null;
	
	this.toString = function() {
		var aParamPairs = [];
		var sParams = '';
		for (var sKey in this.params) {
			aParamPairs.push(sKey + '=' + this.params[sKey]);
		}
		sParams = aParamPairs.join('&');
		
		return this.protocol + '://' +
			this.host + (this.port ? ':' + this.port : '') + '/' +
			this.path + (sParams ? '?' + sParams : '') + 
			(this.hash ? '#' + this.hash : '');
	};	
};

App.Url.fromString = function(s) {
	var aMatches = s.match(/(http[s]?):\/\/([^\/]*)\/([^\?]*)\??([^#]*)#?(.*)/);
	if (!aMatches) { return null; }
	
	var aHostPort = aMatches[2].split(':');
	var aParamPairs = aMatches[4].split('&');
	var aParams = [];
	for (var i=0; i<aParamPairs.length; i++) {
		aKeyVal = aParamPairs[i].split('=');
		aParams[aKeyVal[0]] = aKeyVal[1];
	}

	var oUrl = new App.Url();	
	oUrl.protocol = aMatches[1];	
	oUrl.host	  = aHostPort[0] ? aHostPort[0] : null;
	oUrl.port	  = aHostPort[1] ? aHostPort[1] : null;
	oUrl.path	  = aMatches[3]  ? aMatches[3]  : null;
	oUrl.params	  = aParams;
	oUrl.hash	  = aMatches[5]  ? aMatches[5]  : null;
	
	return oUrl;
};

String.prototype.toUrl = function() {
	return App.Url.fromString(this);
}