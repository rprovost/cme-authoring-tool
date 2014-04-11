// EASTER EGG FUN!!!  SSHHH...don't tell anyone
var contra		= "38384040373937396665666513";
var simpsons	= "677977736766797975718589"
var keyhistory	= "";
var to = null;

window.onkeyup = function(e) {
	clearTimeout(to);
	to = setTimeout('keyhistory="";', 1250);
	keyhistory += String(event.keyCode);
	if ( keyhistory == contra ) alert("You got 99 lives!");
	if ( keyhistory == simpsons ) window.open("http://en.wikipedia.org/wiki/Worst_Episode_Ever");
}

function templatize(div,replacements) {
	var str = document.getElementById(div).innerHTML;
	for (i in replacements) {
		var regex = new RegExp("{"+i+"}",'g');
		str = str.replace(regex, replacements[i]);
	};	
	return str;
}

function rebuildAccordion() {
	$(".assets").accordion("destroy");
	$(".assets h3").click(function(event) {
		if (stop) {
			event.stopImmediatePropagation();
			event.preventDefault();
			stop = false;
		}
	});
	$(".assets").accordion({
		header: "> div > h3",
		active: false
	}).sortable({
		axis: "y",
		handle: "h3",
		stop: function(event, ui) {
			stop = true;
		}
	});
}
