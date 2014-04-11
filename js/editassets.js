// Current number of uploading assets
var uploading = 0;

// Display the "remove" icon for an asset
$('.toggler').live('mouseenter',function(){$(this).find('.remove').fadeIn('fast');});

// Hide the "remove" icon for an asset
$('.toggler').live('mouseleave',function(){$(this).find('.remove').fadeOut('fast');});

// Return a list of all lyrics assets
function getLyricsAssets() {
	// create empty lyrics array
	var lyrics = new Array();

	// loop through all current lyrics plugins
	$(".lyrics-asset").each(function(index) {
		// determine uid of found lyrics asset
		var uid		= getUID($(this).children().first());

		// retrieve display information
		var artist	= $(this).find(".artist").val();
		var title	= $(this).find(".title").val();

		// add lyrics information to the list
		lyrics.push([uid,artist,title]);
	});

	return lyrics;
}

// Create a new asset
function newAsset(pluginid) {
	$.get("?do=assets&pluginid="+pluginid, function(plugin) {
		// clear the notice when the accordion is empty
		$(".emptyaccordion").remove();

		// insert the new plugin
		$(".assets").append(plugin);

		// Rebuild the accordion with the new asset
		rebuildAccordion();

		// Get the accordion length and current node before resetting it
		var accordionLength = Number($(".assets h3").length);

		// Set the accordion position
		$(".assets").accordion("activate",accordionLength-1);
	});
	return false;
}

// Remove an asset from the accordion
function removeAsset(asset) {
	// determine the uid of the asset being deleted
	var uid = getUID(asset);

	// determine which asset is being removed
	$(".assets h3").each(function(index) {
		if (getUID(this) == uid) {
			deleting = Number(index);
		}
	});

	// delete the current asset
	$(asset).parents(".asset").remove();

	// determine which element to reopen the accordion to
	var accordionLength		= Number($(".assets h3").length);
	var accordionCurrent	= Number($(".assets").accordion("option","active"));

	// adjust current asset accordingly
	if ( accordionCurrent >= deleting && deleting > 1 ) {
		accordionCurrent--;
	}
	if ( accordionCurrent == accordionLength ) {
		accordionCurrent--;
	}

	// rebuild the accordion
	rebuildAccordion();

	// return to the proper location
	$(".assets").accordion("option", "animated", false);
	$(".assets").accordion("activate", accordionCurrent);
	$(".assets").accordion("option", "animated", "slide");
}

// Get the UID of the asset according to the DOM element passed in
function getUID(asset) {
	var uid;
	var classes = $(asset).parents(".asset").attr("class").split(" ");

	$(classes).each(function(index,value) {
		if (value.indexOf("uid_")!==-1) {
			uid = value.split("_");
			uid = uid[1];
		}
	});

	return uid;
}


// Display or hide the Asset Plugin selector
$(".button.selector").live("click",function(){
	if ( $(".selector.plugins").is(':visible') ) {
		$(".selector.plugins").fadeOut('fast');
	} else {
		var offset	= $(".button.selector").offset();
		var height	= $(".selector.plugins").height();
		var width	= $(".button.selector").width();
		var top		= offset.top-height-15;
		$(".selector.plugins").css({'width':width,'top': top});
		$(".selector.plugins").fadeIn('fast');

		// hide the menu when clicked outside
		$(document).one("click",function(){
			if ( $(".selector.plugins").is(':visible') ) {
				$(".selector.plugins").fadeOut('fast');
			}
		});
	}
});


$('.build').live('click',function(){
	if(uploading === 0) {
		$('#editassets').submit();
	} else {
		alert('You still have ' + uploading + ' upload(s) in progress. Please wait until all uploads have been completed.');
	}
	return false;
});
