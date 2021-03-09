<?php
/**
 * Customer processing order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-processing-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<!--
<?php /* translators: %s: Customer first name */ ?>
<p><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) ); ?></p>
<?php /* translators: %s: Order number */ ?>
<p><?php printf( esc_html__( 'Samo radi informacije &mdash; Vaša narudžba je potvrđena i narudžba broj #%s se dalje procesira:', 'woocommerce' ), esc_html( $order->get_order_number() ) ); ?></p>
-->

<p>
Hvala vam na vašoj narudžbi!
<br/><br/>
Poslat ćemo vaš paket što je prije moguće.
<br/><br/>
<b>Proizvodi koje ste naručili su ispisani dolje.</b> Ako želite promijeniti sadržaj narudžbe, kontaktirajte nas što je prije moguće na: info@mrmaks.eu.
<br/><br/>
<b>Kad pošaljemo narudžbu, primit ćete informacije o dostavi na vašu email adresu, zajedno s kodom za praćenje, pomoću kojeg ćete moći pratiti svoj paket.</b>
<br/><br/>
<b>Možete očekivati dostavu u roku od 1-2 radnih dana (zbog dostavnih službi, vrijeme dostave se ponekad može produžiti dan ili dva). Pakete dostavlja GLS dostavna služba.</b> Ako ne primite svoj paket u navedenom periodu, kontaktirajte nas na info@mrmaks.eu tako da provjerimo što se događa s vašom narudžbom.
<br/><br/>
Kad primite paket, <b>poslat ćemo vam račun i link na vašu e-mail adresu, gdje možete naći upute za korištenje proizvoda.</b>
<br/><br/>
Ako niste zadovoljni proizvodom, možete ga jednostavno vratiti ili zamijeniti. U slučaju povratka ili zamjene, pošaljite nam e-mail na info@mrmaks.eu i riješit ćemo vaš problem što je prije moguće. 
<br/><br/>
<b>Hvala vam još jednom na narudžbi i ne možemo dočekati da primite paket i pridružite se našim brojnim zadovoljnim kupcima.</b>
<br/><br/>
Tim Mrmaks
</p>


<?php

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

?>
<p>
<?php esc_html_e( 'Thanks!', 'woocommerce' ); ?>
</p>
<?php

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
