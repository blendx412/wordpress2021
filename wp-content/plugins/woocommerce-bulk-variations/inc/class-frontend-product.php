<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if( !class_exists('WCBV_Frontend_Product') ) {

	class WCBV_Frontend_Product {

		public function __construct() {

			add_filter( 'body_class', array( &$this, 'add_body_classes') );
			add_action( 'wp_head', array( &$this, 'head_handler') );

			//add additional form fields to cart form
			add_action( 'woocommerce_before_add_to_cart_button', array( &$this, 'add_form_fields') );
			//disable default quantity input
			add_filter( 'woocommerce_is_sold_individually', array(&$this, 'disable_quantity_input'), 10, 2 );

			//CART
			//handler when a product is added to the cart
			add_action( 'woocommerce_add_to_cart', array( &$this, 'add_product_to_cart'), 20, 6 );
			add_filter( 'fpd_wc_cart_item_price', array(&$this, 'set_cart_item_price'), 10, 3 );

		}

		//add fancy-product class in body
		public function add_body_classes( $classes ) {

			global $post;
			if( isset( $post->ID ) && wcbv_enabled( $post->ID ) )
				$classes[] = 'wcbv-product';

			return $classes;

		}

		//used to reposition the product image if requested
		public function head_handler() {

			global $post;

			if( isset($post->ID) && wcbv_enabled( $post->ID ) ) {

				$position = get_post_meta($post->ID, 'wcbv_position', true);

				$position = 'after_short_desc';

				//after short description
				if($position == 'after_short_desc') {
					add_action( 'woocommerce_single_product_summary', array( &$this, 'add_form'), 25 );
				}
				//after product summary
				else if($position == 'after_product_summary') {
					add_action( 'woocommerce_after_single_product', array( &$this, 'add_form'), 25 );
				}
				//before fancy product designer
				else if($position == 'before_fancy_product_designer') {
					add_action( 'fpd_before_product_designer', array( &$this, 'add_form'), 20 );
				}
				//after fancy product designer
				else if($position == 'after_fancy_product_designer') {
					add_action( 'fpd_after_product_designer', array( &$this, 'add_form'), 20 );
				}
				//default: after title
				else {
					add_action( 'woocommerce_single_product_summary', array( &$this, 'add_form'), 6 );
				}

			}

		}

		//the actual product designer will be added
		public function add_form() {

			global $product;

			if( $product->has_attributes() ) {

				WCBV_Scripts_Styles::$add_script = true;

				$attributes = $product->get_variation_attributes();
				$selected_attributes = array();
				$attribute_names = array();
				$fixed_amount = get_post_meta($product->get_id(), 'wcbv_fixed_amount', true);
				$fixed_amount = $fixed_amount === '' ? 0 : intval($fixed_amount);

				$css_classes = '';
				$layout = get_post_meta($product->get_id(), 'wcbv_layout', true);
				$layout = 'selects_one';

				if( strpos($layout, 'selects_') !== false) {

					$css_classes .= 'wcbv-columnize';
					$css_classes .= ' wcbv-' . str_replace( 'selects_', '', $layout );

				}
				else {
					$css_classes .= ' wcbv-' . $layout;
				}

				?>
				<div class="wcbv-wrapper <?php echo $css_classes; ?>">

					<div class="wcbv-attributes-head">
						<?php foreach ( $attributes as $attribute_name => $options ) :

							if( isset( $_GET[ 'attribute_' . sanitize_title( $attribute_name ) ] ) )
								$selected = wc_clean( stripslashes( urldecode( $_GET[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) );
							else
								$selected = $product->get_variation_default_attribute( $attribute_name );

							$selected_attributes['attribute_'. strtolower($attribute_name)] = $selected;
						?>
						<div>
							<?php
							$attribute_name = wc_attribute_label( $attribute_name );
							$attribute_names[] = $attribute_name;
							echo $attribute_name;
							?>
						</div>
						<?php endforeach; ?>
						<div class="wcbv-quantity"><?php echo stripslashes( wcbv_get_label_option( 'wcbv_label_quantity', 'QTY' ) ); ?></div>
						<div class="wcbv-remove"></div>
					</div>

					<div class="wcbv-variations"></div>

					<div class="wcbv-total-price price" style="display: none;">
						<?php

							$formatted_price = sprintf( get_woocommerce_price_format(), '<span class="woocommerce-Price-currencySymbol">' . get_woocommerce_currency_symbol() . '</span>', '<span class="wcbv-price-value"></span>' );
							echo '<span class="woocommerce-Price-amount amount">' . $formatted_price . '</span>';
						?>
					</div>
					<div class="wcbv-actions" style="display: none;">
						<?php if( $fixed_amount === 0 ): ?>
						<span class="<?php echo esc_attr( get_option( 'wcbv_button_css_class', 'wcbv-btn' ) ); ?>" id="wcbv-add-row">
							<?php echo stripslashes( wcbv_get_label_option( 'wcbv_label_add_variation', 'Add Variation' ) ); ?>
						</span>
						<?php endif; ?>
					</div>

				</div><!-- Wrapper --->

				<?php $replace_choose_option = true; ?>

				<script type="text/javascript">

					var wcbvOptions = {
 						attributeNames: <?php echo wp_json_encode($attribute_names); ?>,
						selectedAttributes: <?php echo wp_json_encode( $selected_attributes ); ?>,
						enableSelect: <?php echo wcbv_parse_to_int(get_post_meta($product->get_id(), 'wcbv_enable_select2', true)); ?>,
						replaceChooseOption: <?php echo $replace_choose_option; ?>,
						priceDecimalSep: "<?php echo get_option('woocommerce_price_decimal_sep'); ?>",
						priceThousandSep: "<?php echo get_option('woocommerce_price_thousand_sep'); ?>",
						fixedAmount: <?php echo $fixed_amount; ?>
					};

				</script>
				<?php
			}

		}

		//the additional form fields
		public function add_form_fields() {

			global $post;

			if( isset( $post->ID ) && wcbv_enabled( $post->ID ) ) { //todo: check if neeeds to added
				?>
				<input type="hidden" value="" name="_wcbv_variations" />
				<input type="hidden" value="" name="_wcbv_fpd_price" />
				<?php
			}

		}

		//hide wc quantity on single product pages, if bulk form is added
	    public function disable_quantity_input( $return, $product ) {

		    if( is_product() && wcbv_enabled( $product->get_id() ) )
			   return true;

		}

		//set cart item price from variation price + fpd price
		public function set_cart_item_price( $price, $cart_item, $fpd_data ) {

			if( isset($cart_item['_wcbv_fpd_price']) && wcbv_not_empty($cart_item['_wcbv_fpd_price']) ) {

				$price = floatval($cart_item['_wcbv_fpd_price']);

			}

			return $price;

		}

		//add bulk variations to the cart
		public function add_product_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {

			if( isset($_POST['_wcbv_variations']) && !empty($_POST['_wcbv_variations']) ) {

				global $woocommerce;

				$wcbv_variations = json_decode( stripslashes( $_POST['_wcbv_variations'] ) );
				unset($_POST['_wcbv_variations']); //remove wcbv variations from post otherwise endless loop

				$woocommerce->cart->set_quantity( $cart_item_key, 0 ); //remove variation thats added by standard form

				//loop through all variations from WCBV JSON
				foreach( $wcbv_variations as $wcbv_variation ) {

					$var_quantity = $wcbv_variation->wcbv_quantity;
					$var_id = $wcbv_variation->variation_id;
					$var_variations = (array) $wcbv_variation->attributes;

					//check if fpd price is sent, then add fpd price to every variation price
					if( isset($_POST['_wcbv_fpd_price']) && wcbv_not_empty($_POST['_wcbv_fpd_price']) )
						$cart_item_data['_wcbv_fpd_price'] = floatval($_POST['_wcbv_fpd_price']) + floatval($wcbv_variation->price);

					$woocommerce->cart->add_to_cart( $product_id, $var_quantity, $var_id, $var_variations, $cart_item_data );

				}

			}

		}

	}
}

new WCBV_Frontend_Product();

?>
