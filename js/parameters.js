$(document).ready(function() {
				$('#slider').rhinoslider({
					effectTime: 1000,
					showTime: 7000,
					controlsMousewheel: false,
					controlsKeyboard: false,
					controlsPrevNext: false,
					autoPlay: true,
					pauseOnHover: false,
					showBullets: 'never',
					showControls: 'never',
					slidePrevDirection: 'toRight'
				});
			});

function slider_x() {				
$(document).ready(function() {
				$('#slider2').rhinoslider({
					effectTime: 50,
					showTime: 14000,
					controlsMousewheel: false,
					controlsKeyboard: false,
					controlsPrevNext: false,
					autoPlay: true,
					pauseOnHover: false,
					showBullets: 'never',
					showControls: 'never',
					slideNextDirection: 'toLeft'
				});
			});
}
			
function slider_xxx() {	 
	setTimeout('slider_x()', 7000);
}

/*
 http://rhinoslider.com/demo-download/
*/