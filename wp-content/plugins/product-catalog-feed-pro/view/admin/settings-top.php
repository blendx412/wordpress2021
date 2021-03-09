<div class="wpwoof-content-top wpwoof-box">
    <div class="wpwoof-left">
        <h1>Help:</h1>
        <p>
            <h3><strong> - Using the plugin for the first time? <a href="http://www.pixelyoursite.com/product-catalog-help" target="_blank">Click here for help</a></strong></h3>
        </p>
    </div>

    <div class="wpwoof-right">
        <h2>Facebook Pixel Setup:</h2>

        <?php if( !class_exists('Pixel_Your_Site_Pro') ) { ?>
            <p>
                Setup Facebook Pixel for Dynamic Ads with <b>PixelYourSite Pro:</b><br>
                <a href="http://www.pixelyoursite.com/super-offer" target="_blank"><b class="wpwoof-clr-orange wpwoof-15p">Download PixelYourSite Pro for a big discount</b></a>
            </p>
        <?php } else {
            $pixelyoursite_pro = get_option('fpmp_facebookpixel_admin_settings');
            $woocommerce_settings = isset( $pixelyoursite_pro['woocommerce']['activate'] ) ? $pixelyoursite_pro['woocommerce']['activate'] : 0;
            ?>
            <p>You'r using PixelYourSite PRO. 
                <?php if( 1 != $woocommerce_settings) { ?> 
                    Make sure WooCommerce settings are activated. 
                <?php } else { ?>
                    Great!
                <?php } ?>
            </p>
        <?php } ?>
    </div>
</div>