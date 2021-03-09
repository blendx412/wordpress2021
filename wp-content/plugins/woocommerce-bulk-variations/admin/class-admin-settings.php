<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if( !class_exists('WCBV_Admin_Settings') ) {

	class WCBV_Admin_Settings {

		public function __construct() {

			add_filter( 'woocommerce_general_settings', array( &$this, 'add_options_to_general_settings' ) );

		}

		// add options to the general settings of woocommerce
		public function add_options_to_general_settings( $general_settings ) {

			$labels = array(
				array(
					'title'   => __( 'Label: Quantity', 'radykal' ),
					'id'      => 'wcbv_label_quantity',
					'type'    => 'textarea',
					'css' 		=> 'width:500px;height: 32px;',
					'default' => 'QTY',
				),
				array(
					'title'   => __( 'Label: Add Variation', 'radykal' ),
					'id'      => 'wcbv_label_add_variation',
					'type'    => 'textarea',
					'css' 		=> 'width:500px;height: 32px;',
					'default' => 'Add Variation',
				)
			);

			array_push($general_settings,

				array(	'title' => __( 'Bulk Variations', 'radykal' ), 'type' => 'title', 'id' => 'woo_bulk_variations' ),

				array(
					'title'   => __( 'Button CSS Class', 'radykal' ),
					'desc_tip'    => __( 'So the buttons are having the same style as in your theme, you can set an own CSS class for the buttons.', 'radykal' ),
					'id'      => 'wcbv_button_css_class',
					'type'    => 'text',
					'css' 		=> 'width:500px;',
					'default' => 'wcbv-btn',
				),

				array(
					'title'   => __( 'Include Select2 JS/CSS', 'radykal' ),
					'desc'    => __( 'To avoid conflicts in the frontend, uncheck this option if your theme or another plugin already includes the Select2 library.', 'radykal' ),
					'id'      => 'wcbv_include_select2',
					'default' => 'yes',
					'type'    => 'checkbox',
				)

			);

			if( defined('ICL_LANGUAGE_CODE') ) {

				//get active languages from WPML
				$languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc&skip_missing=0' );

				if (!empty($languages) && sizeof($languages) > 0 ) {

					foreach($labels as $label) {

						$label_id = $label['id'];
						$i=0;

						foreach($languages as $key => $language) {

							if($i > 0)
								$label['title'] = '';

							$label['desc'] = '<i style="font-size: 12px;">'.$languages[$key]['translated_name']. '</i>';
							$label['id'] = $label_id . '_' . $key;
							$general_settings[] = $label;

							++$i;

						}

					}

				}

			}
			else {

				foreach($labels as $label) {
					$general_settings[] = $label;
				}

			}

			$general_settings[] = array( 'type' => 'sectionend', 'id' => 'woo_bulk_variations');

			return $general_settings;

		}

		// get options for the product meta
		public static function get_product_meta_options() {

			$options = array(

				'position' => array(
					'after_title' => __( 'After Title', 'radykal' ),
					'after_short_desc' => __( 'After Short Description', 'radykal' ),
					'after_product_summary' => __( 'After Product Summary', 'radykal' ),
				),
				'layout' => array(
					'fit_in_row' => __( 'Fit In One Row (Attributes Head)', 'radykal' ),
					'fit_in_row_no_title' => __( 'Fit In One Row (Without Attributes Head)', 'radykal' ),
					'selects_one' => __( 'Selects: One Column', 'radykal' ),
					'selects_two' => __( 'Selects: Two Columns', 'radykal' ),
					'selects_three' => __( 'Selects: Three Columns', 'radykal' ),
					'selects_four' => __( 'Selects: Four Columns', 'radykal' ),
					'selects_five' => __( 'Selects: Five Columns', 'radykal' ),
					'selects_six' => __( 'Selects: Six Columns', 'radykal' ),
				),
				'enable_select2' => 'no',
				'replace_choose_option' => 'no',
				'fixed_amount' => 0,

			);

			if( class_exists('Fancy_Product_Designer') && version_compare(Fancy_Product_Designer::VERSION, '3.0.0', '>=') ) {
				$options['position']['before_fancy_product_designer'] = __( 'Before Fancy Product Designer', 'radykal' );
				$options['position']['after_fancy_product_designer'] = __( 'After Fancy Product Designer', 'radykal' );
			}

			return $options;

		}

	}

}

new WCBV_Admin_Settings();
?>