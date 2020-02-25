jQuery(document).ready(function($) {

	iscwp_slickslider();

	// For direct api request to scrap instagram data
	$('.iscwp-main-wrp').each(function(){
		
		var main_ele	= $(this);
		var gallery_ele = main_ele.find('.iscwp-gallery-main-wrap');
		var is_ajax		= gallery_ele.attr('data-ajax');
		var username 	= gallery_ele.attr('data-user');
		var shrtd_conf = gallery_ele.attr('data-ajax-conf');
		
		if( is_ajax == 'true' && username != '' && shrtd_conf != '' ) {

			var insta_url = "https://www.instagram.com/"+username+"/?__a=1";
			var shrtd_param = $.parseJSON(shrtd_conf);
			main_ele.append('<div class="iscwp-center iscwp-load-text">'+Iscw.load_text+'</div>');

			/* Creating object */
			var shrtd_obj = {};

			/* Assign to object */
			$.each(shrtd_param, function (key,val) {
				shrtd_obj[key] = val;
			});

			$.ajax({
			    type: 'GET',
			    url: insta_url,
			    error: function () {
			        // Do it later
			    },
			    success: function (data) {
			       	
			       	var result = data.graphql.user;
			       	var data = {
			            action  : 'iscw_get_user_media_data',
			            dataType: 'json',
			            shrtd_param : shrtd_obj,
			            username : username,
			            media_data : JSON.stringify(result),
			        };

			        $.post(Iscw.ajaxurl,data,function(response) {

			        	if( response.success == 1 ) {

			        		main_ele.find('.iscwp-load-text').remove();
			        		main_ele.find('.iscwp-result-wrap').html(response.data);
			        		main_ele.find('.iscwp-popup-html').html(response.popup);
			        		gallery_ele.removeAttr('data-ajax');

			        		// For Slider
			        		iscwp_slickslider();
			        	}
			        });
			    }
			});
		}
	});
	
	// Popup Gallery
	$( '.iscwp-popup-gallery' ).each(function( index ) {
		
		var gallery_id 	= $(this).attr('id');
		
		if( typeof(gallery_id) !== 'undefined' && gallery_id != '' ) {
			
			var user 		= $(this).attr('data-user');
			var popup_conf 	= $.parseJSON( $(this).closest('.iscwp-main-wrp').find('.wp-iscwp-popup-conf').attr('data-conf') );

			$('#'+gallery_id).magnificPopup({
				delegate: 'a.iscwp-img-link',
				type: 'inline',
				mainClass: 'iscwp-mfp-popup',
				tLoading: 'Loading image #%curr%...',
				gallery: {
					enabled : (popup_conf.popup_gallery) == "true" ? true : false,
				},
				callbacks: {
					change: function() {

						var popup_obj 		= this.content;
						var media_shortcode = popup_obj.attr('data-shortcode');

						if( media_shortcode ) {
							
							popup_obj.find('.iscwp-loader').fadeIn();
							popup_obj.find('.iscwp-error').remove();

							// Creating object
							var shortcode_obj = {};

							// Creating object
							$.each(popup_conf, function (key,val) {
								shortcode_obj[key] = val;
							});

							var data = {
					            action  		: 'iscw_get_media_data',
					            shortcode   	: media_shortcode,
					            user 			: user,
					            shrt_param 		: shortcode_obj
					        };

					        $.post(Iscw.ajaxurl, data, function(response) {
					        	var result = jQuery.parseJSON(response);

					        	if(result.success == 1) {
					        		popup_obj.find('.iscwp-loader').hide();
					        		popup_obj.find('.iscwp-popup-body').html(result.data);
					        		popup_obj.removeAttr('data-shortcode');
					        	} else {
					        		popup_obj.find('.iscwp-loader').hide();
					        		popup_obj.find('.iscwp-popup-body').html('<div class="iscwp-error">'+result.msg+'</div>');
					        	}
					        });
					    }
					}
				}
			});
		}
	});
});

function iscwp_slickslider() {

	jQuery( '.iscwp-gallery-slider' ).each(function( index ) {

		var slider_id   = jQuery(this).attr('id');
		var slider_conf = jQuery.parseJSON( jQuery(this).closest('.iscwp-gallery-slider-wrp').find('.iscwp-gallery-slider-conf').attr('data-conf'));
		var is_ajax		= jQuery(this).attr('data-ajax');

		if( is_ajax == 'true' || jQuery(this).hasClass('slick-initialized') ) {
			return;
		}

		jQuery('#'+slider_id).slick({
			dots			: (slider_conf.dots) == "true" ? true : false,
			infinite		: (slider_conf.loop) == "true" ? true : false,
			arrows			: (slider_conf.arrows) == "true" ? true : false,
			speed			: parseInt(slider_conf.speed),
			autoplay		: (slider_conf.autoplay) == "true" ? true : false,
			autoplaySpeed	: parseInt(slider_conf.autoplay_interval),
			slidesToShow	: parseInt(slider_conf.slidestoshow),
			slidesToScroll	: parseInt(slider_conf.slidestoscroll),
			rtl             : (Iscw.is_rtl == 1) ? true : false,
			mobileFirst    	: (Iscw.is_mobile == 1) ? true : false,
			responsive 		: [{
				breakpoint 	: 1023,
				settings 	: {
					slidesToShow 	: (parseInt(slider_conf.slidestoshow) > 3) ? 3 : parseInt(slider_conf.slidestoshow),
					slidesToScroll 	: 1,
					infinite 		: (slider_conf.loop) == "true" ? true : false,
					dots 			: (slider_conf.dots) == "true" ? true : false,
				}
			},{
				breakpoint	: 767,	  			
				settings	: {
					slidesToShow 	: (parseInt(slider_conf.slidestoshow) > 2) ? 2 : parseInt(slider_conf.slidestoshow),
					slidesToScroll 	: 1,
					infinite 		: (slider_conf.loop) == "true" ? true : false,
					dots 			: (slider_conf.dots) == "true" ? true : false,
				}
			},
			{
				breakpoint	: 479,
				settings	: {
					slidesToShow 	: 1,
					slidesToScroll 	: 1,
					dots 			: false,
					infinite 		: (slider_conf.loop) == "true" ? true : false,
				}
			},
			{
				breakpoint	: 319,
				settings	: {
					slidesToShow 	: 1,
					slidesToScroll 	: 1,
					dots 			: false,
					infinite 		: (slider_conf.loop) == "true" ? true : false,
				}
			}]
		});
	});
}