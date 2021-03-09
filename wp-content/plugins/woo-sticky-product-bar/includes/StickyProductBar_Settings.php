<?php

namespace OneTeamSoftware\Woocommerce\StickyProductBar;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class StickyProductBar_Settings extends \WC_Settings_Page
{

    /**
     * Constructor.
     */
    public function __construct($id)
    {
        $this->id = $id;
		$this->label = __('Sticky Product Bar', $this->id);
		add_filter('woocommerce_settings_tabs_array', array($this, 'add_settings_page'), 20);
        add_action('woocommerce_settings_' . $this->id, array($this, 'output'));
        add_action('woocommerce_settings_save_' . $this->id, array($this, 'save'));
    }

    /**
     * Get settings array
     *
     * @return array
     */
    public function get_settings()
    {

        return apply_filters('woocommerce_' . $this->id . '_settings', array(

            array('title' => __('Woocommerce Sticky Product Bar', $this->id), 'type' => 'title', 'desc' => '', 'id' => $this->id . '-title'),
            array(
                'title' => __('Enable', $this->id),
                'type' => 'checkbox',
                'id' => $this->id . '[enable]',
                'default' => 'no',
            ),
            array(
                'title' => __('Enable on Desktop', $this->id),
                'type' => 'checkbox',
                'id' => $this->id . '[enableDesktop]',
                'default' => 'yes',
            ),
            array(
                'title' => __('Enable on Mobile', $this->id),
                'type' => 'checkbox',
                'id' => $this->id . '[enableMobile]',
                'default' => 'yes',
            ),
            array(
                'title' => __('Enable for Products', $this->id),
                'type' => 'checkbox',
                'id' => $this->id . '[enableForProduct]',
                'default' => 'yes',
            ),
            array(
                'title' => __('Enable for Cart', $this->id),
                'type' => 'checkbox',
                'id' => $this->id . '[enableForCart]',
                'default' => 'yes',
            ),
            array(
                'title' => __('Enable for Checkout', $this->id),
                'type' => 'checkbox',
                'id' => $this->id . '[enableForCheckout]',
                'default' => 'yes',
            ),
            array(
                'title' => __('Enable for Out of Stock', $this->id),
                'type' => 'checkbox',
                'id' => $this->id . '[enableForOutOfStock]',
                'default' => 'no',
			),
            array(
                'title' => __('Always visible', $this->id),
                'desc' => __('It can be either always visible or when page is scrolled down', $this->id),
                'type' => 'checkbox',
                'id' => $this->id . '[alwaysVisible]',
                'default' => 'yes',
            ),
            array(
                'title' => __('RTL', $this->id),
                'desc' => __('Right to Left arrangement of the bar elements for RTL languages', $this->id),
                'type' => 'checkbox',
                'id' => $this->id . '[rtl]',
                'default' => is_rtl() ? 'yes' : 'no',
            ),
            array(
                'title' => __('Display product image', $this->id),
                'type' => 'checkbox',
                'id' => $this->id . '[displayImage]',
                'default' => 'yes',
            ),
            array(
                'title' => __('Display product name', $this->id),
                'type' => 'checkbox',
                'id' => $this->id . '[displayName]',
                'default' => 'yes',
            ),
            array(
                'title' => __('Display product rating', $this->id),
                'type' => 'checkbox',
                'id' => $this->id . '[displayRating]',
                'default' => 'yes',
            ),
            array(
                'title' => __('Display purchase quantity', $this->id),
                'type' => 'checkbox',
                'id' => $this->id . '[displayQuantity]',
                'default' => 'yes',
            ),
            array(
                'title' => __('Display product price', $this->id),
                'type' => 'checkbox',
                'id' => $this->id . '[displayPrice]',
                'default' => 'yes',
            ),
            array(
				'title' => __('Display product price range', $this->id),
				'desc' => __('Display (FROM - TO) price range for variable products', $this->id),
                'type' => 'checkbox',
                'id' => $this->id . '[displayPriceRange]',
                'default' => 'yes',
            ),
            array(
                'title' => __('Display cart and checkout total', $this->id),
                'type' => 'checkbox',
                'id' => $this->id . '[displayTotal]',
                'default' => 'yes',
            ),
            array(
                'title' => __('Display terms checkbox', $this->id),
                'type' => 'checkbox',
                'id' => $this->id . '[displayTerms]',
                'default' => 'yes',
            ),
            array(
                'title' => __('Display button', $this->id),
                'desc' => __('It displays "Add to cart", "Proceed to Checkout", "Pay" buttons or "Out of Stock" message', $this->id),
                'type' => 'checkbox',
                'id' => $this->id . '[displayButton]',
                'default' => 'yes',
            ),
            array(
                'title' => __('"Out of stock" text', $this->id),
                'id' => $this->id . '[textOutOfStock]',
                'type' => 'text',
                'default' => 'Out of stock',
                'desc_tip' => true,
            ),
            array(
                'title' => __('"Choose an option" text', $this->id),
                'id' => $this->id . '[textChooseAnOption]',
                'type' => 'text',
                'default' => 'Choose an option',
                'desc_tip' => true,
            ),
            array(
                'title' => __('Custom CSS', $this->id),
                'id' => $this->id . '[css]',
                'type' => 'textarea',
                'desc_tip' => true,
            ),

            array('type' => 'sectionend', 'id' => $this->id . '-sectionend'),

        )); // End pages settings
    }
}