<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$design_data = $this->get_design_data();


?>
<style>
    <?php if ( $design_data['enable_box_border'] == 'true' ) { ?>
    body #wfob_wrap .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump {
        border: <?php echo sprintf( '%dpx %s %s', $design_data['border_width'], $design_data['border_style'], $design_data['border_color'] ); ?>;
    }

    <?php } ?>

    body #wfob_wrap .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump {
        background: <?php echo $design_data['box_background']; ?>;
        padding: <?php echo $design_data['box_padding']; ?>px;
    }

    body #wfob_wrap .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump .wfob_bgBox_table {
        background: <?php echo $design_data['heading_background']; ?>;
        padding: <?php echo $design_data['heading_box_padding']; ?>px;
        font-size: <?php echo $design_data['heading_font_size']; ?>px;

    }

    body #wfob_wrap .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump .wfob_bgBox_table .wfob_title, #wfacp-e-form .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump .wfob_bgBox_table .wfob_title {
        color: <?php echo $design_data['heading_color']; ?>;
        margin-bottom: 0;
    }

    body #wfob_wrap .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump .wfob_bgBox_table .wfob_title:hover, #wfacp-e-form .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump .wfob_bgBox_table .wfob_title:hover {
        color: <?php echo $design_data['heading_hover_color']; ?>;
    }

    body #wfob_wrap .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump .wfob_bgBox_table:hover {
        background-color: <?php echo $design_data['heading_hover_background']; ?>;
    }

    body #wfob_wrap .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump .wfob_price_container {
        color: <?php echo $design_data['price_color']; ?>;
    }

    body #wfob_wrap .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump .wfob_contentBox {
        color: <?php echo $design_data['content_color']; ?>;
        font-size: <?php echo $design_data['content_font_size']; ?>px;
        padding: <?php echo $design_data['content_box_padding']; ?>px;
    }

    body #wfob_wrap .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump .wfob_contentBox p, #wfacp-e-form .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump .wfob_contentBox p {
        color: <?php echo $design_data['content_color']; ?>
    }

    body #wfob_wrap .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump .wfob_contentBox .wfob_pro_txt_wrap a, #wfacp-e-form .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump .wfob_contentBox .wfob_pro_txt_wrap a {
        color: <?php echo $design_data['content_variation_link_color']; ?>
    }

    body #wfob_wrap .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump .wfob_contentBox .wfob_pro_txt_wrap a, #wfacp-e-form .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump .wfob_contentBox .wfob_pro_txt_wrap a {
        color: <?php echo $design_data['content_variation_link_color']; ?>
    }

    <?php if ( isset( $design_data['error_color'] ) ) { ?>
    body #wfob_wrap .wfob_wrapper .wfob_outer .wfob_order_wrap .wfob_error_message {
        color: <?php echo $design_data['error_color']; ?>;
    }

    <?php
}
	?>

    body #wfob_wrap .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump .wfob_contentBox .wfob_qv-button:hover {
        color: <?php echo $design_data['content_variation_link_hover_color']; ?>;
    }

    <?php if ( $design_data['enable_featured_image_border'] == 'true' ) { ?>
    body #wfob_wrap .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump .wfob_contentBox .wfob_pro_img_wrap {
        border: <?php echo sprintf( '%dpx %s %s', $design_data['featured_image_border_width'], $design_data['featured_image_border_style'], $design_data['featured_image_border_color'] ); ?>;
    }

    <?php } ?>

    body #wfob_wrap .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump .wfob_price_container span.woocommerce-Price-amount.amount {
        font-size: <?php echo $design_data['price_font_size']; ?>px;
    }

    body #wfob_wrap .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump .wfob_price_container span.woocommerce-Price-amount.amount, #wfacp-e-form .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump .wfob_price_container span.woocommerce-Price-amount.amount {
        font-size: <?php echo $design_data['price_font_size']; ?>px;
    }

    body #wfob_wrap .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] .wfob_bump .wfob_price_container .wfob_price ins {
        color: <?php echo $design_data['price_sale_color']; ?>;
    }

    body #wfob_wrap .wfob_price ins {
        background: transparent;
    }

    @media (min-width: 768px) {
        #wfob_wrap .wfob_wrapper[data-wfob-id='<?php echo $this->get_id(); ?>'] {
        <?php
		if ( isset( $design_data['bump_max_width'] ) && $design_data['bump_max_width'] > 0 ) {
			echo 'max-width:' . $design_data['bump_max_width'] . 'px;';
		}
		?> margin: 0 auto;
        }
    }


</style>

<style>
    <?php
	$globalSetting = WFOB_Common::get_global_setting();
	if ( isset( $globalSetting['css'] ) && $globalSetting['css'] != '' ) {
		echo $globalSetting['css'];}
	?>
</style>


