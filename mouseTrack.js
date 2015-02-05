var isOpera = (navigator.userAgent.indexOf('Opera') != -1);
var isIE = (!isOpera && navigator.userAgent.indexOf('MSIE') != -1)

// returns mouse coords relative to document as 2 element array
// from an article by koch called "Mission Impossible - mouse position" on evolt.org
function getMouseCoords(e) {
	var posx = 0;
	var posy = 0;
	if (!e) var e = window.event;
	if (e.pageX || e.pageY) {
		posx = e.pageX;
		posy = e.pageY;
	} else if (e.clientX || e.clientY) {
		posx = e.clientX;
		posy = e.clientY;
		if (isIE) {
			posx += document.body.scrollLeft;
			posy += document.body.scrollTop;
		}
	}
	return new Array(posx, posy);
}
