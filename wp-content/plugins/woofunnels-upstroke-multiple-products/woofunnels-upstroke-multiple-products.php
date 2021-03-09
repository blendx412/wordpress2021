<?php
/*
* Plugin Name: UpStroke: Multi Product Offers
* Plugin URI: https://buildwoofunnels.com/upstroke
* Description: This UpStroke addon allows you to add multiple products to the offer page. You can create grids or lists of products on offer pages. When you activate on your offer screen will show "add product" button with which you can select multiple products.
* Version: 1.2.0
* Author: WooFunnels
* Author URI: https://buildwoofunnels.com
* License: GPLv3 or later
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
* Text Domain: woofunnels-upstroke-multiple-products
* Domain Path: /languages/
*
 * Requires at least: 4.9.0
 * Tested up to: 5.2.2
 * WC requires at least: 3.3.1
 * WC tested up to: 3.6
 * WooFunnels: true
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! function_exists( 'wfocu_multiple_products_dependency' ) ) {
	/**
	 * Function to check if upstroke are enabled and activated or not?
	 * @return bool True|False
	 */
	function wfocu_multiple_products_dependency() {

		$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		if ( false === file_exists( plugin_dir_path( __DIR__ ) . 'woofunnels-upstroke-one-click-upsell/woofunnels-upstroke-one-click-upsell.php' ) ) {
			return false;
		}

		return ( in_array( 'woofunnels-upstroke-one-click-upsell/woofunnels-upstroke-one-click-upsell.php', $active_plugins ) || array_key_exists( 'woofunnels-upstroke-one-click-upsell/woofunnels-upstroke-one-click-upsell.php', $active_plugins ) );
	}
}


/**
 * Class WFOCU_MultiProduct
 */
final class WFOCU_MultiProduct {
	private static $_instance = null;
	/**
	 * @var WFOCU_MultiProductCore
	 */
	public $template;

	/**
	 * WFOCU_MultiProduct constructor.
	 */
	public function __construct() {
		/**
		 * Load important variables and constants
		 */
		$this->define_plugin_properties();
		$this->include_files();

		add_filter( 'wfocu_assets_scripts', array( $this, 'mp_assets_scripts' ), 10, 1 );

		//Adding license check
		add_action( 'plugins_loaded', array( $this, 'wfocu_add_woofunnels_upstroke_multiple_product_licence_support_file' ) );

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'plugins_loaded', array( $this, 'maybe_check_version' ) );
	}

	/**
	 * @return null|WFOCU_MultiProduct
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}

	/**
	 *  Deifining plugins constants
	 */
	public function define_plugin_properties() {
		define( 'WFOCU_MP_VERSION', '1.2.0' );
		define( 'WFOCU_MIN_MP_VERSION', '2.0.0' );
		define( 'WFOCU_MP_TEXTDOMAIN', 'woofunnels-upstroke-multiple-products' );
		define( 'WFOCU_MP_FULL_NAME', 'Upstroke: Multi Product Offers' );
		define( 'WFOCU_MP_PLUGIN_FILE', __FILE__ );
		define( 'WFOCU_MP_PLUGIN_DIR', __DIR__ );
		define( 'WFOCU_MP_TEMPLATE_DIR', plugin_dir_path( WFOCU_MP_PLUGIN_FILE ) . 'templates' );
		define( 'WFOCU_MP_PLUGIN_URL', untrailingslashit( plugin_dir_url( WFOCU_MP_PLUGIN_FILE ) ) );
		define( 'WFOCU_MP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		define( 'WFOCU_MP_ADMIN_ASSETS_URL', WFOCU_MP_PLUGIN_URL . '/admin/assets' );
		define( 'WFOCU_MP_PURCHASE', 'xlplugin' );
		define( 'WFOCU_MP_IS_DEV', true );
	}

	/**
	 *  Including files
	 */
	public function include_files() {

		include_once WFOCU_MP_PLUGIN_DIR . '/includes/class-wfocu-mp.php';
		$this->template = WFOCU_MultiProductCore::get_instance();
	}

	/**
	 * @param $script_array
	 *
	 * @return mixed
	 */
	public function mp_assets_scripts( $script_array ) {
		$script_array['mp-customizer'] = array(
			'path'      => plugin_dir_url( WFOCU_MP_PLUGIN_FILE ) . 'assets/js/customizer.js',
			'version'   => null,
			'in_footer' => true,
			'supports'  => array(
				'customizer',
				'customizer-preview',
			),
		);

		return $script_array;
	}

	/**
	 * Adding license support file
	 */
	public function wfocu_add_woofunnels_upstroke_multiple_product_licence_support_file() {
		include_once plugin_dir_path( __FILE__ ) . 'class-woofunnels-upstroke-multiple-product-support.php';
	}

	public function load_textdomain() {

		$locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();

		unload_textdomain( 'woofunnels-upstroke-multiple-products' );
		load_textdomain( 'woofunnels-upstroke-multiple-products', WP_LANG_DIR . '/woofunnels-upstroke-multiple-products-' . $locale . '.mo' );

		load_plugin_textdomain( 'woofunnels-upstroke-multiple-products', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

	}

	public function wfocu_version_check_notice() {
		?>
        <div class="error">
            <p>
				<?php
				/* translators: %1$s: Min required upstroke version */
				printf( __( '<strong> Attention: </strong>UpStroke: Multi Product Offers  needs WooFunnels UpStroke: One Click Upsell version %1$s or greater. Kindly update the WooFunnels UpStroke: One Click Upsell plugin to contniue using Multi Product Offers plugin.', 'woofunnels-upstroke-dynamic-shipping' ), WFOCU_MIN_MP_VERSION );
				?>
            </p>
        </div>
		<?php
	}

	public function maybe_check_version() {
		if ( ! version_compare( WFOCU_VERSION, WFOCU_MIN_MP_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'wfocu_version_check_notice' ) );

			return false;
		}
	}


}

/**
 * Intializing plugin if Woofunnels is enabled and activated.
 */
if ( wfocu_multiple_products_dependency() ) {
	add_action( 'wfocu_loaded', 'wfocu_multi_product' );
} else {
	add_action( 'admin_notices', 'wfocu_upstroke_not_installed_notice' );
}

/**
 * Initializing this addon
 */
function wfocu_multi_product() {
	if ( defined( 'WFOCU_VERSION' ) && version_compare( WFOCU_VERSION, '1.7.2', '<' ) ) {
		add_action( 'admin_notices', 'wfocu_upstroke_version_not_supported_notice' );
	} else {
		return WFOCU_MultiProduct::get_instance();
	}
}

/**
 * Adding notice for inactive state of UpStroke
 */
function wfocu_upstroke_not_installed_notice() { ?>
    <div class="wfocu-notice notice notice-error">
        <p>
			<?php
			echo __( '<strong> Attention: </strong>"UpStroke: WooCommerce One Click Upsells" plugins is not installed or activated. Upstroke: Multi Product Offers wouldn\'t work.', 'woofunnels-upstroke-multiple-products' );
			?>
        </p>
    </div>
	<?php
}

/**
 * Adding notice for low version of UpStroke
 */
function wfocu_upstroke_version_not_supported_notice() {
	?>
    <div class="wfocu-notice notice notice-error">
        <p>
			<?php
			echo __( '<strong> Attention: </strong>To work "Upstroke: Multi Product Offers", "UpStroke: WooCommerce One Click Upsells" version should not be lower than 1.7.2', 'woofunnels-upstroke-multiple-products' );
			?>
        </p>
    </div>
	<?php
}
