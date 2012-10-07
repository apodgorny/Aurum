(function($){
	$.fn.extend({ 
 		MediaBox: function(oOptions) {
			var oDefaults = {};
			var oOptions  = $.extend(oDefaults, oOptions);
			
			function _show(oElement) {
				var o     = oOptions;
				var oUrl  = $(oElement).attr('href').toUrl();
				var sHtml = '';
				
				if (!oUrl) { return null; }
				
				// Generate different html depending
				switch (oUrl.host) {
					case 'youtube.com':
					case 'www.youtube.com':
						sHtml = _getYoutubeHtml(oUrl);
						break;
				}
				
				App.Dialog.open('mediabox', sHtml);
				App.Dialog.setSize('mediabox', 408, 385);
				App.Dialog.center('mediabox');
			}
			
			function _getYoutubeHtml(oUrl) {
				var nW		    = 408;
				var nH			= 385;
				var sVideoToken = null;
				var aMatch 		= null;
				
				if (oUrl.params['v']) {
					sVideoToken = oUrl.params['v'];
				} else if (aMatch = oUrl.path.match(/v\/([^\/&]{11})/)) {
					sVideoToken = aMatch[1];
				}
				
				var sPlayerLink = 'http://www.youtube.com/v/' + sVideoToken + '&fs=1';
				
				return Dom.DIV({'style': 'width:'+ nW+'px;height:'+nH+'px;'},
					Dom.OBJECT({'width': nW, 'height': nH},
                    	Dom.PARAM({'name':'movie', 'value' : sPlayerLink}, ''),
	                    Dom.PARAM({'name':'allowFullScreen', 'value':'true'}, ''),
	                    Dom.PARAM({'name':'allowScriptAccess', 'value':'always'}, ''),
	                    Dom.EMBED({
	                        'src'               : sPlayerLink,
	                        'type'              : 'application/x-shockwave-flash',                            
	                        'allowfullscreen'   : 'true',
	                        'allowscriptaccess' : 'always',
	                        'width'             : nW,
	                        'height'            : nH
	                    }, '')
	                )
				);
			}

    		return this.each(function() {
				// Add a click event of our own
				$(this).click(function(oEvent) {
					oEvent.preventDefault();
					_show(oEvent.currentTarget);
				});
    		});
    	}
	});
})(jQuery);

