function $$(mElement) {
	if (typeof mElement == 'string') {
		return $('#' + mElement);
	}
	return $(mElement);
};

if (!typeof console == 'undefined') {
	var console = {
		debug: function() {
			alert(arguments.join("\n"));
		}
	}
}


$.fn.tagName = function() {
    return this.each(function() {
        return this.tagName;
    });
};