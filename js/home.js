// The user has selected the "bundle id" field, let's clear out the value (it it's the default) and set the correct color representation
$('.bundleid').live('focus',function(){
	if($(this).val() == 'bundle id') {
		$('.bundleid').css({ 'color': '#000000'});
		$(this).val('');
	}
});

// The user has left the "bundle id" field, determine if we should reset things
$('.bundleid').live('blur',function(){
	if($(this).val() == '') {
		$('.bundleid').css({ 'color': '#CCCCCC'});
		$(this).val('bundle id');
	}
});

// The user has selected the "password" field, let's clear out the value (it it's the default) and set the correct color representation
$('.bundlepwd').live('focus',function(){
	if($(this).val() == 'password') {
		$('.bundlepwd').css({ 'color': '#000000'});
		$(this).val('');
	}
});

// The user has left the "password" field, determine if we should reset things
$('.bundlepwd').live('blur',function(){
	if($(this).val() == '') {
		$('.bundlepwd').css({ 'color': '#CCCCCC'});
		$(this).val('password');
	}
});
