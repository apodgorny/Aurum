/**
 * Class Rectangle
 * @author Alexander Podgorny <ap.coding@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */


App.Rect = function(nX, nY, nW, nH) {
	this.x = nX;
	this.y = nY;
	this.w = nW;
	this.h = nH;
};

App.Rect.prototype.has = function(nX, nY, nPadding) {
	return (
		nX >= this.x - nPadding && nX <= this.x + this.w + nPadding &&
		nY >= this.y - nPadding && nY <= this.y + this.h + nPadding
	);
};
