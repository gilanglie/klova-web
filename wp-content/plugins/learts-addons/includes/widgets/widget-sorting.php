<?php
/**
 * Widget sort by
 *
 */
if ( ! class_exists( 'Learts_Widget_Sorting' ) ) {

	add_action( 'widgets_init', 'load_learts_sorting_widget' );

	function load_learts_sorting_widget() {
		register_widget( 'Learts_Widget_Sorting', 1 );
	}

	class Learts_Widget_Sorting extends WPH_Widget {

		function __construct() {

			// Configure widget array
			$args = array(
				'slug'        => 'tm_sorting',
				// Widget Backend label
				'label'       => '&#x1f503; &nbsp;' . esc_html__( 'Learts WooCommerce Sort by', 'learts' ),
				// Widget Backend Description
				'description' => __( 'Sort products by name, price, popularity etc.', 'learts' ),
			);

			// Configure the widget fields

			// fields array
			$args['fields'] = array(
				array(
					'id'   => 'title',
					'type' => 'text',
					'std'  => __( 'Sort by', 'learts' ),
					'name' => __( 'Title', 'learts' ),
				),
			);

			// create widget
			$this->create_widget( $args );
		}

		function widget( $args, $instance ) {

			if ( ! woocommerce_products_will_display() ) {
				return;
			}

			$orderby                 = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby',
				get_option( 'woocommerce_default_catalog_orderby' ) );
			$show_default_orderby    = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby',
					get_option( 'woocommerce_default_catalog_orderby' ) );
			$catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby',
				array(
					'menu_order' => __( 'Default', 'learts' ),
					'popularity' => __( 'Popularity', 'learts' ),
					'rating'     => __( 'Average rating', 'learts' ),
					'date'       => __( 'Newness', 'learts' ),
					'price'      => __( 'Price: low to high', 'learts' ),
					'price-desc' => __( 'Price: high to low', 'learts' ),
				) );

			if ( ! $show_default_orderby ) {
				unset( $catalog_orderby_options['menu_order'] );
			}

			if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
				unset( $catalog_orderby_options['rating'] );
			}

			echo '' . $args['before_widget'];

			if ( $title = apply_filters( 'widget_title',
				empty( $instance['title'] ) ? '' : $instance['title'],
				$instance ) ) {
				echo  ''  . $args['before_title'] . $title . $args['after_title'];
			}

			wc_get_template( 'loop/orderby.php',
				array(
					'catalog_orderby_options' => $catalog_orderby_options,
					'orderby'                 => $orderby,
					'show_default_orderby'    => $show_default_orderby,
					'list'                    => true,
				) );

			echo '' . $args['after_widget'];
		}
	}
}
