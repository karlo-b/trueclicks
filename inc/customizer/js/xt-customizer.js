jQuery(document).ready(function($) {

	$('#customize-control-menu_logo_container_width').on('mousedown', function() {
		$('iframe').contents().find('.xt-navigation .xt-1-4').css( 'border-right', '3px solid #0085ba' );
	}).on('mouseup', function() {
		$('iframe').contents().find('.xt-navigation .xt-1-4').css( 'border-right', 'none' );
	});

	$('#customize-control-mobile_menu_logo_container_width').on('mousedown', function() {
		$('iframe').contents().find('.xt-navigation .xt-2-3').css( 'border-right', '3px solid #0085ba' );
	}).on('mouseup', function() {
		$('iframe').contents().find('.xt-navigation .xt-2-3').css( 'border-right', 'none' );
	});

});
