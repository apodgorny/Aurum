/**
 * class Notification
 * @author Alexander Podgorny <ap.coding@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

App.Notification = new function() {
	this.flash = function(sMessage) {
	//	$('#notification td').html(sMessage);
		$('#notification')
			.html(sMessage)
			.show()
			.animate({opacity: '1'}, 1000)
			.animate({opacity: '0'}, 3000, function() { 
				$(this).hide();
			});
	};
};