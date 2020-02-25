<?php

/**
 * Learts Instagram shortcode
 *
 * @version 1.0
 * @package Learts
 */
class WPBakeryShortCode_Learts_Instagram extends WPBakeryShortCode {

}

// Mapping shortcode
vc_map( array(
	'name'        => esc_html__( 'Instagram', 'learts-addons' ),
	'base'        => 'learts_instagram',
	'icon'        => 'learts-element-icon-instagram',
	'category'    => sprintf( esc_html__( 'by % s', 'learts-addons' ), LEARTS_ADDONS_THEME_NAME ),
	'description' => esc_html__( 'Displays latest Instagram photos', 'learts-addons' ),
	'params'      => array(
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Instagram Username', 'learts-addons' ),
			'admin_label' => true,
			'param_name'  => 'username',
			'value'       => '',
			'description' => wp_kses( __( 'Enter Instagram username (not include @). Example: <b>thememove</b>',
				'learts-addons' ),
				array( 'b' => array() ) ),
		),

		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'View mode', 'learts-addons' ),
			'description' => esc_html__( 'Select a template to display instagram', 'learts-addons' ),
			'param_name'  => 'view',
			'admin_label' => true,
			'value'       => array(
				esc_html__( 'Grid', 'learts-addons' )     => 'grid',
				esc_html__( 'Carousel', 'learts-addons' ) => 'carousel',
			),
		),

		array(
			'type'        => 'number',
			'heading'     => esc_html__( 'Number of items', 'learts-addons' ),
			'param_name'  => 'number_items',
			'value'       => 1,
			'max'         => 1000,
			'min'         => 1,
			'step'        => 1,
			'description' => esc_html__( 'Set number of items in grid (limited to 1000)',
				'learts-addons' ),
		),

		array(
			'type'       => 'checkbox',
			'param_name' => 'loop',
			'value'      => array( esc_html__( 'Enable loop mode', 'learts-addons' ) => 'yes' ),
			'std'        => 'yes',
			'dependency' => array( 'element' => 'view', 'value' => array( 'carousel' ) ),
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'auto_play',
			'value'      => array( esc_html__( 'Enable carousel autoplay', 'learts-addons' ) => 'yes' ),
			'dependency' => array(
				'element' => 'view',
				'value'   => array( 'carousel' ),
			),
		),

		array(
			'type'       => 'number',
			'param_name' => 'auto_play_speed',
			'heading'    => esc_html__( 'Auto play speed', 'learts-addons' ),
			'value'      => 5,
			'max'        => 10,
			'min'        => 3,
			'step'       => 0.5,
			'suffix'     => 'seconds',
			'dependency' => array(
				'element' => 'auto_play',
				'value'   => 'yes',
			),
		),

		array(
			'type'       => 'dropdown',
			'param_name' => 'nav_type',
			'heading'    => esc_html__( 'Navigation type', 'learts-addons' ),
			'value'      => array(
				esc_html__( 'Arrows', 'learts-addons' ) => 'arrows',
				esc_html__( 'Dots', 'learts-addons' )   => 'dots',
				esc_html__( 'Arrows & Dots', 'learts-addons' )  => 'both',
				esc_html__( 'None', 'learts-addons' )   => '',
			),
			'dependency' => array(
				'element' => 'view',
				'value'   => array( 'carousel' ),
			),
		),

		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Number of images to show', 'learts-addons' ),
			'param_name' => 'number_of_items_to_show',
			'value'      => array(
				1,
				2,
				3,
				4,
				5,
				6,
			),
			'std'        => 4,
		),

		array(
			'type'       => 'number',
			'heading'    => esc_html__( 'Item spacing', 'learts-addons' ),
			'param_name' => 'spacing',
			'value'      => 15,
			'max'        => 50,
			'min'        => 5,
			'step'       => 1,
			'suffix'     => 'px',
		),
		array(
			'type'        => 'textarea_html',
			'heading'     => esc_html__( 'Text', 'learts-addons' ),
			'param_name'  => 'content',
			'value'       => esc_html__( 'Follow us on Instagram', 'learts-addons' ),
			'description' => esc_html__( 'Leave empty to hide it', 'learts-addons' ),
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'show_username',
			'value'      => array(
				esc_html__( 'Show Instagram username', 'learts-addons' ) => 'yes',
			),
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'show_likes_comments',
			'value'      => array(
				esc_html__( 'Show likes and comments', 'learts-addons' ) => 'yes',
			),
			'std'        => 'yes',
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'link_new_page',
			'value'      => array(
				esc_html__( 'Open links in new page', 'learts-addons' ) => 'yes',
			),
		),
		array(
			'type'       => 'checkbox',
			'param_name' => 'square_media',
			'value'      => array(
				esc_html__( 'Show square media', 'learts-addons' ) => 'yes',
			),
			'std'        => 'yes',
		),
		Learts_VC::get_param( 'animation' ),
		Learts_VC::get_param( 'el_class' ),
		Learts_VC::get_param( 'css' ),
	),

) );
