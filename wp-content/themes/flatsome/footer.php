<?php
/**
 * The template for displaying the footer.
 *
 * @package flatsome
 */

global $flatsome_opt;
?>

</main><!-- #main -->


<section id="faq">

   <!-- faq -->
   <section style="    background: #f6f6f6 !important" class="section section-footer1" id="section_1082727524">
      <div   class="bg section-bg fill bg-fill  bg-loaded"></div>
      <div class="section-content relative">
         <div  class="row rowpadding row-title" id="row-1541745431">
            <div id="col-200886885" class="col small-12 large-12">
               <div class="col-inner">
					<h2 style="text-align: center;"><?php echo get_field("faq_naslov_f","options"); ?></h2>
					<p style="text-align: center;"><?php echo get_field("faq_podnaslov_f","options"); ?></p>
               </div>
            </div>
         </div>
         <div  class="row rowpaddign row-accordion" id="row-1467227888">
            <div id="col-1504609161" class="col small-12 large-12">
               <div class="col-inner">
                  <div class="accordion accordion-custom" rel="">

					    <div class="accordion-item">
                        <a href="#" class="accordion-title plain"><button class="toggle"><i class="icon-angle-down"></i></button><span><?php echo get_field("faq_fnaslov_1_f","options"); ?></span></a>
                        <div class="accordion-inner">
                           <p><?php echo get_field("faq_ftekst_1_f","options"); ?></p>
                        </div>
                     </div>
                     <div class="accordion-item">
                        <a href="#" class="accordion-title plain"><button class="toggle"><i class="icon-angle-down"></i></button><span><?php echo get_field("faq_fnaslov_2_f","options"); ?></span></a>
                        <div class="accordion-inner">
                           <p><?php echo get_field("faq_ftekst_2_f","options"); ?></p>
                        </div>
                     </div>
                     <div class="accordion-item">
                        <a href="#" class="accordion-title plain"><button class="toggle"><i class="icon-angle-down"></i></button><span><?php echo get_field("faq_fnaslov_3_f","options"); ?></span></a>
                        <div class="accordion-inner">
                           <p><?php echo get_field("faq_ftekst_3_f","options"); ?></p>
                        </div>
                     </div>
                     <div class="accordion-item">
                        <a href="#" class="accordion-title plain"><button class="toggle"><i class="icon-angle-down"></i></button><span><?php echo get_field("faq_fnaslov_4_f","options"); ?></span></a>
                        <div class="accordion-inner">
                           <p><?php echo get_field("faq_ftekst_4_f","options"); ?></p>
                        </div>
                     </div>
                     <div class="accordion-item">
                        <a href="#" class="accordion-title plain"><button class="toggle"><i class="icon-angle-down"></i></button><span><?php echo get_field("faq_fnaslov_5_f","options"); ?></span></a>
                        <div class="accordion-inner">
                           <p><?php echo get_field("faq_ftekst_5_f","options"); ?></p>
                        </div>
                     </div>
                     <div class="accordion-item">
                        <a href="#" class="accordion-title plain"><button class="toggle"><i class="icon-angle-down"></i></button><span><?php echo get_field("faq_fnaslov_6_f","options"); ?></span></a>
                        <div class="accordion-inner">
                           <p><?php echo get_field("faq_ftekst_6_f","options"); ?></p>
                        </div>
                     </div>

                  </div>
               </div>
            </div>
         </div>






      </div>
<style>

.footer-badges  {
	border: none !important;
	
}

.footer-badges .col {
	width: 25% !important;
	max-width: 25% !important;
}



.footer-2.dark .payment-icon {
    background-color: #000;
}
.product .woocommerce-tabs {
	padding-bottom: 0;
}

.back-top {
	display: none;
}

	  .footer-widgets {
	background: #f6f6f6 !important;
	border: none;

}
.footer-widgets .col {
	text-align: left !important;
}
.footer-widgets #text-16 .widget-title{
	margin-bottom: 20px;
    display: block;
}
.footer-badges p {
	    font-weight: bold !important;
    font-size: 15px !important;
    line-height: 1.3 !important;
}
.is-divider {
	display: none;

}

@media only screen and (min-width:851px) {
.footer-badges img {
	width: 35%  !important;
}
}

@media only screen and (max-width:850px) {


		.footer-badges .col{
			width: 50% !important;
			margin-bottom: 20px;
			padding: 0px 5px 10px 5px;
		  }

.footer-badges p {
			font-weight: 500 !important;
			font-size: 11px !important;
			line-height: 1.3 !important;
			text-transform: none !important;
		}
.row-countries  p  {
	width: 32% !important;
	display: inline-flex !important;
	margin-bottom: 0.2em;
				}
.row-countries .col-inner {
			display: inline-block !important;
			padding-bottom: 20px;
		}
.row-countries p span {
			text-align: left !important;
		}
	}

.row-countries .col-inner {
    display: flex;
    justify-content: space-between;
}
.row-countries .col {
	max-width: 100%;
	-ms-flex-preferred-size: 100%;
	flex-basis: 100%;
}
.row-countries img {
    width: 27px;
    margin-bottom: 6px;
    margin-right: 5px;
	margin-left: 5px;
}
#section_1082727524 {
   padding-top: 30px;
   padding-bottom: 30px;
}

.footer-2 .widget-title {
    color: #000 !important;
}
.footer-2  .footer-text  {
    color: #000 !important;
}
</style>
   </section>
   <!-- faq -->

</section>




<section style="padding-top: 0 !important;" class="section section-footer1" id="section_1082727524">
      <div   class="bg section-bg fill bg-fill  bg-loaded"></div>
      <div class="section-content relative">



<div  style="background:white; padding-top: 0;" class="footer-badges">
        <div class="row dark large-columns-4 mb-0 text-center">
            <div class="col">
                <img style="width:55%;" src="/hr/wp-content/themes/flatsome-child/woocommerce/checkout/prva.png">
                <p>Sigurna <br/>kupnja

</p>
            </div>
            <div class="col">
                <img style="width:55%;" src="/hr/wp-content/themes/flatsome-child/woocommerce/checkout/druga.png">
                <p>100% zadovoljnih <br/>kupaca

</p>
            </div>
            <div class="col">
                <img  style="width:55%;"src="/hr/wp-content/themes/flatsome-child/woocommerce/checkout/tretja.png">
                <p>Brza<br/> dostava

</p>
            </div>
			<div class="col">
                <img  style="width:55%;" src="/hr/wp-content/themes/flatsome-child/woocommerce/checkout/cetrta.png">
                <p>Plaćanje prilikom<br/> preuzimanja</p>
            </div>
        </div>
    </div>


<div style="background: #f6f6f6;    margin-bottom: -30px;    padding-top: 30px;    padding-bottom: 10px;" class="conut-arr">
<div class="row rowpadding row-countries" id="row-1213773870">
            <div style="margin-bottom: -10px; padding-bottom: 0;" id="col-1689065685" class="col small-12 large-12">

			<h2 style="text-align: center;     margin-bottom: 25px;"><?php echo get_field("eu_seller_tekst","options"); ?></h2>

               <div style="border-bottom: 1px solid black;" class="col-inner dark">
                  <p style="padding: 5px;"><a href="/si"><img class="alignnonewp-image-820" src="https://mistermega.hu/wp-content/themes/flatsome/landing2/images/Group-1142.svg" alt="" width="27" height="16"><span style="text-align: center; display: block; color: #2b2b2b;">Slovenia</span></a></p>
                  <p  style="padding: 5px;"><a href="/sk"><img class="alignnonewp-image-820" src="https://mistermega.hu/wp-content/themes/flatsome/landing2/images/slovakia.jpg" alt="" width="27" height="16"><span style="text-align: center; display: block; color: #2b2b2b;">Slovakia</span></a></p>
                  <p style="padding: 5px;"><a href="/hr"><img class="alignnonewp-image-813" src="https://mistermega.hu/wp-content/themes/flatsome/landing2/images/Group-1236.svg" alt="" width="27" height="16"><span style="text-align: center; display: block; color: #2b2b2b;">Croatia</span></a></p>
                  <p style="padding: 5px;"><a href="/pl"><img class="alignnonewp-image-814" src="https://mistermega.hu/wp-content/themes/flatsome/landing2/images/Group-1239.svg" alt="" width="27" height="16"><span style="text-align: center; display: block; color: #2b2b2b;">Poland</span></a></p>
                  <p style="padding: 5px;"><a href="/hu"><img class="alignnonewp-image-815" src="https://mistermega.hu/wp-content/themes/flatsome/landing2/images/Group-1242-2.svg" alt="" width="27" height="16"><span style="text-align: center; display: block; color: #2b2b2b;">Hungary</span></a></p>
                  <p style="padding: 5px;"><a href="/it"><img class="alignnonewp-image-816" src="https://mistermega.hu/wp-content/themes/flatsome/landing2/images/Group-1245.svg" alt="" width="26" height="16"><span style="text-align: center; display: block; color: #2b2b2b;">Italy</span></a></p>
                  <p style="padding: 5px;"><a href="/ro"><img class="alignnonewp-image-817" src="https://mistermega.hu/wp-content/themes/flatsome/landing2/images/Group-1248.svg" alt="" width="27" height="16"><span style="text-align: center; display: block; color: #2b2b2b;">Romania</span></a></p>
                  <p style="padding: 5px;"><a href="/at"><img class="alignnonewp-image-818" src="https://mistermega.hu/wp-content/themes/flatsome/landing2/images/Group-1251.svg" alt="" width="26" height="15"><span style="text-align: center; display: block; color: #2b2b2b;">Austria</span></a></p>
                  <p style="padding: 5px;"><a href="/de"><img class="alignnonewp-image-819" src="https://mistermega.hu/wp-content/themes/flatsome/landing2/images/Group-1253.svg" alt="" width="26" height="16"><span style="text-align: center; display: block; color: #2b2b2b;">Germany</span></a></p>
                  <p style="padding: 5px;"><a href="/cz"><img class="alignnonewp-image-819" src="https://mistermega.hu/wp-content/themes/flatsome/landing2/images/czech_flag.jpg" alt="" width="26" height="16"><span style="text-align: center; display: block; color: #2b2b2b;">Czech Republic</span></a></p>
               </div>
            </div>
         </div>
</div>


      </div>


   </section>
   <!-- faq -->

</section>





<footer id="footer" class="footer-wrapper">

	<?php do_action('flatsome_footer'); ?>

</footer><!-- .footer-wrapper -->

</div><!-- #wrapper -->

<?php wp_footer(); ?>

</body>
</html>
