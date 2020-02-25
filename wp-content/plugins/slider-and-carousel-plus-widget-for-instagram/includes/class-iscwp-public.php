<?php
/**
 * Public Class
 *
 * @package Slider and Carousel Plus Widget Instagram
 * @since 1.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Iscw_Public {

	function __construct() {

		// Ajax call to get user data direct method 
		add_action( 'wp_ajax_iscw_get_user_media_data', array($this, 'iscw_get_user_media_data') );
		add_action( 'wp_ajax_nopriv_iscw_get_user_media_data', array( $this, 'iscw_get_user_media_data') );

		// Ajax call to update attachment data
		add_action( 'wp_ajax_iscw_get_media_data', array($this, 'iscw_get_media_data') );
		add_action( 'wp_ajax_nopriv_iscw_get_media_data', array( $this, 'iscw_get_media_data') );
	}

	/**
	 * Get Insta User Media Data
	 * 
	 * @package Slider and Carousel Plus Widget Instagram
	 * @since 1.9
	 */
	function iscw_get_user_media_data() {

		// Taking some defaults
		$result 	= array(
			'success' => 0
		);
		$data		= '';
		$popup_html = '';

		if( ! empty( $_POST['media_data'] ) ) {
			
			$_POST['media_data'] = stripslashes_deep( $_POST['media_data'] );
			$instagram_data = json_decode( $_POST['media_data'], true );
		}

        if( !empty($instagram_data) && !empty( $_POST['shrtd_param'] ) ){

        	$username = $_POST['username'];
			$transient_key = "wp_iscwp_media_{$username}";
			
			// Storing User Data
			iscw_user_data_store( $username, $instagram_data, $transient_key );

			extract( $_POST['shrtd_param'] );
			
			ob_start();
			// User Media details
			$insta_user_media = $instagram_data['edge_owner_to_timeline_media']['edges'];

			$userdata = array(
				'username' 			=>	(!empty($instagram_data['username'])) ? $instagram_data['username'] : '',
				'full_name'			=>	(!empty($instagram_data['full_name'])) ? $instagram_data['full_name'] : '',
				'profile_picture'	=>	(!empty($instagram_data['profile_pic_url'])) ? $instagram_data['profile_pic_url'] : '',
			);

			if( !empty($insta_user_media) ) { 
				foreach ($insta_user_media as $iscwp_key => $iscwp_data) {
					
					$iscwp_data 		= iscw_insta_image_data( $iscwp_data );
					$img_shortcode 		= $iscwp_data['shortcode'];
					$gallery_img_src 	= isset( $iscwp_data['thumbnail_resources'][$img_size] ) ? $iscwp_data['thumbnail_resources'][$img_size] : $iscwp_data['display_url'];
					$iscwp_likes 		= iscw_format_number( $iscwp_data['like_count'] );
					$iscwp_comments 	= iscw_format_number( $iscwp_data['comment_count'] );
					$instagram_link 	= $iscwp_data['link'];
					$img_caption 		= $iscwp_data['caption'];
					$iscwp_link_value 	= ($popup =='true') ? '#wp-iscwp-popup-'.$unique.'-'.$count : $instagram_link ;
					
					// Getting media data
					$media_data = iscw_user_media_data( $username, $img_shortcode );
					$location 	= isset($media_data['location']) 		? $media_data['location'] 		: '';						
					$video_url	= isset($media_data['video_url']) 		? $media_data['video_url'] 		: '';
					$popup_attr = (!$media_data) ? "data-shortcode='{$img_shortcode}'" : '';
					$wrpper_cls	= ($loop_count == 1 && $shortcode == 'iscwp-grid') ? $main_cls.' iscwp-first' : $main_cls;
					
					include( ISCW_DIR . '/templates/design-1.php' );

					// Creating Popup HTML
					if( $popup == 'true' ) {
						ob_start();
						include( ISCW_DIR . '/templates/popup/popup.php' );
						$popup_html .= ob_get_clean();
					}
					
					if( $limit == $count) {
						break;
					}

					$count++;
					$loop_count++; // Increment loop count for grid
					
					// Reset loop count
					if( $loop_count == $grid && $shortcode == 'iscwp-grid' ) {
						$loop_count = 0;
					}

				} 
			}
			$data .= ob_get_clean();

			$result['success'] 		= 1;
			$result['data'] 		= $data;
			$result['count']		= $count;
			$result['popup']		= $popup_html;

		} 

		wp_send_json($result);
	}

	/**
	 * Get Insta Media Data
	 * 
	 * @package Slider and Carousel Plus Widget Instagram
	 * @since 1.1
	 */
	function iscw_get_media_data() {

		// Taking passed data
		extract( $_POST['shrt_param'] );
		$shortcode 	= iscw_clean( $_POST['shortcode'] );
		$username 	= iscw_clean( $_POST['user'] );

		// Taking some defaults		
		$result 			= array();
		$result['success'] 	= 0;
		$result['msg'] 		= esc_html__('Sorry, Something happened wrong.', 'instagram-slider-and-carousel-plus-widget');

		if( $shortcode && $username ) {

			$transient_key 		= "wp_iscwp_media_data_{$username}";

			$stored_transient 	= get_transient( $transient_key ); // Getting cache value
			$stored_transient	= ! empty($stored_transient) ? json_decode($stored_transient, true) : array();

			if( $stored_transient === false || empty($stored_transient[$shortcode]) ) {

				$api_url 		= "https://www.instagram.com/p/{$shortcode}/?__a=1";
				$response_data 	= iscw_insta_request( $api_url );

				if( $response_data['body'] ) {
					
					$response_arr 					= json_decode($response_data['body'], true);
					$stored_transient[$shortcode]	= $response_arr;

					// Stored media data into cache
					set_transient( $transient_key, json_encode($stored_transient), 172800 );
				}
			}

			// Getting user data for popup info
			$instagram_link_main 	= 'https://www.instagram.com/';
			$instagram_data 		= iscw_get_user_media( $username );
			$insta_user_media 		= !empty($instagram_data['iscwp_data']['edge_owner_to_timeline_media']['edges']) ? $instagram_data['iscwp_data']['edge_owner_to_timeline_media']['edges'] : '';

			if( $insta_user_media ) {

				// User details
				$userdata = array(
					'username' 			=>	(!empty($instagram_data['iscwp_user_data']['username'])) 			? $instagram_data['iscwp_user_data']['username'] 		: '',
					'full_name'			=>	(!empty($instagram_data['iscwp_user_data']['full_name'])) 			? $instagram_data['iscwp_user_data']['full_name'] 		: '',
					'profile_picture'	=>	(!empty($instagram_data['iscwp_user_data']['profile_pic_url']) ) 	? $instagram_data['iscwp_user_data']['profile_pic_url'] : '',
				);

				$media_node_data 	= wp_list_pluck( $insta_user_media, 'node' );
				$media_id_data 		= wp_list_pluck( $media_node_data, 'shortcode' );
				$media_ref_key 		= array_search($shortcode, $media_id_data);
				$media_ref_data		= isset($insta_user_media[$media_ref_key]) ? $insta_user_media[$media_ref_key] : '';

				$iscwp_data 		= iscw_insta_image_data( $media_ref_data );						
				$gallery_img_src 	= $iscwp_data['standard_img'];
				$iscwp_likes 		= iscw_format_number( $iscwp_data['like_count'] );
				$iscwp_comments 	= iscw_format_number( $iscwp_data['comment_count'] );
				$instagram_link 	= $iscwp_data['link'];
				$img_caption 		= $iscwp_data['caption'];						

				// Getting media data
				$media_data 	= iscw_user_media_data( $username, $shortcode );
				$location 		= isset($media_data['location']) 		? $media_data['location'] 		: '';
				$comment_data 	= isset($media_data['comment_data']) 	? $media_data['comment_data'] 	: '';
				$video_url		= isset($media_data['video_url']) 		? $media_data['video_url'] 		: '';

				ob_start();
				include( ISCW_DIR . '/templates/popup/design-1.php' );
				$data = ob_get_clean();

				$result['success'] 	= 1;
				$result['data'] 	= $data;
				$result['msg'] 		= esc_html__('Success', 'instagram-slider-and-carousel-plus-widget');
			}

		} // End of check username and shortcode

		echo json_encode( $result );
		exit;
	}
}

$Iscw_public = new Iscw_Public();