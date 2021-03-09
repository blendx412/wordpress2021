<?php

/**
 * Common class.
 *
 * Holds the config about what fields are available.
 */
class WoocommerceWpwoofCommon {

	private $settings = array();
	private $category_cache = array();
	public $product_fields = array();
    public $fields_organize = array(
        'general' => array(
			'site_name',
			'id',
			'wpfoof-mpn-name',
			'wpfoof-gtin-name',
            'item_group_id',
            'title',
            'description',
            'link',
            'availability',
            'product_type',
			'is_bundle'
        ),
        'price' => array(
            'price',
            'sale_price',
            'sale_price_effective_date',
        ),
        'shipping' => array(
            //'shipping',
            //'shipping_weight'
        ),
        'additional_data' => array(
            'brand',
			'mpn'
        ),
        'additional_images' => array(
            'image_link',
            'product_image',
			'wpfoof-carusel-box-media-name',
			'wpfoof-box-media-name',
            'additional_image_link_1',
            'additional_image_link_2',
            'additional_image_link_3',
            'additional_image_link_4',
            'additional_image_link_5',
            'additional_image_link_6',
            'additional_image_link_7',
            'additional_image_link_8',
            'additional_image_link_9',
            'additional_image_link_10'
        ),
    );
	
	static function isActivatedWMPL(){
		return  ( !function_exists('pll_the_languages')  && function_exists('icl_get_languages'));
	}
	
    public $fields_organize_name = array(
        'general' => 'Products',
        'price' => 'Price',
        'shipping' => 'Shipping',
        'additional_data' => 'Additional',
        'additional_images' => 'Product images',
        'custom_label' => 'Custom labels',
    );

	private function _addfieldImages($key){
		$tmpData = array();
		foreach($this->fields_organize['additional_images'] as $el){
			array_push($tmpData,$el);
			if($el=='product_image') array_push($tmpData,$key);
		}
		$this->fields_organize['additional_images']=$tmpData;
		if(!defined('PCFP_WP')) define('PCFP_WP',true);
	}
	
	public function getPicturesFields(){
		return $this->fields_organize['additional_images'];
	}

	public function check_plugins(){
		if ( defined( 'MASHSB_VERSION' ) ) {
			$data = get_option('mashsb_settings');

			if($data && isset($data['post_types']) && isset($data['post_types']['product'])) {
				$this->_addfieldImages('mashshare_product_image');
			}
		}
		if ( defined( 'WPSEO_VERSION' ) )  {$this->_addfieldImages('yoast_seo_product_image');}
	}

	/**
	 * Constructor - set up the available product fields
	 *
	 * @access public
	 */
	function __construct() {

		add_action( 'plugins_loaded', array($this,'check_plugins'));

		$this->product_fields = array(
			'id' => array(
				'label' 		=> __('ID', 'woocommerce_wpwoof'),
				'desc'			=> __( 'The Product ID. If there are multiple instances of the same ID, all of those entries will be ignored.', 'woocommerce_wpwoof' ) ,
				'value'			=> false,
				'required'		=> true,
				'needcheck'		=> true,
				'feed_type'		=> array('facebook','all','google','adsensecustom'),
				'facebook_len'	=> 100,
				'woocommerce_default' =>array('label' => 'ID', 'value' => 'id'),
			),


			'id2' => array(
				'label' 		=> __('ID2', 'woocommerce_wpwoof'),
				'desc'			=> __( 'matches the tag custom parameter dynx_itemid2 Any sequence of letters and digits. ID sequence (for example ID + ID2, or just ID) must be unique.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> true,
				'feed_type'		=> array('adsensecustom'),
				'facebook_len'	=> 100

			),

			'item title' => array(
				'label' 		=> __('Title', 'woocommerce_wpwoof'),
				'desc'			=> __( 'The title of the product.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'callback'		=> 'wpwoof_render_title',
				'required'		=> true,
				'needcheck'		=> true,
				'feed_type'		=> array('adsensecustom'),
				'facebook_len'	=> 25,
				'delimiter'     => true,
				'woocommerce_default' =>array('label' => 'Title', 'value'=>'title'),
				'additional_options'  => array('uc_every_first' => '')
			),
			'item subtitle' =>  array(
				'label' 		=> __('Subtitle', 'woocommerce_wpwoof'),
				'desc'			=> __( 'The subtitle of the product.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> true,
				'callback'		=> 'wpwoof_render_title',
				'feed_type'		=> array('adsensecustom'),
				'facebook_len'	=> 25,
				'delimiter'     => true,
				'additional_options'  => array('uc_every_first' => '')
			),

			'item description'  => array(
				'label' 		=> __('Description', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Description of the product <b>(highly recommended)</b> (max 25 chars).', 'woocommerce_wpwoof' ),
				'required'		=> true,
				'value'			=> false,
				'callback'		=> 'wpwoof_render_description',
				'feed_type'		=> array('adsensecustom'),
				'facebook_len'	=> 25,
				'woocommerce_default' =>array('label' => 'Description', 'value' => 'description'),
                'additional_options'  => array('uc_every_first' => '')
			),

			'final URL' => array(
				'label' 		=> __('Link', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Link to the merchant’s site where you can buy the item.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> true,
				'needcheck'		=> true,
				'feed_type'		=> array('adsensecustom'),
				'facebook_len'	=> false,
				'woocommerce_default' =>array('label' => 'Link', 'value' => 'link'),
		    ),

			'image URL' =>  array(
					'label' 		=> __('Featured image', 'woocommerce_wpwoof'),
					'desc'			=> __( 'Link to an image of the item. This is the image used in the feed.', 'woocommerce_wpwoof' ),
					'value'			=> false,
					'required'		=> true,
					'needcheck'		=> true,
					'feed_type'		=> array('adsensecustom'),
					'facebook_len'	=> false,
					'woocommerce_default' =>array('label' => 'Featured image', 'value'=>'image_link'),
					'callback'		=> 'wpwoof_render_image'
		     ),




			'item category' => array(
				'label' 		=> __('Item Category', 'woocommerce_wpwoof'),
				'desc'			=> __( 'The retailer-defined category of the product as a string.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> true,
				'feed_type'		=> array('adsensecustom'),
				'facebook_len'	=> 750,
				'woocommerce_default' =>array('label' => 'Woo Prod Categories', 'value'=>'product_type')
			),


			'contextual keywords' => array(
				'label' 		=> __('Contextual keywords', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Use semicolons to separate multiple keywords.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> true,
				'feed_type'		=> array('adsensecustom'),
				'facebook_len'	=> false,
				'woocommerce_default' =>array('label' => 'Product tags', 'value'=>'tags'),


			),

			'item address'  => array(
				'label' 		=> __('Item address', 'woocommerce_wpwoof'),				
				'value'			=> false,
				'feed_type'		=> array('adsensecustom'),
				'facebook_len'	=> false,
				'define' => array(
					'label' => 'Address Options:',
					'desc'	=> 'Use one of these formatting methods:<ul><li>- City, state code, country</li><li>- Full address with zip code</li><li>- Latitude-longitude in the DDD format</li><br />Use commas to separate your address. See specs for cities, regions, and countries',
					'value' => '',
					'type' => 'textarea'
				)

			),

			'tracking template'   => array(
				'label' 		=> __(' Tracking template', 'woocommerce_wpwoof'),
				'value'			=> false,
				'feed_type'		=> array('adsensecustom'),
				'facebook_len'	=> false,
				'define' => array(
					'label' => 'Tracking template:',
					'desc'	=> 'Include any ValueTrack parameters, custom parameters, or tracking redirects for your item URL.',
					'value' => '',
					'type' => 'textarea'
				)

			),


			'item_group_id' => array(
				'label' 		=> __('Group ID', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Is this item a variant of a product? If so, all of the items in a group should share an item_group_id.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> true,
				'needcheck'		=> true,
				'feed_type'		=> array('facebook','google'),
				'facebook_len'	=> false,
				'woocommerce_default' =>array('label' => 'Group ID', 'value' => 'item_group_id'),
			),

			'color' => array(/* For Google Feed */
				'label' 		=> __('Color', 'woocommerce_wpwoof'),
				'desc'			=> __( ' Required for all products in an item group that vary by color. Required for all apparel items in feeds that target Brazil, France, Germany, Japan, the UK, and the US. Recommended for all products for which color is an important, distinguishing attribute.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> 100,
				/*'woocommerce_default' =>array('label' => 'Color', 'value'=>'')*/
			),
			'gender' => array(/* For Google Feed */
				'label' 		=> __('Gender', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Required for all products in an item group that vary by gender. Required for all apparel items in feeds that target Brazil, France, Germany, Japan, the UK, and the US. Recommended for all products for which gender is an important, distinguishing attribute.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> 100,
				/*'woocommerce_default' =>array('label' => 'Gender', 'value'=>'gender')*/
			),
			'age_group' => array(/* For Google Feed */
				'label' 		=> __('Age Group', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Required for all products in an item group that vary by gender. Required for all apparel items in feeds that target Brazil, France, Germany, Japan, the UK, and the US. Recommended for all products for which gender is an important, distinguishing attribute.', 'woocommerce_wpwoof' ),
				'value'			=> "newborn,infant,toddler,kids,adult",
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> false,
				/*'woocommerce_default' =>array('label' => 'Age Group', 'value'=>'age_group')*/
			),
			'material' => array(/* For Google Feed */
				'label' 		=> __('Material', 'woocommerce_wpwoof'),
				'desc'			=> __('Required for all products in an item group that vary by material. Recommended for all products for which material is an important, distinguishing attribute.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> 200,
				/*'woocommerce_default' =>array('label' => 'Material', 'value'=>'material')*/
			),
			'pattern' => array(/* For Google Feed */
				'label' 		=> __('Pattern', 'woocommerce_wpwoof'),
				'desc'			=> __('Required for all products in an item group that vary by pattern. Recommended for all products for which pattern is an important, distinguishing attribute.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> 100,
				/*'woocommerce_default' =>array('label' => 'Pattern', 'value'=>'pattern')*/
			),
			'size' => array(/* For Google Feed */
				'label' 		=> __('Size', 'woocommerce_wpwoof'),
				'desc'			=> __('Required for all products in an item group that vary by size. Required for all apparel items in the \'Apparel & Accessories > Clothing\' and \'Apparel & Accessories > Shoes\' product categories in product data that targets Brazil, France, Germany, Japan, the UK, and the US. Recommended for all products for which size is an important, distinguishing attribute.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> 100,
				/*'woocommerce_default' =>array('label' => 'Size', 'value'=>'size')*/
			),
			'size_type' => array(/* For Google Feed */
				'label' 		=> __('Size Type', 'woocommerce_wpwoof'),
				'desc'			=> __("Recommended. Only 5 values are accepted: 'regular', 'petite', 'plus', 'big and tall', 'maternity'", 'woocommerce_wpwoof' ),
				'value'			=> "regular,petite,plus,big and tall,maternity",
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> false,
				/*'woocommerce_default' =>array('label' => 'Size Type', 'value'=>'size_type')*/
			),
			'size_system' => array(/* For Google Feed */
				'label' 		=> __('Size System', 'woocommerce_wpwoof'),
				'desc'			=> __('Recommended. There are 11 accepted values: US, UK, EU, DE, FR, JP, CN (China), IT, BR, MEX, AU', 'woocommerce_wpwoof' ),
				'value'			=> "US,UK,EU,DE,FR,JP,CN,IT,BR,MEX,AU",
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> false,
				/* 'woocommerce_default' =>array('label' => 'Size System', 'value'=>'size_system') */
			),


			'title' => array(
				'label' 		=> __('Title', 'woocommerce_wpwoof'),
				'desc'			=> __( 'The title of the product.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'callback'		=> 'wpwoof_render_title',
				'required'		=> true,
				'needcheck'		=> true,
				'feed_type'		=> array('facebook','google'),
				'facebook_len'	=> 150,
                'delimiter'     => true,
				'woocommerce_default' =>array('label' => 'Title', 'value'=>'title'),
                'additional_options'  => array('uc_every_first' => '')
			),


			'product_type' => array(
				'label' 		=> __('Product Type', 'woocommerce_wpwoof'),
				'desc'			=> __( 'The retailer-defined category of the product as a string.', 'woocommerce_wpwoof' ),
				'value'			=> true,
				'required'		=> true,
				'needcheck'		=> true,
				'feed_type'		=> array('facebook','google'),
				'facebook_len'	=> 750,
				'woocommerce_default' =>array('label' => 'Woo Prod Categories', 'value'=>'product_type')
				
			),
			'description' => array(
				'label' 		=> __('Description', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Description of the product.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'callback'		=> 'wpwoof_render_description',
				'required'		=> true,
				'needcheck'		=> true,
				'feed_type'		=> array('facebook','google'),
				'facebook_len'	=> 5000,
				'text'			=> true,
				'woocommerce_default' =>array('label' => 'Description', 'value' => 'description'),
                'additional_options'  => array('uc_every_first' => '')
			),

			'link' => array(
				'label' 		=> __('Link', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Link to the merchant’s site where you can buy the item.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> true,
				'needcheck'		=> true,
				'feed_type'		=> array('facebook','google'),
				'facebook_len'	=> false,
				'woocommerce_default' =>array('label' => 'Link', 'value' => 'link'),
			),

			'mobile_link' => array( /* For Google Feed */
				'label' 		=> __('Mobile Link', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Recommended if you have mobile-optimized versions of your landing pages.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> true,
				'needcheck'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> 2000,
				/*'woocommerce_default' =>array('label' => 'Mobile link', 'value'=>'mobile_link'),*/
			),

			'image_link' => array(
				'label' 		=> __('Featured image', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Link to an image of the item. This is the image used in the feed.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> true,
				'needcheck'		=> true,
				'feed_type'		=> array('facebook','google'),
				'facebook_len'	=> false,
				'woocommerce_default' =>array('label' => 'Featured image', 'value'=>'image_link'),
				'callback'		=> 'wpwoof_render_image'
			),
			'price' => array(
				'header'		=> __('Price and Tax', 'woocommerce_wpwoof'),
				'headerdesc'    => __('Tax should be included for all countries except US, Canada and India. If you choose to include or exclude tax your price and sale price values will be recalculated for the feed based on your woocommerce settings.', 'woocommerce_wpwoof'),
				'delimiter'     => true,
				'label' 		=> __('Price', 'woocommerce_wpwoof'),
				'desc'			=> __( 'The cost of the product and currency', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> true,
				'needcheck'		=> true,
				'feed_type'		=> array('facebook','all','google','adsensecustom'),
				'facebook_len'	=> false,
				'woocommerce_default' =>array('label' => 'Price', 'value'=>'price'),
			),
			'sale price' => array(
				'label' 		=> __('Sale Price', 'woocommerce_wpwoof'),
				'desc'			=> __( 'The discounted price if the item is on sale.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> true,
				'needcheck'		=> false,
				'feed_type'		=> array('adsensecustom'),
				'facebook_len'	=> false,
				'woocommerce_default' =>array('label' => 'Sale Price', 'value' => 'sale_price'),
			),

			'sale_price' => array(
				'label' 		=> __('Sale Price', 'woocommerce_wpwoof'),
				'desc'			=> __( 'The discounted price if the item is on sale.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> true,
				'needcheck'		=> false,
				'feed_type'		=> array('facebook','google'),
				'facebook_len'	=> false,
				'woocommerce_default' =>array('label' => 'Sale Price', 'value' => 'sale_price'),
			),

			'sale_price_effective_date' => array(
				'label' 		=> __('Sale Price Effective Date', 'woocommerce_wpwoof'),
				'desc'			=> __( 'The start and end date/time of the sale, separated by slash.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> true,
				'needcheck'		=> false,
				'feed_type'		=> array('facebook','all','google'),
				'facebook_len'	=> false,
				'woocommerce_default' =>array('label' => 'Sale Price Effective Date', 'value'=>'sale_price_effective_date'),
			),


			
			'tax' => array(
				'label' 		=> __('Tax', 'woocommerce_wpwoof'),
				'desc'			=>  __( 'For the US, don\'t include tax in the price. For Canada and India, do not include any value added tax in the price. For all other countries, value added tax (VAT) has to be included in the price.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'attr' 			=> array("id"=>"ID_tax_field","onchange"=>"showHideCountries(this.value);"),
				'required'		=> true,
				'needcheck'		=> false,
				'feed_type'		=> array('google','adsensecustom','facebook'),
				'facebook_len'	=> false,
				'custom'        => array("Include tax in price"=>'true',"Exclude tax from price"=>'false'),
				'second_field'  => 'tax_countries'/* ( (get_option("woocommerce_tax_based_on")!='base' 
				                       || get_option("woocommerce_tax_based_on")=='base' 
										   && 
										  get_option("woocommerce_prices_include_tax")=="no" 
									  ) ? 'tax_countries' : false )*/
				
			),
			'tax_countries' => array(
				'dependet'      => 'tax',
				'label' 		=> __('apply tax for', 'woocommerce_wpwoof'),
				'value'			=> false,			
				'required'		=> true,
				'needcheck'		=> false,
				'feed_type'		=> array('google','adsensecustom','facebook'),
				'facebook_len'	=> false,
				'custom'        => $this->getTaxRateCountries(),
				'rendervalues'  => 'buidCountryValues',
				'attr'			=>	array("class"=>"CSS_tax_countries"),
				'desc'			=> __( 'Select a feed target country.', 'woocommerce_wpwoof' )
			),
			'remove_currency' => array(
				'label' 		=> __('Remove Currency', 'woocommerce_wpwoof'),
				'value'			=> false,
				'required'		=> true,
				'feed_type'		=> array('all'),
				'facebook_len'	=> false,
				'type'=>'checkbox'
			),
			'availability' => array(
				'label' 		=> __('Availability', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Whether or not the item is in stock.', 'woocommerce_wpwoof' ),
				'value'			=> 'in stock,out of stock,preorder,available for order',
				'required'		=> true,
				'delimiter'     => true,
				'needcheck'		=> true,
				'feed_type'		=> array('facebook','google'),
				'facebook_len'	=> false,
				'woocommerce_default' =>array('label' => 'Availability', 'value' => 'availability'),
			),
			'availability_date' => array(/* For Google Feed */
				'label' 		=> __('Availability Date', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Recommended for items with the ‘preorder’ value for the ‘availability’ attribute.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> true,
				'needcheck'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> false				
			),

			'is_bundle' => array( /* For Google Feed */
				'label' 		=> __('Is Bundle', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Merchant-defined bundles are custom groupings of different products defined by a merchant and sold together for a single price. A bundle features a main item sold with various accessories or add-ons, such as a camera combined with a bag and a lens.', 'woocommerce_wpwoof' ),
				'value'			=> 'true,false',
				'required'		=> true,
				'needcheck'		=> false,
				'feed_type'		=> array('google'),
				'delimiter'     => true,
				'facebook_len'	=> false,
				'woocommerce_default' =>array('label' => 'Is Bundle', 'value' => 'is_bundle'),
			),
			'adult' => array( /* For Google Feed */
				'label' 		=> __('Is Adult', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Global attribute. Select "True" if your website generally targets an adult audience and contains adult-oriented content.', 'woocommerce_wpwoof' ),
				'value'			=> 'true,false',
				'required'		=> true,
				'needcheck'		=> false,
				'feed_type'		=> array('google'),
				'delimiter'     => true,
				'facebook_len'	=> false,
				'custom'        => array("False"=>"","True"=>"true"),

			),
			'unit_pricing_measure' => array( /* For Google Feed */
				'label' 		=> __('Unit Pricing Measure', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Optional, but you may be required to provide this information based on local laws or regulations. Strongly recommended that you submit unit pricing for applicable products. For example, applicable products could fall under the Hardware, Office Supplies, Food, Beverages, and Tobacco categories, including Flooring, Business Cards, Perfume, or Beverages.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> false,
				/*'woocommerce_default' =>array('label' => 'Unit Pricing Measure', 'value'=>'unit_pricing_measure'),*/
				'help'=>" Numerical value + unit. Accepted units: Weight: oz, lb, mg, g, kg; Volume US imperial: floz, pt, qt, gal; Volume metric: ml, cl, l, cbm; Length: in, ft, yd, cm, m; Area: sqft, sqm; Per unit: ct"
			),
			'unit_pricing_base_measure' => array( /* For Google Feed */
				'label' 		=> __('Unit Pricing Base Measure', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Recommended if you also submit the \'unit pricing measure\' attribute.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> false,
				/*'woocommerce_default' =>array('label' => 'Unit Pricing Base Measure', 'value'=>'unit_pricing_base_measure')*/
			),
			'energy_efficiency_class' => array( /* For Google Feed */
				'label' 		=> __('Energy Efficiency Class', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Recommended if applicable for items in feeds targeting an EU country or Switzerland. Enumeration: G, F, E, D, C, B, A, A+, A++, A+++', 'woocommerce_wpwoof' ),
				'value'			=> "G,F,E,D,C,B,A,A+,A++,A+++",
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> false,
				/*'woocommerce_default' =>array('label' => 'Energy Efficiency Class', 'value'=>'energy_efficiency_class')*/
			),
			'promotion_id' => array( /* For Google Feed */
				'label' 		=> __('Promotion ID', 'woocommerce_wpwoof'),
				'desc'			=> __( 'The unique ID of a promotion. For online promotions that apply to specific products, the \'promotion id\' in your promotions feed should match this attribute in your products feed so Google knows which products belong to this promotion.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> 60,
				/*'woocommerce_default' =>array('label' => 'Promotion ID', 'value'=>'promotion_id')*/
			),

			'loyalty_points' => array( /* For Google Feed */
				'label' 		=> __('Loyalty points', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Recommended for items targeting Japan. It lets you specify how many and what type of loyalty points the customer receives when buying a product.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> 24,
				/*'woocommerce_default' =>array('label' => 'Loyalty points', 'value'=>'loyalty_points')*/
			),
            'installment' => array( /* For Google Feed */
				'label' 		=> __('Installment', 'woocommerce_wpwoof'),
				'desc'			=> __( 'For items submitted to Brazil that can also be paid in multiple installments.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> 6,
				/*'woocommerce_default' =>array('label' => 'Installment', 'value'=>'installment')*/
			),

			'custom parameter'  => array(
				'label' 		=> __(' Custom parameter', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Include up to 3 key:value pairs, which are automatically filled up in the click URL. Neither one can exceed 16 characters or 200 bytes. Use semicolons to separate key:value pairs.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'feed_type'		=> array('adsensecustom'),
				'facebook_len'	=> false,
		   ),

		  'destination URL' =>	array(
			'label' 		=> __('Destination URL', 'woocommerce_wpwoof'),
			'desc'			=> __( 'Same domain as your website. Begins with "http://" or "https://"', 'woocommerce_wpwoof' ),
			'value'			=> false,
			'feed_type'		=> array('adsensecustom'),
			'facebook_len'	=> false
		  ),

			'final_mobile_url ' => array( /* For Google Feed */
				'label' 		=> __('Mobile Link', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Recommended if you have mobile-optimized versions of your landing pages.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'feed_type'		=> array('adsensecustom')
			),


			'adwords_redirect' => array( /* For Google Feed */
				'label' 		=> __('Adwords Redirect', 'woocommerce_wpwoof'),
				'desc'			=> __( 'This attribute can be used in Shopping campaigns. Learn more about the \'adwords redirect\' attribute.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> 2000,
				/*'woocommerce_default' =>array('label' => 'Adwords Redirect', 'value' => 'adwords_redirect'),*/
			),


			'gtin' => array(
				'label' 		=> __('GTIN', 'woocommerce_wpwoof'),
				'desc'			=> __( 'The Global Trade Item Number (GTINs) can include UPC, EAN, JAN and ISBN.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> true,
				'needcheck'		=> true,
				'feed_type'		=> array('facebook','google'),
				'facebook_len'	=> 100,
				'delimiter'     => true,
				'woocommerce_default' => array('label' => 'GTIN', 'value' => 'wpfoof-gtin-name'),
				/*'callback'		=> 'wpwoof_render_gtin'*/
			),

			'mpn' => array(
				'label' 		=> __('MPN', 'woocommerce_wpwoof'),
				'desc'			=> __( 'The number which uniquely identifies the product to its manufacturer.', 'woocommerce_wpwoof' ),
				'value'			=> true,
				'required'		=> true,
				'needcheck'		=> true,
				'attr'			=> array('id'=>"ID_mpn_field"),
				'feed_type'		=> array('facebook','google'),
				'facebook_len'	=> 100,
				'woocommerce_default' => array('label' => 'ID', 'value' => 'id'),
				/*'callback'		=> 'wpwoof_render_gtin'*/

			),

			'identifier_exists' => array(
				'label' 		=> __('identifier exists', 'woocommerce_wpwoof'),
				'desc'			=> __('If your product doesn\'t have both a GTIN and MPN set identifier_​exists to "No"'),
				'value'			=> "true,false",
				'required'		=> true,
				'needcheck'		=> false,
				'feed_type'		=> array('google'),
				'custom'        => array("Yes"=>'true',"No"=>'false'),
				'facebook_len'	=> false
			),
			'product_image' => array(
				'dependet'		=> true,
				'label' 		=> __('Product image', 'woocommerce_wpwoof'),
				'desc'			=> __( 'More images. You can include up to 10 additional images. If supplying multiple images, send them as comma separated URLs.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('facebook','google'),
				'facebook_len'	=> false,
				'count' 		=> 10,
				'text'			=> true,
				'woocommerce_default' =>array('label' => 'Product image', 'value'=>'product_image'),
			),

			'brand' => array(
				'label' 		=> __('Brand', 'woocommerce_wpwoof'),
				'desc'			=> __( 'The name of the brand.', 'woocommerce_wpwoof' ),
				'required'		=> true,
				'needcheck'		=> true,
				'feed_type'		=> array('facebook','google'),
				'facebook_len'	=> 100,
				'define' => array(
						'label' => 'Brand Options:',
						'desc'	=> 'Brand is a requered field. You can use the brand from your WooCommerce products and define a value for when brand is missing, or you can set a global value that will be used for all the products in this feed',
						'value' => '',
						),
				'woocommerce_default' =>array('label' => 'Site Name', 'value'=>'site_name'),
			),

			

			'condition' => array(
				'label' 		=> __('Condition', 'woocommerce_wpwoof'),
				'desc'			=> __( 'The condition of the product.', 'woocommerce_wpwoof' ),
                'value'			=> false,
				'required'		=> true,
				'needcheck'		=> true,
				'feed_type'		=> array('facebook','google'),
				'facebook_len'	=> false,
				'define' => array(
						'label' => 'Define Condition',
						'desc'	=> 'Condition is a required field and you have a few options. You can use the conditions from your WooCommerce products and define a value for when condition is missing (it will be used just for products that don\'t have condition), or you can set a global value that will be used for all the products in this feed',
						'values' => 'new,refurbished,used',
						),
			),

		    'excluded_destination' => array(/* For Google Feed */
				'label' 		=> __('Excluded Destination', 'woocommerce_wpwoof'),
				'desc'			=> __( 'You want to exclude the item from a destination.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> false,
				/*'woocommerce_default' =>array('label' => 'Excluded Destination', 'value'=>'excluded_destination'),*/
			),
			'expiration_date' => array(/* For Google Feed */
				'label' 		=> __('Expiration Date', 'woocommerce_wpwoof'),
				'desc'			=> __( '"This is the date that an item listing will expire. If you do not provide this attribute, items will expire and no longer appear in Google Shopping results after 30 days. You cannot use this attribute to extend the expiration period to longer than 30 days. When to include: If you would like an item to expire earlier than 30 days from the upload date of the feed."', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> 10,
				/*'woocommerce_default' =>array('label' => 'Expiration Date', 'value'=>'expiration_date'),*/
			),

			'additional_image_link_1' => array(
				'label' 		=> __('Additional Image Link 1', 'woocommerce_wpwoof'),
				'desc'			=> '',
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google','facebook'),
				'facebook_len'	=> 2000,
				'count' 		=> 10,
				'text'			=> true,
				'woocommerce_default' =>array('label' => 'Additional Image Link 1', 'value'=>'additional_image_link_1'), 
			),

			'additional_image_link_2' => array(
				'label' 		=> __('Additional Image Link 2', 'woocommerce_wpwoof'),
				'desc'			=> '',
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google','facebook'),
				'facebook_len'	=> 2000,
				'count' 		=> 10,
				'text'			=> true,
				'woocommerce_default' =>array('label' => 'Additional Image Link 2', 'value'=>'additional_image_link_2'), 
			),

			'additional_image_link_3' => array(
				'label' 		=> __('Additional Image Link 3', 'woocommerce_wpwoof'),
				'desc'			=> '',
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google','facebook'),
				'facebook_len'	=> 2000,
				'count' 		=> 10,
				'text'			=> true,
				'woocommerce_default' =>array('label' => 'Additional Image Link 3', 'value'=>'additional_image_link_3'), 
			),

			'additional_image_link_4' => array(
				'label' 		=> __('Additional Image Link 4', 'woocommerce_wpwoof'),
				'desc'			=> '',//__( 'More images. You can include up to 10 additional images. If supplying multiple images, send them as comma separated URLs.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google','facebook'),
				'facebook_len'	=> 2000,
				'count' 		=> 10,
				'text'			=> true,
				'woocommerce_default' =>array('label' => 'Additional Image Link 4', 'value'=>'additional_image_link_4'), 
			),

			'additional_image_link_5' => array(
				'label' 		=> __('Additional Image Link 5', 'woocommerce_wpwoof'),
				'desc'			=> '',//__( 'More images. You can include up to 10 additional images. If supplying multiple images, send them as comma separated URLs.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google','facebook'),
				'facebook_len'	=> 2000,
				'count' 		=> 10,
				'text'			=> true,
				'woocommerce_default' =>array('label' => 'Additional Image Link 5', 'value'=>'additional_image_link_5'), 
			),

			'additional_image_link_6' => array(
				'label' 		=> __('Additional Image Link 6', 'woocommerce_wpwoof'),
				'desc'			=> '',//__( 'More images. You can include up to 10 additional images. If supplying multiple images, send them as comma separated URLs.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google','facebook'),
				'facebook_len'	=> 2000,
				'count' 		=> 10,
				'text'			=> true,
				'woocommerce_default' =>array('label' => 'Additional Image Link 6', 'value'=>'additional_image_link_6'), 
			),

			'additional_image_link_7' => array(
				'label' 		=> __('Additional Image Link 7', 'woocommerce_wpwoof'),
				'desc'			=> '',//__( 'More images. You can include up to 10 additional images. If supplying multiple images, send them as comma separated URLs.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google','facebook'),
				'facebook_len'	=> 2000,
				'count' 		=> 10,
				'text'			=> true,
				'woocommerce_default' =>array('label' => 'Additional Image Link 7', 'value'=>'additional_image_link_7'), 
			),

			'additional_image_link_8' => array(
				'label' 		=> __('Additional Image Link 8', 'woocommerce_wpwoof'),
				'desc'			=> '',//__( 'More images. You can include up to 10 additional images. If supplying multiple images, send them as comma separated URLs.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google','facebook'),
				'facebook_len'	=> 2000,
				'count' 		=> 10,
				'text'			=> true,
				'woocommerce_default' =>array('label' => 'Additional Image Link 8', 'value'=>'additional_image_link_8'), 
			),

			'additional_image_link_9' => array(
				'label' 		=> __('Additional Image Link 9', 'woocommerce_wpwoof'),
				'desc'			=> '',//__( 'More images. You can include up to 10 additional images. If supplying multiple images, send them as comma separated URLs.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google','facebook'),
				'facebook_len'	=> 2000,
				'count' 		=> 10,
				'text'			=> true,
				'woocommerce_default' =>array('label' => 'Additional Image Link 9', 'value'=>'additional_image_link_9'), 
			),

			'additional_image_link_10' => array(
				'label' 		=> __('Additional Image Link 10', 'woocommerce_wpwoof'),
				'desc'			=> '',//__( 'More images. You can include up to 10 additional images. If supplying multiple images, send them as comma separated URLs.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google','facebook'),
				'facebook_len'	=> 2000,
				'count' 		=> 10,
				'text'			=> true,
				'woocommerce_default' =>array('label' => 'Additional Image Link 10', 'value'=>'additional_image_link_10'), 
			),

			'shipping' => array(
				'label' 		=> __('Shipping', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Shipping Cost.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> false,
				'delimiter'     => true,
				/*'woocommerce_default' =>array('label' => 'Shipping', 'value'=>'shipping')*/
			),

			'shipping_weight' => array(
				'label' 		=> __('Shipping Weight', 'woocommerce_wpwoof'),
				'desc'			=> __( 'The weight of the product for shipping.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> false,
				/*'woocommerce_default' =>array('label' => 'Shipping Weight', 'value' => 'shipping_weight'),*/
			),
			
			'shipping_length' => array(/* For Google Feed */
				'label' 		=> __('Shipping Length', 'woocommerce_wpwoof'),
				'desc'			=> __( 'The length of the product for shipping.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> false,
				/*'woocommerce_default' =>array('label' => 'Shipping Length', 'value' => 'shipping_length'),*/
			),
			'shipping_height' => array(/* For Google Feed */
				'label' 		=> __('Shipping Height', 'woocommerce_wpwoof'),
				'desc'			=> __( 'The height of the product for shipping.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> false,
				/*'woocommerce_default' =>array('label' => 'Shipping Height', 'value' => 'shipping_height'),*/
			),

			'shipping_width' => array(/* For Google Feed */
				'label' 		=> __('Shipping Width', 'woocommerce_wpwoof'),
				'desc'			=> __( 'The width of the product for shipping.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> false,
				/*'woocommerce_default' =>array('label' => 'Shipping Width', 'value' => 'shipping_width'),*/
			),

			'shipping_label' => array(/* For Google Feed */
				'label' 		=> __('Shipping Label', 'woocommerce_wpwoof'),
				'desc'			=> __( 'This attribute can be used to assign labels to specific products using values of your choosing, such as perishable, bulky, or promotion. ', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> 1000,
				'text'			=> true,
				/*'woocommerce_default' =>array('label' => 'Shipping Label', 'value' => 'shipping_label'),*/
			),
		    'multipack' => array(/* For Google Feed */
				'label' 		=> __('Multipack', 'woocommerce_wpwoof'),
				'desc'			=> __( 'Multipacks are packages that include several identical products to create a larger unit of sale, submitted as a single item.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google'),
				'facebook_len'	=> 6,
				/*'woocommerce_default' =>array('label' => 'Multipack', 'value' => 'multipack'),*/
			),



			'custom_label_0' => array(
				'label' 		=> __('Custom Label 0', 'woocommerce_wpwoof'),
				'desc'			=> __( 'This attribute can be used to group the items in a Shopping campaign by values of your choosing, such as seasonal or clearance. Examples: seasonal, clearance, holiday, sale, best seller.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google','facebook'),
				'facebook_len'	=> false,
				'text'			=> true,
				/*'woocommerce_default' =>array('label' => 'Custom Label 0', 'value' => 'custom_label_0'),*/
			),

			'custom_label_1' => array(
				'label' 		=> __('Custom Label 1', 'woocommerce_wpwoof'),
				'desc'			=> __( 'This attribute can be used to group the items in a Shopping campaign by values of your choosing, such as seasonal or clearance. Examples: seasonal, clearance, holiday, sale, best seller.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=>array('google','facebook'),
				'facebook_len'	=> false,
				'text'			=> true,
				/*'woocommerce_default' =>array('label' => 'Custom Label 0', 'value' => 'custom_label_1'),*/
			),

			'custom_label_2' => array(
				'label' 		=> __('Custom Label 2', 'woocommerce_wpwoof'),
				'desc'			=> __( 'This attribute can be used to group the items in a Shopping campaign by values of your choosing, such as seasonal or clearance. Examples: seasonal, clearance, holiday, sale, best seller.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google','facebook'),
				'facebook_len'	=> false,
				'text'			=> true,
				/*'woocommerce_default' =>array('label' => 'Custom Label 2', 'value' => 'custom_label_2'),*/
			),

			'custom_label_3' => array(
				'label' 		=> __('Custom Label 3', 'woocommerce_wpwoof'),
				'desc'			=> __( 'This attribute can be used to group the items in a Shopping campaign by values of your choosing, such as seasonal or clearance. Examples: seasonal, clearance, holiday, sale, best seller.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google','facebook'),
				/*'woocommerce_default' =>array('label' => 'Custom Label 3', 'value' => 'custom_label_3'),*/
				'facebook_len'	=> false,
				'text'			=> true,
			),

			'custom_label_4' => array(
				'label' 		=> __('Custom Label 4', 'woocommerce_wpwoof'),
				'desc'			=> __( 'This attribute can be used to group the items in a Shopping campaign by values of your choosing, such as seasonal or clearance. Examples: seasonal, clearance, holiday, sale, best seller.', 'woocommerce_wpwoof' ),
				'value'			=> false,
				'required'		=> false,
				'feed_type'		=> array('google','facebook'),
				/*'woocommerce_default' =>array('label' => 'Custom Label 4', 'value' => 'custom_label_4'),*/
				'facebook_len'	=> false,
				'text'			=> true,
			),


		);

		if( get_option('woocommerce_calc_taxes',null) != 'yes' ) { unset($this->product_fields['tax']); }
		
		$this->product_fields = apply_filters( 'woocommerce_wpwoof_all_product_fields', $this->product_fields );
	}

	/**
	 * Helper function to remove blank array elements
	 *
	 * @access public
	 * @param array $array The array of elements to filter
	 * @return array The array with blank elements removed
	 */
	private function remove_blanks( $array ) {
		if ( empty( $array ) || ! is_array( $array ) ) {
			return $array;
		}
		foreach ( array_keys( $array ) as $key ) {
			if ( empty( $array[ $key ] ) || empty( $this->settings['product_fields'][ $key ] ) ) {
				unset( $array[ $key ] );
			}
		}
		return $array;
	}

	public function getTaxRateCountries($id=""){
		global $wpdb;
		$sWhere = ($id && is_numeric($id)) ? " where  `tax_rate_id`='".$id."' " : "";
		//echo "SELECT tax_rate_country as shcode, `tax_rate_class` as `class`, `tax_rate_id` as `id`,`tax_rate` as `rate`, `tax_rate_name` as `name` FROM {$wpdb->prefix}woocommerce_tax_rates ".$sWhere." Order By tax_rate_class, tax_rate_country ";
		$res =  $wpdb->get_results( "SELECT tax_rate_country as shcode, `tax_rate_class` as `class`, `tax_rate_id` as `id`,`tax_rate` as `rate`, `tax_rate_name` as `name` FROM {$wpdb->prefix}woocommerce_tax_rates ".$sWhere." Order By tax_rate_class, tax_rate_country ",ARRAY_A );
		return $res;
		/**
		 (
				[0] => Array
					(
						[shcode] => IT
						[class] => 
						[id] => 1
						[rate] => 22.0000
						[name] => IVA
					)

			)
		 */
		
	}

	/**
	 * Helper function to remove items not needed in this feed type
	 *
	 * @access public
	 * @param array $array The list of fields to be filtered
	 * @param string $feed_format The feed format that should have its fields maintained
	 * @return array The list of fields filtered to only contain elements that apply to the selectedd $feed_format
	 */
	private function remove_other_feeds( $array, $feed_format ) {
		if ( empty( $array ) || ! is_array( $array ) ) {
			return $array;
		}
		foreach ( array_keys( $array ) as $key ) {
			if ( empty( $this->product_fields[ $key ] ) || ! in_array( $feed_format, $this->product_fields[ $key ]['feed_types'] ) ) {
				unset ( $array[ $key ] );
			}
		}
		return $array;
	}

	/**
	 * Retrieve the values that should be output for a particular product
	 * Takes into account store defaults, category defaults, and per-product
	 * settings
	 *
	 * @access public
	 * @param  int  $product_id       The ID of the product to retrieve info for
	 * @param  string  $feed_format   The feed format being generated
	 * @param  boolean $defaults_only Whether to retrieve the
							*         store/category defaults only
	 * @return array                  The values for the product
	 */
	public function get_values_for_product( $product_id = null, $feed_format = 'all', $defaults_only = false ) {
		if ( ! $product_id ) {
			return false;
		}
		// Get Store defaults
		if ( ! isset( $this->settings['product_defaults'] ) ) {
			$this->settings['product_defaults'] = array();
		}
		$settings = $this->remove_blanks( $this->settings['product_defaults'] );
		// Merge category settings
		$categories = wp_get_object_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );

		foreach ( $categories as $category_id ) {
			$category_settings = $this->get_values_for_category( $category_id );
			$category_settings = $this->remove_blanks( $category_settings );
			if ( 'all' != $feed_format ) {
				$category_settings = $this->remove_other_feeds( $category_settings, $feed_format );
			}
			if ( $category_settings ) {
				$settings = array_merge( $settings, $category_settings );
			}
		}
		if ( $defaults_only ) {
			return $settings;
		}
		// Merge prepopulated data if required.
		if ( ! empty( $this->settings['product_prepopulate'] ) ) {
			$prepopulated_values = $this->get_values_to_prepopulate( $product_id );
			$prepopulated_values = $this->remove_blanks( $prepopulated_values );
			$settings            = array_merge( $settings, $prepopulated_values );
		}
		// Merge per-product settings.
		$product_settings = get_post_meta( $product_id, '_woocommerce_wpwoof_data', true );
		if ( $product_settings ) {
			$product_settings = $this->remove_blanks( $product_settings );
			$settings = array_merge( $settings, $product_settings );
		}
		if ( 'all' != $feed_format ) {
			$settings = $this->remove_other_feeds( $settings, $feed_format );
		}
		$settings = $this->limit_max_values( $settings );

		return $settings;
	}

	/**
	 * Make sure that each element does not contain more values than it should.
	 *
	 * @param   array   $data  The data for a product / category.
	 * @return                 The modified data array.
	 */
	private function limit_max_values( $data ) {
		foreach ( $this->product_fields as $key => $element_settings ) {
			if ( empty( $element_settings['max_values'] ) ||
				 empty( $data[ $key ] ) ||
				 ! is_array( $data[ $key ] ) ) {
				continue;
			}
			$limit = intval( $element_settings['max_values'] );
			$data[ $key ] = array_slice( $data[ $key ], 0, $limit );
		}
		return $data;
	}

	/**
	 * Retrieve category defaults for a specific category
	 *
	 * @access public
	 * @param  int $category_id The category ID to retrieve information for
	 * @return array            The category data
	 */
	private function get_values_for_category( $category_id ) {
		if ( ! $category_id ) {
			return false;
		}
		if ( isset ( $this->category_cache[ $category_id ] ) ) {
			return $this->category_cache[ $category_id ];
		}
		$values = get_metadata( 'woocommerce_term', $category_id, '_woocommerce_wpwoof_data', true );
		$this->category_cache[ $category_id ] = &$values;

		return $this->category_cache[ $category_id ];
	}

	/**
	 * Get all of the prepopulated values for a product.
	 *
	 * @param  int    $product_id  The product ID.
	 *
	 * @return array               Array of prepopulated values.
	 */
	private function get_values_to_prepopulate( $product_id = null ) {
		$results = array();
		foreach ( $this->settings['product_prepopulate'] as $gpf_key => $prepopulate ) {
			if ( empty( $prepopulate ) ) {
				continue;
			}
			$value = $this->get_prepopulate_value_for_product( $prepopulate, $product_id );
			if ( ! empty( $value ) ) {
				$results[ $gpf_key ] = $value;
			}
		}
		return $results;
	}

	/**
	 * Gets a specific prepopulated value for a product.
	 *
	 * @param  string  $prepopulate  The prepopulation value for a product.
	 * @param  int     $product_id   The product ID being queried.
	 *
	 * @return string                The prepopulated value for this product.
	 */
	private function get_prepopulate_value_for_product( $prepopulate, $product_id ) {
		$result = array();
		list( $type, $value ) = explode( ':', $prepopulate );
		switch ( $type ) {
			case 'tax':
				$terms = wp_get_object_terms( $product_id, array( $value ), array( 'fields' => 'names' ) );
				if ( ! empty( $terms ) ) {
					$result = $terms;
				}
				break;
			case 'field':
				$result = $this->get_field_prepopulate_value_for_product( $value, $product_id );
				break;
		}
		return $result;
	}

	/**
	 * Get a prepopulate value for a specific field for a product.
	 *
	 * @param  string  $field       Details of the field we want.
	 * @param  int     $product_id  The product ID.
	 *
	 * @return array                The value for this field on this product.
	 */
	private function get_field_prepopulate_value_for_product( $field, $product_id ) {
		global $woocommerce_wpwoof_frontend;

		$product = $woocommerce_wpwoof_frontend->load_product( $product_id );
		if ( ! $product ) {
			return array();
		}
		if ( 'sku' == $field ) {
			$sku = $product->get_sku();
			if ( !empty( $sku ) ) {
				return array( $sku );
			}
		}
		return array();
	}

	/**
	 * Generate a list of choices for the "prepopulate" options.
	 *
	 * @return array  An array of preopulate choices.
	 */
	public function get_prepopulate_options() {
		$options = array();
		$options = array_merge( $options, $this->get_available_taxonomies() );
		$options = array_merge( $options, $this->get_prepopulate_fields() );
		return $options;
	}

	/**
	 * get a list of the available fields to use for prepopulation.
	 *
	 * @return array  Array of the available fields.
	 */
	private function get_prepopulate_fields() {
		$fields = array(
			'field:sku' => 'SKU',
		);
		asort( $fields );
		return array_merge( array( 'disabled:fields' => __( '- Product fields -', 'woo_gpf' ) ), $fields );
	}

	/**
	 * Get a list of the available taxonomies.
	 *
	 * @return array Array of available product taxonomies.
	 */
	private function get_available_taxonomies() {
		$taxonomies = get_object_taxonomies( 'product' );
		$taxes = array();
		foreach ( $taxonomies as $taxonomy ) {
			$tax_details = get_taxonomy( $taxonomy );
			$taxes[ 'tax:' . $taxonomy ] = $tax_details->labels->name;
		}
		asort( $taxes );
		return array_merge( array( 'disabled:taxes' => __( '- Taxonomies -', 'woo_gpf' ) ), $taxes );
	}

    public function get_feed_count(){
        global $wpdb;
        $tablenm = $wpdb->prefix.'options';
        $wpdb->get_results( "SELECT *  FROM ".$tablenm." WHERE option_name LIKE '%wpwoof_feedlist_%'" );
        define("FEED_COUNT", $wpdb->num_rows);
        return $wpdb->num_rows;
	}
	public function get_feed_status($feed_id){
		//echo $feed_id;
		//trace(get_option('wpwoof_status_'.$feed_id));
		$feedStatus= get_option('wpwoof_status_'.$feed_id,array());		
		if(empty($feedStatus['time']) )     $feedStatus['time']=0;
		if(empty($feedStatus['products_left']) )   $feedStatus['products_left']  = false;// array product IDs
		if(empty($feedStatus['total_products']) )  $feedStatus['total_products'] = 0; // num total products
		if(empty($feedStatus['parsed_products']) ) $feedStatus['parsed_products']= 0; //
		if(empty($feedStatus['parsed_product_ids']) ) $feedStatus["parsed_product_ids"] =array(); 
		if(empty($feedStatus['type']) ) $feedStatus["type"] ='';		
		return $feedStatus;
	}
	public function upadte_feed_status($feed_id,$newvalue,$isExit=false){
		if(WPWOOF_DEBUG) { echo "UPDATE STATUS:".$feed_id."=>".print_r($newvalue,true)."\n"; }
		$newvalue['time']=time();
		update_option( 'wpwoof_status_'.$feed_id, $newvalue );
		//if(WPWOOF_DEBUG && $isExit) exit;
		
	}
	public function delete_feed_status($feed_id){		
		delete_option( 'wpwoof_status_'.$feed_id );		
	}
}

global $woocommerce_wpwoof_common;
$woocommerce_wpwoof_common = new WoocommerceWpwoofCommon();
