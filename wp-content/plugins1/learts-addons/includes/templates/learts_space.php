<?php
/**
 * Shortcode attributes
 *
 * @var $unit
 * @var height
 * @var height_lg
 * @var height_md
 * @var height_sm
 * @var height_xs
 * @var $id
 * @var $el_class
 * @var $atts
 * Shortcode class
 * @var $this WPBakeryShortCode_Learts_Space
 */
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$el_class = $this->getExtraClass( $el_class );

$css_class = array(
	'tm-shortcode',
	'learts-space',
	$el_class
);

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,
	implode( ' ', $css_class ),
	$this->settings['base'],
	$atts );

$css_id = Learts_VC::get_learts_shortcode_id( 'learts-space' );
$this->shortcode_css( $css_id );

?>

<div class="<?php echo esc_attr( trim( $css_class ) ); ?>" id="<?php echo esc_attr( $css_id ); ?>"></div>
