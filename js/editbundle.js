// Initial default value color state - grey when not resumed, black otherwise
$(document).ready(function () {
	if($('.title').val() == 'Title') {
		$('.title').css({ 'color': '#CCCCCC'});
	}

	if($('.artist').val() == 'Artist') {
		$('.artist').css({ 'color': '#CCCCCC'});
	}

	if($('.base').val() == 'http://cme.pushentertainment.com/releases/') {
		$('.base').css({ 'color': '#CCCCCC'});
	}
});

/***
 *	Title Manipulation Stuff
 **/
// Set banner title equal, at all times, to the value being entered
$('.title').live('keyup',function(){
	$('.titlehead').text($(this).val());
});

// The user has selected the title field, let's clear out the value (it it's the default) and set the correct color representation
$('.title').live('focus',function(){
	if($(this).val() == 'Title') {
		$('.title').css({ 'color': '#000000'});
		$(this).val('');
	}
});

$('.title').live('keyup',function(){
	// Recognize the end user is editing this - clear the error style
	$('.title').css({ 'color': '#000000'});
	$('.title').css({ 'border': 'solid 1px #DDD', 'background':'#FFFFFF'});
});

// The user has left the title field, determine if we should reset things
$('.title').live('blur',function(){
	if($(this).val() == '') {
		$('.title').css({ 'color': '#CCCCCC'});
		$(this).val('Title');
		$('.titlehead').text($(this).val());
	}
});

// Set banner title equal, at all times, to the value being entered
$('.artist').live('keyup',function(){
	$('.artisthead').text($(this).val());
});

// The user has selected the artist field, let's clear out the value (it it's the default) and set the correct color representation
$('.artist').live('focus',function(){
	if($(this).val() == 'Artist') {
		$('.artist').css({ 'color': '#000000'});
		$(this).val('');
	}
});

// The user has left the artist field, determine if we should reset things
$('.artist').live('blur',function(){
	if($(this).val() == '') {
		$('.artist').css({ 'color': '#CCCCCC'});
		$(this).val('Artist');
	}
});

// The user has selected the artist field, let's clear out the value (it it's the default) and set the correct color representation
$('.base').live('focus',function(){
	if($(this).val() == 'http://cme.pushentertainment.com/releases/') {
		$('.base').css({ 'color': '#000000'});
		$(this).val('');
	}
});

// The user has left the artist field, determine if we should reset things
$('.base').live('blur',function(){
	if($(this).val() == '') {
		$('.base').css({ 'color': '#CCCCCC'});
		$(this).val('http://cme.pushentertainment.com/releases/');
	}
});

/***
 *	Determine what should happen when the user clicks the continue button
 **/
$('.#continue').live('click',function() {
	if(check_form()) {
		$('#editbundle').submit();
	}
});

// Make sure all fields have been entered that are required - mark those accordingly that haven't
function check_form() {
	var complete = true;

	if ($('.title').val().length > 0 && $('.title').val() != 'Title') {
		// reset the field because it now has valid data
		$('.title').css({ 'border': 'solid 1px #DDD', 'background':'#FFFFFF'});
	} else {
		// the field does not have valid data
		$('.title').css({ 'border': 'solid 2px #FF0000', 'background':'#FFDFDF'});
		complete = false;
	}

	if ($('.artist').val().length > 0 && $('.artist').val() != 'Artist') {
		// reset the field because it now has valid data
		$('.artist').css({ 'border': 'solid 1px #DDD', 'background':'#FFFFFF'});
	} else {
		// the field does not have valid data
		$('.artist').css({ 'border': 'solid 2px #FF0000', 'background':'#FFDFDF'});
		complete = false;
	}

	if ($('.grid').val().length > 0) {
		// reset the field because it now has valid data
		$('.grid').css({ 'border': 'solid 1px #DDD', 'background':'#FFFFFF'});
	} else {
		// the field does not have valid data
		$('.grid').css({ 'border': 'solid 2px #FF0000', 'background':'#FFDFDF'});
		complete = false;
	}

	if ($('.base').val().length > 0 && $('.base').val().indexOf('http://') == 0 && $('.base').val().charAt($('.base').val().length-1) == '/') {
		// reset the field because it now has valid data
		$('.base').css({ 'border': 'solid 1px #DDD', 'background':'#FFFFFF'});
	} else {
		// the field does not have valid data
		$('.base').css({ 'border': 'solid 2px #FF0000', 'background':'#FFDFDF'});
		complete = false;
	}

	// If we couldn't find anything, the form is good to submit
	return complete;
}
