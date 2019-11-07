(function($) {

	/**
	 * add aria-haspopup="true" to all sub-menu li's
	 */
	 $('.menu-item-has-children').each(function() {
	 	$(this).attr( 'aria-haspopup', 'true' );
	 });

	/**
	 * ScrollTop
	 */
	if ($('.scrolltop').length) {

		var scrollTopVal = $('.scrolltop').attr('data-scrolltop-value');

		$(window).scroll(function () {
			if ($(this).scrollTop() > scrollTopVal) {
				$('.scrolltop').fadeIn();
			} else {
				$('.scrolltop').fadeOut();
			}
		});

		$('.scrolltop').click(function() {
			$('body').attr('tabindex', '-1').focus();
			$(this).blur();
			$('body, html').animate({ scrollTop: 0 }, 500);
		});
	}

	/**
	 * Search Menu Item
	 */
	 $('.xt-menu-item-search').click(function(event) {

		event.stopPropagation();

		$('.xt-navigation .xt-menu > li').slice(-3).addClass('calculate-width');
    	var itemWidth = 0;
		$('.calculate-width').each(function() {
			itemWidth += $(this).outerWidth();
		});
		if( itemWidth < 200 ) {
			var itemWidth = 250;
		}

		if( $(this).hasClass('active') ) {
			$(this).removeClass('active').attr('aria-expanded', 'false');
			$('.xt-menu-search', this).stop().animate({opacity:'0', width:'0px'}, 250, function() {
				$(this).css({display:'none'});
			});
		} else {
			$(this).addClass('active').attr('aria-expanded', 'true');
			$('.xt-menu-search', this).stop().css({display:'block'}).animate({width : itemWidth, opacity : '1'}, 200);
			$('input[type=search]', this).val('').focus();
		}

	});

	 function searchClose() {

		if ( $('.xt-menu-item-search').hasClass('active') ) {

			$('.xt-menu-search').stop().animate({opacity:'0', width:'0px'}, 250, function() {
				$(this).css({display:'none'});
			});

			setTimeout(function(){
				$('.xt-menu-item-search').removeClass('active').attr('aria-expanded', 'false');
			}, 400);
		}

	 }

	$(window).click(function() {
		searchClose();
	});

	$(document).keyup(function(e) {
		if (e.keyCode === 27) {
			searchClose();
		}
	});

	/**
	 * Contact Form 7 Tips
	 */
	$('.wpcf7-form-control-wrap').hover(function(){
		$('.wpcf7-not-valid-tip', this).fadeOut();
	});

	/**
	 * Sub Menu Animation – Fade
	 */
	var duration = $(".xt-navigation").data('sub-menu-animation-duration');

	// Fade Animation
	$('.xt-sub-menu-animation-fade > .menu-item-has-children').hover(function() {
		$('.sub-menu', this).first().stop().fadeIn(duration);
	},
	function(){
		$('.sub-menu', this).first().stop().fadeOut(duration);
	});

	/**
	 * Sub Menu Animation – Second Level
	 *
	 * Excluding Mega Menu – this is always going to be a Fade effect
	 */
    $('.xt-sub-menu > .menu-item-has-children:not(.xt-mega-menu) .menu-item-has-children').hover(function() {
		$('.sub-menu', this).first().stop().css({display:'block'}).animate({opacity:'1'}, duration);
	},
	function(){
		$('.sub-menu', this).first().stop().animate({opacity:'0'}, duration, function() {
			$(this).css({display:'none'});
		});
	});

	/**
	 * Window Load
	 *
	 * Firing triggers after page has been loaded
	 */
	$(window).load(function(){

		$('.opacity').delay(200).animate({opacity:'1'}, 200);
		$('.display-none').show();
		$(window).trigger('resize');
		$(window).trigger('scroll');

	});

	/**
	 * Remove Boxed Layout
	 */
	var mtpagemargin = $('.xt-page').css('margin-top');

	$(window).resize(function(){
		var mtpagewidth = $('.xt-page').width();

		if(mtpagewidth >= $(window).width()) {
			$('.xt-page').css({'margin-top':'0','margin-bottom':'0'})
		} else {
			$('.xt-page').css({'margin-top': mtpagemargin,'margin-bottom':mtpagemargin})
		}
	});

	/**
	 * Centered Menu
	 */
	if ( $('.xt-menu-centered').length ) {
		var menu_items = $('.xt-navigation .xt-menu > li > a').length;
		var divided = menu_items/2;
		var divided = Math.floor(divided);
		var divided = divided -1;

		$('.xt-menu-centered .logo-container').insertAfter('.xt-navigation .xt-menu >li:eq('+ divided +')').css({'display':'block'});
	}

	$('body').mousedown(function() {
		$(this).addClass('using-mouse');
		$('.menu-item-has-children').removeClass('xt-sub-menu-focus');
	});
	$('body').keydown(function() {
		$(this).removeClass('using-mouse');
	});

	function XT_on_focus() {

		if( $('body').hasClass('using-mouse') ) return;
		if( !$('#navigation > ul').hasClass('xt-sub-menu') ) return;

		$('.menu-item-has-children').removeClass('xt-sub-menu-focus');
		$(this).parents('.menu-item-has-children').addClass('xt-sub-menu-focus');

	}

	$('.xt-menu-container #navigation a').on('focus', XT_on_focus );
	$('.xt-menu-container #navigation a').on('blur', XT_on_focus );


	/* Sticky Menu
	========================================================================== */

	// don't take it further if the navigation doesn't exist
	if ($('.xt-navigation').length == 0) return;

	// Sticky Vars
	var sticky = $('.xt-navigation').data('sticky');

	var delay = $(".xt-navigation").data('sticky-delay');
	var animation = $(".xt-navigation").data('sticky-animation');
	var duration = $(".xt-navigation").data('sticky-animation-duration');

	var offset_top = $('.xt-navigation').offset().top;

	var fired = 0;
	var lastScrollTop = 0;

	var distance = parseInt(offset_top) + parseInt(delay);

	var menu_logo = $('.xt-logo img').attr('src');
	var menu_active_logo = $('.xt-logo').data("menu-active-logo");

	// Sticky Navigation
	function stickyNavigation() {

	var scroll_top = $(window).scrollTop();

	var navHeight = $('.xt-navigation').outerHeight();

		if (scroll_top > distance && fired == '0') {

			$('.xt-navigation').addClass('xt-navigation-active');

			if(animation == 'slide') {

				$('.xt-navigation').css({ 'position':'fixed', 'left':'0', 'zIndex':'666', 'top': -navHeight }).animate({'top':0}, duration);

			} else if(animation == 'fade') {

				$('.xt-navigation').css({ 'display':'none', 'position':'fixed', 'top':'0', 'left':'0', 'zIndex':'666' }).fadeIn(duration);

			} else {

				$('.xt-navigation').css({ 'position': 'fixed', 'top':'0', 'left':'0', 'zIndex':'666' });

				if(animation == 'scroll') {

					$('.xt-navigation').addClass('xt-navigation-animate');

				}

			}

			if (!$('body').hasClass('xt-transparent-header')) {

				$('.xt-page-header').css('marginTop', navHeight);

			}

			if ($('.xt-logo').data('menu-active-logo')) {
				$('.xt-logo img').attr('src', menu_active_logo);
				$('.xt-mobile-logo img').attr('src', menu_active_logo);
			}

			fired = '1';

		} else if (scroll_top < distance && fired == '1') {

			$('.xt-navigation').removeClass('xt-navigation-active xt-navigation-animate');

			if (!$('body').hasClass('xt-transparent-header')) {

				$('.xt-navigation').css({ 'position':'', 'top':'', 'left':'', 'zIndex':'' });
				$('.xt-page-header').css('marginTop', '');

			} else {

				$('.xt-navigation').css({ 'position':'absolute', 'top':'', 'left':'', 'zIndex':'' });

			}

			if ($('.xt-logo').data('menu-active-logo')) {
				$('.xt-logo img').attr('src', menu_logo);
				$('.xt-mobile-logo img').attr('src', menu_logo);
			}

			fired = '0';

		}

	};

	// Hide on Scroll
	function HideOnScroll() {
		var scroll_top = $(window).scrollTop();
		var navHeight = $('.xt-navigation').outerHeight();

	    if(Math.abs(lastScrollTop - scroll_top) <= delay) return;

		if (scroll_top > lastScrollTop && scroll_top > navHeight){
			// Scroll Down
			$('.xt-navigation').css({'top':-navHeight});
			$('.xt-navigation').removeClass('xt-navigation-scroll-up').addClass('xt-navigation-scroll-down');
		} else {
			// Scroll Up
			if(scroll_top + $(window).height() < $(document).height()) {
				$('.xt-navigation').css({'top':'0px'});
				$('.xt-navigation').removeClass('xt-navigation-scroll-down').addClass('xt-navigation-scroll-up');
			}
		}

		lastScrollTop = scroll_top;

	}


	// execute
	if(sticky) {

		$(window).scroll(function() {

			stickyNavigation();

			if(sticky && animation == 'scroll') {

				HideOnScroll();

			}

		});

	}

		// Transparent Header Advanced
		$('.xt-transparent-header-advanced').toggle(function(e) {
			e.preventDefault();
			$(this).html('- Advanced');
			$('.xt-transparent-header-advanced-wrapper').slideDown();
		}, function() {
			$(this).html('+ Advanced');
			$('.xt-transparent-header-advanced-wrapper').slideUp();
		});

		var modal = document.querySelector(".xt-modal");
		var trigger = document.querySelector(".trigger");
		var closeButton = document.querySelector(".close-button");

		function toggleModal() {
			modal.classList.toggle("show-modal");
		}

		function windowOnClick(event) {
			if (event.target === modal) {
				toggleModal();
				console.log('cli');
			}
		}
	if (trigger) {
		trigger.addEventListener("click", toggleModal);
	}
	if (closeButton) {
		closeButton.addEventListener("click", toggleModal);
	}	
		window.addEventListener("click", windowOnClick);

	equalheight = function (container) {
		var currentTallest = 0,
			currentRowStart = 0,
			rowDivs = new Array(),
			$el,
			topPosition = 0;
		$(container).each(function () {

			$el = $(this);
			$($el).outerHeight('auto')
			topPostion = $el.position().top;

			if (currentRowStart != topPostion) {
				for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
					rowDivs[currentDiv].outerHeight(currentTallest);
				}
				rowDivs.length = 0; // empty the array
				currentRowStart = topPostion;
				currentTallest = $el.outerHeight();
				rowDivs.push($el);
			} else {
				rowDivs.push($el);
				currentTallest = (currentTallest < $el.outerHeight()) ? ($el.outerHeight()) : (currentTallest);
			}
			for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
				rowDivs[currentDiv].outerHeight(currentTallest);
			}
		});
	}
	$(window).load(function () {
		equalheight('.xt-podcast-equal .xt-image-block');
		
		
	});
	$(window).resize(function () {
		equalheight('.xt-podcast-equal .xt-image-block');
		
		
	});
/*
	$('a[href*="#"]')
		// Remove links that don't actually link to anything
		.not('[href="#"]')
		.not('[href="#0"]')
		.click(function (event) {
			// On-page links
			if (
				location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') &&
				location.hostname == this.hostname
			) {
				// Figure out element to scroll to
				var target = $(this.hash);
				target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
				// Does a scroll target exist?
				if (target.length) {
					// Only prevent default if animation is actually gonna happen
					event.preventDefault();
					$('html, body').animate({
						scrollTop: target.offset().top
					}, 1000, function () {
						// Callback after animation
						// Must change focus!
						var $target = $(target);
						$target.focus();
						if ($target.is(":focus")) { // Checking if the target was focused
							return false;
						} else {
							$target.attr('tabindex', '-1'); // Adding tabindex for elements not focusable
							$target.focus(); // Set focus again
						};
					});
				}
			}
		});
*/

		 $('.slider-for').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			fade: true,
			asNavFor: '.slider-nav'
		});
		$('.slider-nav').slick({
			slidesToShow: 4,
			slidesToScroll: 1,
			asNavFor: '.slider-for',
			dots: false,
			arrows: true,
			prevArrow: '<div class="slick-prev">&#10229;</div>',
			nextArrow: '<div class="slick-next">&#10230;</div>',
			focusOnSelect: true,
			responsive: [
				{
					breakpoint: 767,
					settings: {
						slidesToShow: 2,
						slidesToScroll: 1
					}
				}

			]

		});

	$('.xt-image-block p:empty').remove();

	var rangeSlider = document.getElementById('range-slider');
	noUiSlider.create(rangeSlider, {
		start: 1000,
		connect: 'lower',
		direction: 'ltr',
		step: 1000,
		snap: true,
		format: wNumb({
			decimals: 0,
			prefix: '$',
			mark: '.',
	        thousand: ',',
		}),
		range: {
			'min': [5000],
			'9%': [10000],
			'18%': [25000],
			'27%': [50000],
			'36%': [100000],
			'45%': [250000],
			'55%': [500000],
			'64%': [750000],
			'73%': [1000000],
			'82%': [2000000],
			'91%': [3000000],
			'max': [3500000],
		},
		tooltips: true,
	});

rangeSlider.noUiSlider.on('update', function( values, handle ){
		 var billedYearly = $('.billed-yearly');
		 var saveYearly = $('.save-yearly');
		 var yearlyPrice = $('.yearly-price');
		 var monthlyPrice = $('.monthly-price');

var moneyFormat = wNumb({
	mark: '.',
	thousand: ',',
	prefix: '$',
});
	
		 console.log(values[0]);
	if(values[0] =='$5,000'){
		billedYearly.html(moneyFormat.to(468));
		saveYearly.html(moneyFormat.to(120));
		yearlyPrice.html(moneyFormat.to(39));
		monthlyPrice.html(moneyFormat.to(49));
		$('.noUi-tooltip').prepend('Up to ');
	}
	 if(values[0] =='$10,000'){
		billedYearly.html(moneyFormat.to(756));
		saveYearly.html(moneyFormat.to(192));
		yearlyPrice.html(moneyFormat.to(63));
		monthlyPrice.html(moneyFormat.to(79));
		$('.noUi-tooltip').prepend('Up to ');
	}
	 if(values[0] =='$25,000'){
		billedYearly.html(moneyFormat.to(948));
		saveYearly.html(moneyFormat.to(240));
		yearlyPrice.html(moneyFormat.to(79));
		monthlyPrice.html(moneyFormat.to(99));
		$('.noUi-tooltip').prepend('Up to ');
	}
	 if(values[0] =='$50,000'){
		billedYearly.html(moneyFormat.to(948));
		saveYearly.html(moneyFormat.to(1428));
		yearlyPrice.html(moneyFormat.to(360));
		monthlyPrice.html(moneyFormat.to(148));
		$('.noUi-tooltip').prepend('Up to ');
	}
	 if(values[0] =='$100,000'){
		billedYearly.html(moneyFormat.to(2388));
		saveYearly.html(moneyFormat.to(600));
		yearlyPrice.html(moneyFormat.to(199));
		monthlyPrice.html(moneyFormat.to(249));
		$('.noUi-tooltip').prepend('Up to ');
	}
	 if(values[0] =='$250,000'){
		billedYearly.html(moneyFormat.to(3348));
		saveYearly.html(moneyFormat.to(840));
		yearlyPrice.html(moneyFormat.to(279));
		monthlyPrice.html(moneyFormat.to(349));
		$('.noUi-tooltip').prepend('Up to ');
	}
	 if(values[0] =='$500,000'){
		billedYearly.html(moneyFormat.to(4788));
		saveYearly.html(moneyFormat.to(1200));
		yearlyPrice.html(moneyFormat.to(399));
		monthlyPrice.html(moneyFormat.to(499));
		$('.noUi-tooltip').prepend('Up to ');
	}
	 if(values[0] =='$750,000'){
		billedYearly.html(moneyFormat.to(6708));
		saveYearly.html(moneyFormat.to(1680));
		yearlyPrice.html(moneyFormat.to(599));
		monthlyPrice.html(moneyFormat.to(699));
		$('.noUi-tooltip').prepend('Up to ');
	}
	 if(values[0] =='$1,000,000'){
		billedYearly.html(moneyFormat.to(8628));
		saveYearly.html(moneyFormat.to(2160));
		yearlyPrice.html(moneyFormat.to(719));
		monthlyPrice.html(moneyFormat.to(899));
		$('.noUi-tooltip').prepend('Up to ');
	}
	 if(values[0] =='$2,000,000'){
		billedYearly.html(moneyFormat.to(14388));
		saveYearly.html(moneyFormat.to(3600));
		yearlyPrice.html(moneyFormat.to(1199));
		monthlyPrice.html(moneyFormat.to(1499));
		$('.noUi-tooltip').prepend('Up to ');
	}
	 if(values[0] =='$3,000,000'){
		billedYearly.html(moneyFormat.to(19188));
		saveYearly.html(moneyFormat.to(4800));
		yearlyPrice.html(moneyFormat.to(1599));
		monthlyPrice.html(moneyFormat.to(1999));
		$('.noUi-tooltip').prepend('Up to ');
	}
	 if(values[0] =='$3,500,000'){
		$('.noUi-tooltip').prepend('Above ');
		$('.xt-show-above').show();
		$('.xt-hide-above').hide();
		
	}else{
		$('.xt-show-above').hide();
		$('.xt-hide-above').show();

	}

	 
});

})( jQuery );
