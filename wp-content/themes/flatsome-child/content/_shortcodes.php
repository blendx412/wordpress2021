<?php

/**
 * Show a single product page.
 *
 * @param array $atts Attributes.
 * @return string
 */
function display_custom_product_page($atts) {
	if ( empty( $atts ) ) {
		return '';
	}
	if ( ! isset( $atts['id'] ) && ! isset( $atts['sku'] ) ) {
		return '';
	}
	$args = array(
		'posts_per_page'      => 1,
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'no_found_rows'       => 1,
	);
	if ( isset( $atts['sku'] ) ) {
		$args['meta_query'][] = array(
			'key'     => '_sku',
			'value'   => sanitize_text_field( $atts['sku'] ),
			'compare' => '=',
		);
		$args['post_type'] = array( 'product', 'product_variation' );
	}
	if ( isset( $atts['id'] ) ) {
		$args['p'] = absint( $atts['id'] );
	}
	// Don't render titles if desired.
	if ( isset( $atts['show_title'] ) && ! $atts['show_title'] ) {
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
	}
	// Change form action to avoid redirect.
	add_filter( 'woocommerce_add_to_cart_form_action', '__return_empty_string' );
	$single_product = new WP_Query( $args );
	$preselected_id = '0';
	// Check if sku is a variation.
	if ( isset( $atts['sku'] ) && $single_product->have_posts() && 'product_variation' === $single_product->post->post_type ) {
		$variation  = new WC_Product_Variation( $single_product->post->ID );
		$attributes = $variation->get_attributes();
		// Set preselected id to be used by JS to provide context.
		$preselected_id = $single_product->post->ID;
		// Get the parent product object.
		$args = array(
			'posts_per_page'      => 1,
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'no_found_rows'       => 1,
			'p'                   => $single_product->post->post_parent,
		);
		$single_product = new WP_Query( $args );
	?>
		<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				var $variations_form = $( '[data-product-page-preselected-id="<?php echo esc_attr( $preselected_id ); ?>"]' ).find( 'form.variations_form' );
				<?php foreach ( $attributes as $attr => $value ) { ?>
					$variations_form.find( 'select[name="<?php echo esc_attr( $attr ); ?>"]' ).val( '<?php echo esc_js( $value ); ?>' );
				<?php } ?>
			});
		</script>
	<?php
	}
	// For "is_single" to always make load comments_template() for reviews.
	$single_product->is_single = true;
	ob_start();
	global $wp_query;
	// Backup query object so following loops think this is a product page.
	$previous_wp_query = $wp_query;
	// @codingStandardsIgnoreStart
	$wp_query          = $single_product;
	// @codingStandardsIgnoreEnd
	wp_enqueue_script( 'wc-single-product' );
	while ( $single_product->have_posts() ) {
		$single_product->the_post()
		?>
		<div class="single-product" data-product-page-preselected-id="<?php echo esc_attr( $preselected_id ); ?>">
			<?php wc_get_template_part( 'content', 'single-product' ); ?>
		</div>
		<?php
	}
	// Restore $previous_wp_query and reset post data.
	// @codingStandardsIgnoreStart
	$wp_query = $previous_wp_query;
	// @codingStandardsIgnoreEnd
	wp_reset_postdata();
	// Re-enable titles if they were removed.
	if ( isset( $atts['show_title'] ) && ! $atts['show_title'] ) {
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
	}
	additional_simple_add_to_cart();
	remove_filter( 'woocommerce_add_to_cart_form_action', '__return_empty_string' );
	return '<div class="woocommerce">' . ob_get_clean() . '</div>';
}

//add_shortcode('custom_product_page', 'display_custom_product_page');

function display_video_mp4($atts) {

	if (!isset($atts['url'])) {
		$video_url = '';
	} else {
		$video_url = $atts['url'];
	}

	$video = '<video style="display:block;width:initial;margin:0 auto" muted="" autoplay="" playsinline="" loop="" id="video-landing">';
    $video .= '<source src="'.$video_url.'" type="video/mp4" />';
    $video .= '</video>';

    return $video;
}

add_shortcode('display_video', 'display_video_mp4');

?>
