jQuery(document).ready(function($) {

	$('.xt-control-desktop').addClass('active');

	$('.xt-responsive-options .preview-desktop').click(function() {
		$('.wp-full-overlay').removeClass('preview-mobile').removeClass('preview-tablet').addClass('preview-desktop');
		$('.xt-responsive-options button').removeClass('active');
		$(this).addClass('active');
		$('.xt-control-mobile, .xt-control-tablet').removeClass('active');
		$('.xt-control-desktop').addClass('active');
	});

	$('.xt-responsive-options .preview-tablet').click(function() {
		$('.wp-full-overlay').removeClass('preview-desktop').removeClass('preview-mobile').addClass('preview-tablet');
		$('.xt-responsive-options button').removeClass('active');
		$(this).addClass('active');
		$('.xt-control-desktop, .xt-control-mobile').removeClass('active');
		$('.xt-control-tablet').addClass('active');
	});

	$('.xt-responsive-options .preview-mobile').click(function() {
		$('.wp-full-overlay').removeClass('preview-desktop').removeClass('preview-tablet').addClass('preview-mobile');
		$('.xt-responsive-options button').removeClass('active');
		$(this).addClass('active');
		$('.xt-control-desktop, .xt-control-tablet').removeClass('active');
		$('.xt-control-mobile').addClass('active');
	});

	// Slider Custom Control
	$('.xt-input-slider-control').each(function(){
		var sliderValue = $(this).find('.customize-control-slider-value').val();
		var sliderNumber = sliderValue.match(/\d+/);
		var newSlider = $(this).find('.slider');
		var sliderMinValue = parseFloat(newSlider.attr('slider-min-value'));
		var sliderMaxValue = parseFloat(newSlider.attr('slider-max-value'));
		var sliderStepValue = parseFloat(newSlider.attr('slider-step-value'));

		newSlider.slider({
			value: sliderNumber,
			min: sliderMinValue,
			max: sliderMaxValue,
			step: sliderStepValue,
			change: function(e,ui){
				$(this).parent().find('.customize-control-slider-value').trigger('change');
			}
		});
	});

	// Change the value of the input field as the slider is moved
	$('.slider').on('slide', function(event, ui) {
		var sliderValue = $(this).parent().find('.customize-control-slider-value').val();
		var sliderSuffix = sliderValue.replace(/\d+/g, '');

		$(this).parent().find('.customize-control-slider-value').val(ui.value + sliderSuffix);
	});

	// Reset slider and input field back to the default value
	$('.slider-reset').on('click', function() {
		var resetValue = $(this).attr('slider-reset-value');
		var sliderNumber = resetValue.match(/\d+/);
		$(this).parent().find('.customize-control-slider-value').val(resetValue);
		$(this).parent().find('.slider').slider('value', sliderNumber);
	});

	// Update slider if the input field loses focus as it's most likely changed
	$('.customize-control-slider-value').blur(function() {
		var resetValue = $(this).val();
		var sliderNumber = resetValue.match(/\d+/);
		var sliderSuffix = resetValue.replace(/\d+/g, '');
		var slider = $(this).parent().find('.slider');
		var sliderMinValue = parseInt(slider.attr('slider-min-value'));
		var sliderMaxValue = parseInt(slider.attr('slider-max-value'));

		// Make sure our manual input value doesn't exceed the minimum & maxmium values
		if(sliderNumber < sliderMinValue) {
			sliderNumber = sliderMinValue;
			$(this).val(sliderNumber + sliderSuffix);
		}
		if(sliderNumber > sliderMaxValue) {
			sliderNumber = sliderMaxValue;
			$(this).val(sliderNumber + sliderSuffix);
		}
		$(this).parent().find('.slider').slider('value', sliderNumber);
	});

});
