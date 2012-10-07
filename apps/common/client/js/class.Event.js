/**
 * Class Event - wrapper for Event class. 
 * Implement the methods with calls to js library of your choice
 *
 * @author Alexander Podgorny <ap.coding@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

App.Event = new function() {
	
	/************** PRIVATE ****************/
	/************** PUBLIC *****************/
	
	this.fire = function(sEvent, oMemo) {
		$(document).trigger(sEvent, oMemo);
	};
	this.observe = function(sEvent, fHandler) {
		$(document).bind(sEvent, fHandler);
	};
	this.stopObserving = function(sEvent, fHandler) {
		$(document).unbind(sEvent, fHandler);
	};
};