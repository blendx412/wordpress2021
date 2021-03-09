<?php

namespace OneTeamSoftware\Woocommerce\StickyProductBar;

if (!defined('ABSPATH')) {
    exit;
}

class StickyProductBar
{
	private $id;
    private $pluginFilePath;
    private $templatePath;
	private $settings;

    /**
     * Class constructor.
     */
    public function __construct()
    {
		$this->id = 'wc-sticky-product-bar';
		$this->settingsOptionKey = 'woocommerce_' . $this->id .'_settings';
        $this->pluginFilePath = basename(realpath(__DIR__ . '/../'));
		$this->templatePath = realpath(dirname(__FILE__) . '/../') . '/templates/';
		$this->settings = array();

		$this->loadSettings();
    }

    /**
     * Loads settings.
     */
	private function loadSettings()
	{
		$this->settings = array_merge(
			array(
				'enable' => 'no', 
				'enableDesktop' => 'yes',
				'enableMobile' => 'yes',
				'enableForProduct' => 'yes',
				'enableForCart' => 'yes',
				'enableForCheckout' => 'yes',
				'enableForOutOfStock' => 'no',
				'alwaysVisible' => 'yes',
				'rtl' => is_rtl() ? 'yes' : 'no',
				'displayImage' => 'yes',
				'displayName' => 'yes',
				'displayRating' => 'yes',
				'displayQuantity' => 'yes',
				'displayPrice' => 'yes',
				'displayPriceRange' => 'yes',
				'displayTotal' => 'yes',
				'displayTerms' => 'yes',
				'displayButton' => 'yes',
			), 
			get_option($this->id, array())
		);
	}

    /**
     * Registers itself in the world.
     */
    public function register()
    {
        //Check if woocommerce plugin is installed.
        add_action('admin_notices', array($this, 'onAdminNotices'));

        //Add actionHead function to frontend
        add_action('wp_footer', array($this, 'onWpFooter'));

        //Add sticky bar tab to woocommerce settings
        add_filter('woocommerce_get_settings_pages', array($this, 'onGetSettingsPage'));

        //Add all js script and css to sticky bar
        add_action('wp_enqueue_scripts', array($this, 'onEnqueScripts'));

        //Add setting link for the admin settings
        add_filter("plugin_action_links_" . plugin_basename($this->pluginFilePath), array($this, 'onActionLinks'));
    }

    /**
     *
     * Helper to display template
     *
     */
    private function displayTemplate($fileName, $parameters = array())
    {
        $filePath = $this->id . '/' . $fileName;

        wc_get_template($filePath, $parameters, '', $this->templatePath);
    }

    /**
     *
     * Check if woocommerce is installed and activated and if not
     * activated then deactivate WooCommerce product sticky bar.
     *
     */
    public function onAdminNotices()
    {
        //Check if woocommerce is installed and activated
        if (!is_plugin_active('woocommerce/woocommerce.php')) {?>
		<div id="message" class="error">
			<p>WooCommerce Sticky Product Bar requires <a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a> to be activated in order to work. Please install and activate <a href="<?php echo admin_url('/plugin-install.php?tab=search&amp;type=term&amp;s=WooCommerce'); ?>" target="">WooCommerce</a> first.</p>
		</div>
		<?php deactivate_plugins('/wc-sticky-product-bar/wc-sticky-product-bar.php');
        }
    }

    /**
     * Add new admin setting page for woocommerce sticky add to cart settings.
     *
     * @param array $settings an array of existing setting pages.
     * @return array of setting pages along with sticky bar settings page.
     *
     */
    public function onGetSettingsPage($settings)
    {
		require_once __DIR__ . '/StickyProductBar_Settings.php';
		$settings[] = new StickyProductBar_Settings($this->id);

        return $settings;
    }

    /**
     * Displayed sticky bar when enabled
     */
    public function onWpFooter()
    {
		if (empty($this->settings)) {
			return;
		}

        $this->displayProductBar();
        $this->displayCartBar();
        $this->displayCheckoutBar();
    }

    /**
     *
     * Displays product bar
     *
     */
    private function displayProductBar()
    {
        if ($this->settings['enableForProduct'] != 'yes' || !is_product()) {
            return;
        }
        global $post;

        $product = wc_get_product($post->ID);
        if ($product->is_in_stock() || $this->settings['enableForOutOfStock'] == 'yes') {
            $this->displayTemplate('product-bar.php', array('id' => $this->id, 'product' => $product, 'options' => $this->settings));
        }
    }

    /**
     *
     * Displays cart bar
     *
     */
    private function displayCartBar()
    {
        if ($this->settings['enableForCart'] != 'yes' || !is_cart()) {
            return;
        }

        $cart = WC()->cart->get_cart();
        if (count($cart) > 0) {
            $this->displayTemplate('cart-bar.php', array('id' => $this->id, 'options' => $this->settings));
        }
    }

    /**
     *
     * Displays cart bar
     *
     */
    private function displayCheckoutBar()
    {
        if ($this->settings['enableForCheckout'] != 'yes' || is_order_received_page() || !is_checkout()) {
            return;
        }

        $this->displayTemplate('checkout-bar.php', array('id' => $this->id, 'options' => $this->settings));
    }

    /**
     *
     * Add necessary js and css files for sticky bar
     *
     */
    public function onEnqueScripts()
    {
        // Get all admin settings value
        if (empty($this->settings) || $this->settings['enable'] != 'yes') {
            return;
        }

        if (!is_product() && !is_cart() && !is_checkout()) {
            return;
        }

        $this->settings['id'] = $this->id;
        $this->settings['siteUrl'] = get_site_url();
        $this->settings['termsQuestions'] = __("Do you accept our terms and conditions?", $this->id);

        wp_enqueue_script('jquery-visible', '//cdnjs.cloudflare.com/ajax/libs/jquery-visible/1.2.0/jquery.visible.min.js', array('jquery'), false, true);
        //Load rateyo jstext_color
        wp_enqueue_script('rateyo-js', '//cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js', array('jquery'), false, true);
        //Load custom js
        wp_enqueue_script($this->id . '-js', plugins_url($this->pluginFilePath . '/assets/js/' . $this->id . '.js'), array('jquery'), false, true);
        //Load rateyo css
        wp_enqueue_style('jquery-reteyo-css', '//cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css', array());
        //Load custom css
		wp_enqueue_style($this->id . '-css', plugins_url($this->pluginFilePath . '/assets/css/bar.css'), array());
		//Load RTL css
		if ($this->settings['rtl'] == 'yes') {
	        wp_enqueue_style($this->id . '-rtl-css', plugins_url($this->pluginFilePath . '/assets/css/bar-rtl.css'), array());		
		}

        //Add inline css to custom css
        if (!empty($this->settings['css'])) {
            wp_add_inline_style($this->id . '-css', $this->settings['css']);
        }

        // pass options to our js file
        wp_localize_script($this->id . '-js', 'options', $this->settings);
    }

    /**
     * Add new link for the settings under plugin links
     *
     * @param array $links an array of existing links.
     * @return array of links  along with sticky bar settings link.
     *
     */
    public function onActionLinks($links)
    {
        $settings_link = '<a href="' . admin_url('admin.php?page=wc-settings&tab=wc-sticky-product-bar') . '">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
}

(new StickyProductBar())->register();