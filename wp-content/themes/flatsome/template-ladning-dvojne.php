<?php
/**
 * Template Name: 2. Landing Page Builder VARIACIJE DVOJNE
 *
 * This template can be used to override the default template and sidebar setup
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
		
        <link rel="stylesheet" href="/hr/wp-content/themes/flatsome/landing-builder/css/all3.css">
        <link rel="stylesheet" href="/hr/wp-content/themes/flatsome/landing-builder/css/custom_qty.css">
		<link rel="stylesheet" href="/hr/wp-content/themes/flatsome/landing-builder/slick.min.css">
		<link rel="stylesheet" href="/hr/wp-content/themes/flatsome/landing-builder/slick-theme.css">
   
		<input style="display: none;" id="price11" value="<?php echo get_field("izdelek_cena_1_kos"); ?>">
		<input style="display: none;" id="price22" value="<?php echo get_field("izdelek_cena_2_kos"); ?>">
		<input style="display: none;" id="price33" value="<?php echo get_field("izdelek_cena_3_kos"); ?>">
        <input style="display: none;" id="pricefull" value="<?php echo get_field("izdelek_cena_full_price"); ?>">
		<input style="display: none;" id="valuta" value="<?php echo get_field("izdelek_cena_valuta"); ?>">
 		<?php $date =  date("Y/m/d");    ?>
		<input style="display: none;" id="datumc" value="<?php echo $date; ?>">
		
		<style>
			#faq { display: none; }
			#footer { display: none; }
			#section_1082727524 { display: none; }
		</style>

	
<?php $valuta =  get_field("izdelek_cena_valuta"); ?> 


<?php

$domain2 = $_SERVER['HTTP_HOST'];

//var_dump($domain);

//mistermega.si

$domain = "https://" . "mistermega.si". "/kosarica";

if($domain2 ==  "mistermega.si") {
	
	$domain = "https://" . "mistermega.si". "/kosarica";
	
}
elseif( $domain2 ==  "mistermega.hr" ) {
	
	$domain = "https://" . "mistermega.hr". "/kosik";
}

elseif( $domain2 ==  "mistermega.hu" ) {
	
	$domain = "https://" . "mistermega.hu". "/kosar";
}

elseif( $domain2 ==  "mister-mega.hu" ) {
	
	$domain = "https://" . "mister-mega.hu". "/kosar";
}

elseif( $domain2 ==  "mistermega.pl" ) {
	
	$domain = "https://" . "mistermega.pl". "/zakonczenie-zakupu";
}




elseif( $domain2 ==  "mistermega.ro" ) {
	
	$domain = "https://" . "mistermega.ro". "/cart";
}
elseif( $domain2 ==  "mistermega.it" ) {
	
	$domain = "https://" . "mistermega.it". "/kosarica";
}
elseif( $domain2 ==  "mistermega.cz" ) {
	
	$domain = "https://" . "mistermega.cz". "/kosik";
}
elseif( $domain2 ==  "mistermega.sk" ) {
	
	$domain = "https://" . "mistermega.sk". "/kosik";
}
elseif( $domain2 ==  "miste-rmega.sk" ) {
	
	$domain = "https://" . "mister-mega.pl". "/dokoncite-nakup";
}


$domain = "https://" . "mrmaks.eu/hr". "/ukoncit-nakup";


// mistermega.ro cart
// mistermega.it kosarica
// mistermega.cz kosik
// mistermega.sk  kosik
// mister-mega.sk  dokoncite-nakup


// hr   https://mistermega.hr/ukoncit-nakup
// hu 2 https://mister-mega.hu/vasarlas-befejezese/
// hu 2 https://mistermega.hu/vasarlas-befejezese/
// pl https://mistermega.pl/zakonczenie-zakupu
	
 $pid = get_field('podatki_o_izdelku_copy');  
 $product = wc_get_product( $pid );
	$available_variations = $product->get_available_variations();
$available_variations = array_reverse($available_variations);



//print("<pre>".print_r($available_variations,true)."</pre>");
//die();


 ?>
 
 <input style="display: none;" id="basee" value="<?php echo $domain; ?>">
 
 <?php 
 
 $polna_cema = get_field("skrij_ceno");
 
 ?>
 <?php if ( $polna_cema == true ): ?>
 <style>
     
     .full-price {
         display: none !important;
     }
     
     .custom_qty {
         /*    margin-bottom: -25px; */
     }
 </style>
<?php endif; ?>
		
<style>
    
    header, footer, #wpadminbar, #main-menu, #login-form-popup, .cc-banner, .cc-bottom, .cc-window, .cc-window, .cc-floating, .cc-type-info, .cc-theme-block, .cc-bottom, .cc-color-override--1766633788, #cc-countdown-wrap, .footer-badges, .checkout-countdown-wrapper     {
        
        display: none !important;
    }
    
    .skip-link {
         display: none !important;
    }
    
    html {
        
        margin-top: 0 !important;
    }
    
	
			@media only screen and (max-width: 900px) {
				  .guaranties {
					margin-top: -27px !important;
						margin-bottom: -10px  !important;
						width: 94%;
    display: block;
    margin: 0 auto;
				  }
				  
				  .watch .property-wrapper {
					  
					  margin-top: 0 !important;
				  }
				  
				    .watch .properties {
					  
					  margin-top: 1rem !important;
				  }
				}
				
				
				.buy-wrapper .selects {
				    
				    width: 5% !important;
				}
				
					.buy-wrapper #price-calculation {
				    
				      width: 95% !important;
				}
	
</style>


	<?php 
	// header fields
	$pasica = get_field("zgornja_info_pasica");// var_dump($pasica);
	$pasica_tekst = get_field("pasica_tekst");
	$pasica_barva = get_field("pasica_barva");
	$pasica_barva_tekst = get_field("pasica_barva_tekst");

	
	
	$logo = get_field("logo");
	$header_main_color = get_field("header_main_color");
	$middle_header = get_field("middle_header");
	
	
	?>
	
	




        <div class="container">
            <span class="arrows-scroll slide-in-top"></span>
        </div>
		
            <section  style="    height: 4rem;"  class="sticky">
			
			
    <div  style="background: <?php echo $header_main_color; ?> !important;     height: 4rem;" class="wrapper">
	
			
				
				<?php if($pasica): ?>
			
			<div style="top: 0;
    position: absolute;
    /* text-align: center; */
    left: 0; width: 100%; display: block;text-align: center; background: <?php echo $pasica_barva; ?>; height: 20px;">
				<div class="container text-center">
				<p style="font-size: 13px; padding-top: 2px;    display: block;
    margin: 0 auto; text-align: center; font-weight: bold; color: <?php echo $pasica_barva_tekst;?>" ><?php echo $pasica_tekst; ?></p>
				</div>
			</div>
	<?php endif; ?>
				
			
			
			
	
        <div style="    margin-top: 18px;" class="container">
            <div class="logo">
                <span>
                  
                  <img  style="width: 52px; height:38px; " src="<?php echo $logo; ?>" />
                </span>
            </div>

            <div class="other">
              <div class="phone">
                  <div class="icons mp_whatsapp_top">
                      <!--  <div class="is-hidden-mobile"><a href="#"><img src="/wp-content/themes/flatsome/landing-builder/img/WhatsApp2.png" width="40" height="60" style="position:relative; top:4px; right:1px"></a>
						</div>-->
                     <div class="mobileonly">
                      <div class="img-wrapper">
                        <!--<img src="/wp-content/themes/flatsome/landing-builder/img/phone_icon2.svg">-->
                    
                      </div>
                  </div>
                  </div>
                  <div class="txt">
               
                        
                        <?php echo $middle_header; ?>
                        
                   
                    </div>
                </div>
                <div class="is-hidden-mobile">
                 <div class="button-wrapper">
                  <!--  <button class="button is-third will-scroll mp_cta_top" data-scroll=".scroll-target">
                        <span>KUP <span>TOURMALINE</span></span>
                    </button>-->
                  </div>
                </div>
                <div class="mobileonly">
                <div class="button-wrapper">
                    <div class="phone">
                      <!--
                    <div class="icons mp_whatsapp_top">
                      <a class="whatsapp-header-url" href="https://api.whatsapp.com/send?phone=38641600093&amp;text=Witaj%2C%20jestem%20zainteresowany%20zakupem%20produktu%3A"><img class="whatsapp-img" src="https://mistermega.si/wp-content/uploads/2018/10/whatsapp-logo.png"><span class="whatsapp-text">WhatsApp</span></a>    
					  </div>
					  -->
                      
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>

</section>        


    
    
    
    <!-- modularna vsebina -->


<?php  if( have_rows('modularni') ):?>

    <?php while ( have_rows('modularni') ) : the_row(); ?>

    <!-- kdo so - osebe -->
        <?php if( get_row_layout() == 'uvodna' ): ?>
            <?php
            $img = get_sub_field('slika_gif');   
			$tekst1 = get_sub_field('naslov');  
			$tekst2 = get_sub_field('cena');  
			$tekst3 = get_sub_field('cena_akcija');  
			$tekst4 = get_sub_field('znizanje');  
			$tekst5 = get_sub_field('tekst_last');  
			
			$color1 =  get_sub_field('barva_ozadje');  
			$color2 =  get_sub_field('barva_tekst');  
			$color3 =  get_sub_field('barva_ozadje_2'); 
			
			$video = get_sub_field('video_file');   
			$video2 = get_sub_field('video_file_2');   
            ?>
            
             
    <section style="padding-left: 0;
    padding-right: 0;" class="section sticky-child top">
        <div class="container">
            <div class="columns">
                <div class="column is-12-mobile is-half is-marginless is-paddingless">
				
				<?php if( $video != false && $video != null && $video != "" ): ?>
					
					<video  autoplay playsinline  loop muted width="100%" height="400"  >
					  <source src="<?php echo $video;  ?>" type="video/mp4">
					   <source src="<?php echo $video2;  ?>" type="video/ogg">
					</video>

				
				<?php else: ?>
                   <div class="is-hidden-mobile">
                     <img style="width:550px; height: 550px;" src="<?php echo $img; ?>"></div>
				<div class="mobileonly"><img style="width: 100%; height: auto;" src="<?php echo $img; ?>"></div>
				<?php endif; ?>

                </div>
                <div class="column is-12-mobile is-half is-marginless is-paddingless">
                    <div class="wrapper">
                        <h2 style="padding-left: 10px;   padding-right: 10px;"><b><?php echo $tekst1; ?></b></h2>
                        <div style="margin: 10px 0 10px 0;"></div>
                        <div style="background: <?php echo $color1; ?> !important;" class="banner primary">
                            <span class="old"><?php echo $tekst2; ?>
</span>
                            <span class="new"><b><?php echo $tekst3; ?>
</b></span>
                        </div>
                        <div class="savings">
                            <?php echo $tekst4; ?>
                        </div>
                        <a href="#nakup" style="background: <?php echo $color3; ?> !important;" class="banner secondary will-scroll mp_cta_main"">
                            <b><?php echo $tekst5; ?>
</b>
                        </a>
               
                     </div>
                </div>
            </div>
        </div>
    </section>
		  


        <?php elseif( get_row_layout() == 'ikone-desno' ): ?>
            
			 <?php
			$naslov = get_sub_field('naslov');  
            $img = get_sub_field('slika_leva');   
			
			$tekst1 = get_sub_field('ikona_1_tekst');  
			$tekst2 = get_sub_field('ikona_2_tekst');  
			$tekst3 = get_sub_field('ikona_3_tekst');  
			$tekst4 = get_sub_field('ikona_4_tekst');  
			
			$img1 = get_sub_field('ikona_1');  
			$img2 = get_sub_field('ikona_2');  
			$img3 = get_sub_field('ikona_3');  
			$img4 = get_sub_field('ikona_4');  
			
			$color11 = get_sub_field('barva_tekst');  
            ?>
            
            
            <section class="section intro">
    <div class="container">
        <div class="intro intro2">
            <strong><?php echo $naslov; ?></strong>
        </div>
        <div class="text">

        </div>
    </div>
</section>
<section class="section watch">
    <div class="container">
        <div class="columns">
            <div class="column is-12-mobile is-1">
            </div>
            <div class="column is-12-mobile is-4">

                <img style="display: block;  margin: 0 auto;" src="<?php echo $img; ?>" />

            </div>

            <div class="column is-9 deskto-up">
                <div class="properties">
                    <div class="property-wrapper">

                        <div class="item icon">
                            <span>
                        <img src="<?php echo $img1; ?>"  width="80%">
                        </span>
                        </div>
                        <div class="item">
                            <h3 style="color: <?php echo $color11; ?> !important;" class="title is-3"><?php echo $tekst1; ?></h3>
                        </div>
                    </div>
                    <br>
                    <div class="property-wrapper">
                        <div class="item icon">
                            <span>
                      <img src="<?php echo $img2; ?>" width="80%" >
                        </span>
                        </div>
                        <div class="item">
                            <h3 style="color: <?php echo $color11; ?> !important;" class="title is-3"><?php echo $tekst2; ?></h3>
                        </div>
                    </div>
                    <br>
                    <div class="property-wrapper">
                        <div class="item icon">
                            <span>
                        <img src="<?php echo $img3; ?>" width="80%">
                        </span>
                        </div>
                        <div class="item">
                            <h3 style="color: <?php echo $color11; ?> !important;" class="title is-3"><?php echo $tekst3; ?></h3>
                        </div>
                    </div>
                    <br>
                    <div class="property-wrapper">
                        <div class="item icon">
                            <span>
                        <img src="<?php echo $img4; ?>" width="80%">
                        </span>
                        </div>
                        <div class="item">
                            <h3 style="color: <?php echo $color11; ?> !important;" class="title is-3"><?php echo $tekst4; ?></h3>
                        </div>
                    </div>

                    <div class="column is-12-mobile is-1">

                    </div>
                </div>
            </div>
        </div>
</section>
            
        <?php elseif( get_row_layout() == '4-ikone-vrsta' ): ?>
            
			 <?php
			
			$tekst1 = get_sub_field('tekst_1');  
			$tekst2 = get_sub_field('tekst_2');  
			$tekst3 = get_sub_field('tekst_3');  
			$tekst4 = get_sub_field('tekst_4');  
			
			$img1 = get_sub_field('ikona_1');  
			$img2 = get_sub_field('ikona_2');  
			$img3 = get_sub_field('ikona_3');  
			$img4 = get_sub_field('ikona_4');  

            ?>
                
    <div class="mobileonly">
         <section class="section guaranties">
        <div class="container">
            <div class="guaranties hover-parent">
                <div>
                    <img src="<?php echo $img1; ?>">
                    <?php echo $tekst1; ?>

                </div>
                <div>
                    <img src="<?php echo $img2; ?>">
                   <?php echo $tekst2; ?>

                </div>
                <div>
                    <img src="<?php echo $img3; ?>">
                 <?php echo $tekst3; ?>

                </div>
                <div>
                    <img src="<?php echo $img4; ?>">
                  <?php echo $tekst4; ?>

                </div>
            </div>
        </div>
    </section>
</div>
            
        <?php elseif( get_row_layout() == 'sekcija-okgrogle-slike' ): ?>
            
					 <?php
					 
			$naslov = get_sub_field('naslov');  
            $naslov2 = get_sub_field('tekst');   
			
			$tekst1 = get_sub_field('tekst_1');  
			$tekst2 = get_sub_field('tekst_2');  
			$tekst3 = get_sub_field('tekst_3');  
			
			$img1 = get_sub_field('slika_1');  
			$img2 = get_sub_field('slika_2');  
			$img3 = get_sub_field('slika_3');  

			$color11 = get_sub_field('barva_ozadje');  
			
			$bgimage = get_sub_field('barva_ozadje_copy');  
			
			
            ?>
            
    
   <section style=" background: <?php if( $bgimage != false && $bgimage != null && $bgimage != "" ) { echo "url('" . $bgimage . "')"; } else { echo $color11; } ?> !important;  <?php if( $bgimage != false && $bgimage != null && $bgimage != "" ) { echo "background-repeat: no-repeat !important; background-size: cover !important;"; } else { } ?>   padding: 3rem 1.5rem 1rem 1.5rem;" class="section reasons">
        <div class="container slides-inverted-button">
            <div class="inline-title">
                <b><?php echo $naslov; ?></b>
            </div>
            <div>
            <?php echo $naslov2; ?>


            </div>
            <div class="columns slides slides-mobile-only carousel-3">
                <div class="column is-4 is-12-mobile cc-3">
   
   <img style="margin: 0 auto;" src="<?php echo $img1; ?>">

    <h3 class="title is-3"><?php echo $tekst1; ?></h3>
</div>
                <div class="column is-4 is-12-mobile cc-1">
    <img  style="margin: 0 auto;" src="<?php echo $img2; ?>">

    <h3 class="title is-3"><?php echo $tekst2; ?></h3>
</div>                <div class="column is-4 is-12-mobile cc-2">
    <img style="margin: 0 auto;" src="<?php echo $img3; ?>">

    <h3 class="title is-3"><?php echo $tekst3; ?></h3>
</div> 
        </div>
        </div>
    </section>
            
        <?php elseif( get_row_layout() == 'sekcija-nakup' ): ?>
            
			<?php
            $repeter = get_sub_field('slike');   //d($title);
			
			$tekst1 = get_sub_field('tekst');  
			$tekst2 = get_sub_field('tekst_2');  	
			$tekst3 = get_sub_field('tekst_3');  
			$tekst4 = get_sub_field('tekst_4');  
			$ocena = get_sub_field('ocena');  
			$bestseller = get_sub_field('bestseller');  
			
			$bestseller_barva = get_sub_field('bestseller_barva');  
			
			$kolicina = get_sub_field('kolicina');  
			
			$izdelek = get_field('podatki_o_izdelku_copy');  
			 
			$payments_slike = get_sub_field('payments_slike');  
			$dostava = get_sub_field('dostava');  
			$dostava_tekst = get_sub_field('dostava_tekst');  
			$dodaj_v_kosarico = get_sub_field('dodaj_v_kosarico');  
			$na_voljo_se = get_sub_field('na_voljo_se');  
			
			$dodaj_v_kosarico2 = get_sub_field('dodaj_v_kosarico_color');  
			
			$dni = get_sub_field('time1');  
			$ur =  get_sub_field('time2');  
			$min = get_sub_field('time3');  
			$sek =  get_sub_field('time4');  
			
			$vartekst =  get_sub_field('vartekst'); 
			
            ?>
            
            
	
	
    <section class="section buy">
        <div class="container">

                <div class="columns">
                    <div class="column is-6 is-12-mobile">
							<div class="slides-wrapper2 carousel-2">
                   
								<?php if($repeter): ?>
								
									<?php foreach($repeter as $r): ?>
									
										<img style="    padding: 40px;" src="<?php echo $r['slika']; ?>" width="574">
									
									
									<?php endforeach; ?>
								<?php endif; ?>
                         
                         
								</div>
                  </div>
                    <div class="column is-6 is-12-mobile scroll-target">
                        <div class="inline-title">
                            <span class="shop"><b><?php echo $tekst1; ?></b></span>
                        </div>
                        <div class="stats"><div id="nakup"></div>
                            <div class="supply show"><?php echo $tekst2; ?></div>
                        
                            <div class="sold"><?php echo $tekst3; ?></div>
                         
                         
                   
                        </div>
                        <div class="description"><?php echo $tekst4; ?></div>
                        <div class="not-available-retail">
           
                        </div>
                        <div class="rating">
                            <img src="/hr/wp-content/themes/flatsome/landing-builder/img/zvezdice_ocena.png">
                            <span><?php echo $ocena; ?></span>
                        </div>
                        
                        <form id="nakupna" method="GET" action="<?php echo $domain; ?>/?add-to-cart=<?php echo $available_variations[0]['variation_id']; ?>" accept-charset="UTF-8">
                        <div class="buy-wrapper">
                            <div class='custom_qty'>
                                <table>
                                    <tr>
                                        <td class="product_price_top_up"></td>
                                        <td style="background: <?php echo $bestseller_barva; ?> !important;" class="product_price_top_up BEST-SELLER"><?php echo $bestseller; ?></td>
                                        <td class="product_price_top_up"></td>
                                    </tr>
                                    <tr>
                                        <td class="product_qty mp_product_qty_1 product_price_top"><b>1</b>x <span
                                                class="price_qty price_qty_1"><?php echo get_field("izdelek_cena_1_kos"); ?></span><span
                                                class="per_qty">/<?php echo $kolicina; ?></span></td>
                                        <td class="product_qty mp_product_qty_2 product_price_basic"><b>2</b>x <span
                                                class="price_qty price_qty_2"><?php echo get_field("izdelek_cena_2_kos"); ?></span><span
                                                class="per_qty">/<?php echo $kolicina; ?></span></td>
                                        <td class="product_qty mp_product_qty_3 product_price_basic"><b>3</b>x <span
                                                class="price_qty price_qty_3"><?php echo get_field("izdelek_cena_3_kos"); ?></span><span
                                                class="per_qty">/<?php echo $kolicina; ?></span></td>
                                    </tr>
                                </table>
								
								
								<!-- variacije -->
								
								<p style="text-align: center;     margin-top: 1.3rem !important;   margin-bottom: 0.7rem;">
									<?php echo $vartekst; ?> 1:
								</p>	
								
								<?php 
								
								//var_dump($available_variations[0]['variation_id']);
								//var_dump(  reset($available_variations[0]['attributes'])  );

								//var_dump($available_variations[1]['variation_id']);
								//var_dump(  reset($available_variations[1]['attributes'])  );
								
								?>
								
								<style>
									.specialS {
										margin-bottom:3px !important; 
										text-align: center  !important; 
										padding:8px 5px 5px 5px  !important; 
										height: 72px  !important; 
										display: inline-block  !important; 
										width: 24%  !important;   
										margin-right: 1%  !important; 
									}
								</style>
									
									<?php 
									
									$islong = false;
						

									if(  count($available_variations) > 5 ) {
										$islong = true;
									}
									?>
									
									
								   <div id="izdelek1" class="bottom_table izdelek1 q_act">
									   <table>
										<tr>
										<?php $c = 0; ?>
											
											
											
											<?php foreach($available_variations as $vv): ?>
											
											<?php if(  $islong == true ): ?>
											
											<td style="width: 24%  !important;   " data-thisid="<?php echo $vv['variation_id']; ?>" class="specialS product_qty2 izbira1 product_price_basic <?php if( $c == 0){ echo 'act_variation';} ?>">
											
											 <span style="  line-height:1 !imporant;  font-size: 12.5px; text-align: center;"  class="price_qty1 color1"><?php echo reset($vv['attributes']) . ' - ' . end($vv['attributes'])  ; ?></span><span
													class="per_qty1"></span>
											
											
											</td>
											
											<?php else: ?>
											
										<td data-thisid="<?php echo $vv['variation_id']; ?>" class="product_qty2 izbira1 product_price_basic <?php if( $c == 0){ echo 'act_variation';} ?>">
											
												 <span style="  line-height:1 !imporant;  font-size: 12.5px; text-align: center;"  class="price_qty1 color1"><?php echo reset($vv['attributes']) . ' - ' . end($vv['attributes'])  ; ?></span><span
													class="per_qty1"></span>
											
											
											</td>
											
											<?php endif; ?>
											
											
											<?php $c = $c +1; ?>
											<?php endforeach; ?>
										</tr>
									</table>
								</div>
								
								<div id="izdelek2" style="display: none;" class="bottom_table izdelek2">
								
									<p style="text-align: center;     margin-top: 1.3rem !important;   margin-bottom: 0.7rem;">
									<?php echo $vartekst; ?> 2:
								</p>	
								
									   <table>
										<tr>
										<?php $c = 0; ?>
											
											<?php foreach($available_variations as $vv): ?>
											
											
												<?php if( $islong == true ): ?>
											
											<td  style="width: 24%  !important;   "  data-thisid="<?php echo $vv['variation_id']; ?>" class="specialS product_qty2 izbira1 product_price_basic <?php if( $c == 0){ echo 'act_variation';} ?>">
											
											 <span style="  line-height:1 !imporant;  font-size: 12.5px; text-align: center;"  class="price_qty1 color1"><?php echo reset($vv['attributes']) . ' - ' . end($vv['attributes'])  ; ?></span><span
													class="per_qty1"></span>
											
											
											</td>
											
											<?php else: ?>
											
											<td data-thisid="<?php echo $vv['variation_id']; ?>" class="product_qty2 izbira1 product_price_basic <?php if( $c == 0){ echo 'act_variation';} ?>">
											
											 <span class="price_qty1 color1"><?php echo reset($vv['attributes']) . ' - ' . end($vv['attributes'])  ; ?></span><span
													class="per_qty1"></span>
											
											</td>
													
											
											<?php endif; ?>
											
											

													
													
													
											<?php $c = $c +1; ?>
											
											
											
											<?php endforeach; ?>
										</tr>
									</table>
								</div>
								
								 <div  id="izdelek3" style="display: none;"  class="bottom_table izdelek3">
								 
								 	<p style="text-align: center;     margin-top: 1.3rem !important;   margin-bottom: 0.7rem;">
									<?php echo $vartekst; ?> 3:
								</p>	
									   <table>
										<tr>
										<?php $c = 0; ?>
											
											<?php foreach($available_variations as $vv): ?>
											
											
													<?php if( $islong == true ): ?>
											
											<td  style="width: 24%  !important;   " data-thisid="<?php echo $vv['variation_id']; ?>" class="specialS product_qty2 izbira1 product_price_basic <?php if( $c == 0){ echo 'act_variation';} ?>">
											
											 <span style="  line-height:1 !imporant;  font-size: 12.5px; text-align: center;"  class="price_qty1 color1"><?php echo reset($vv['attributes']) . ' - ' . end($vv['attributes'])  ; ?></span><span
													class="per_qty1"></span>
											
											
											</td>
											
											<?php else: ?>
											
											<td data-thisid="<?php echo $vv['variation_id']; ?>" class="product_qty2 izbira1 product_price_basic <?php if( $c == 0){ echo 'act_variation';} ?>">
											
											 <span class="price_qty1 color1"><?php echo reset($vv['attributes']) . ' - ' . end($vv['attributes'])  ; ?></span><span
													class="per_qty1"></span>
										</td>
													
											
											<?php endif; ?>
											
											
										
													
													
													
													
											<?php $c = $c +1; ?>
											<?php endforeach; ?>
										</tr>
									</table>
								</div>
								
								<style>
								.bottom_table td { width: auto !important; }
								
								.bottom_table table {table-layout: fixed; }
								    
								</style>
								
								<!-- varijacije -->
								
                            </div>
                            <div class="selects">

                                <span class="select is-large">
								
                  <select autocomplete="off" id="quantity_picker" name="quantity" onchange="qty_mp_push(this.value)"><option value="0" selected="selected"> </option><option value="1" selected="selected">1</option><option value="2">2</option><option value="3">3</option></select>              </span>
                            
							</div>
                            <div id="price-calculation">
                                <div class="full-price"></div>
                                <div class="discounted-price"></div>
                            </div>
                        </div>
                        <a style="background: <?php echo $dodaj_v_kosarico2; ?> !important;" id="order_link" href="<?php echo $domain; ?>/?add-to-cart=<?php echo $available_variations[0]['variation_id']; ?>" class="button is-primary submit mp_submit"><?php echo $dodaj_v_kosarico;  ?></a>
                        </form>

                        <div class="payment-methods">
                            <div class="payments">
                                <img src="<?php echo $payments_slike;  ?>">
                            </div>
                            <div class="sender hoverly hover-parent" data-pos="bottom" data-delivery="true" data-size="medium">
                                <div><img src="<?php echo $dostava;  ?>"></div>
                                <span><?php echo $dostava_tekst;  ?></span>
                            </div>
                        </div>
                  
				  <p class="pohitite"><?php echo $na_voljo_se; ?></p>
				  
				  
				  
				  <div id="getting-started"></div>
				  
				  <div id="getting-started2"> <span> <?php echo $dni; ?> </span><span> <?php echo $ur; ?> </span><span> <?php echo $min; ?> </span><span> <?php echo $sek; ?> </span></div>

						
						     
						
                    </div>
                </div>


        </div>
    </section>
            
        <?php elseif( get_row_layout() == 'sekcija-lastnosti' ): ?>
            
			<?php
            $naslov = get_sub_field('naslov');   //d($title);
			$text1 = get_sub_field('tekst_1');
			$text2 = get_sub_field('tekst_2');
			$text3 = get_sub_field('tekst_3');
			$text4 = get_sub_field('tekst_4');
			$text5 = get_sub_field('tekst_5');
			$text6 = get_sub_field('tekst_6');

			$color11 = get_sub_field('barva_ozadje');  
            ?>
            
            
       <section style="background: <?php echo $color11;  ?> !important;" class="section specifications">
        <div class="container">
            <div class="columns">
                <div class="column is-12 specs">
                    <h2 class="title is-2"><?php echo $naslov; ?></h2>

                    <div class="collapsible-wrapper">
                        <span class="collapsible-trigger" data-collapse=".specifications .collapsible"></span>
                    </div>
                </div>
            </div>
            <div  class="columns collapsible">
                <div class="column is-6 is-12-mobile">
                    <div>
                        <div class="columns spec">
                            <div class="column is-4 is-12-mobile">
                              
                            </div>
                            <div class="column is-8 is-12-mobile">
                                <span><?php echo $text1; ?>
</span>
                              </div>
                        </div>
                    </div>
                    <div>
                        <div class="columns spec">
                            <div class="column is-4 is-12-mobile intro">
                            
                            </div>
                            <div class="column is-8 is-12-mobile">
                                <span><?php echo $text2; ?>
</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="columns spec">
                            <div class="column is-4 is-12-mobile intro">
                            
                            </div>
                            <div class="column is-8 is-12-mobile">
                                <span><?php echo $text3; ?>
</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6 is-12-mobile">
                   <div>
                        <div class="columns spec">
                            <div class="column is-4 is-12-mobile intro">
                        
                            </div>
                            <div class="column is-8 is-12-mobile">
                                <span><?php echo $text4; ?>
</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="columns spec">
                            <div class="column is-4 is-12-mobile intro">

                            </div>
                            <div class="column is-8 is-12-mobile">
                                <span><?php echo $text5; ?></span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="columns spec">
                            <div class="column is-4 is-12-mobile intro">
                          
                            </div>
                            <div class="column is-8 is-12-mobile">
                                <span><?php echo $text6; ?>
</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
            
        <?php elseif( get_row_layout() == 'sekcija-infopasica' ): ?>
            
			<?php
            //$title = get_sub_field('title');   //d($title);
            ?>
            
           <section style="width: 100%; height: 30px; background: green;">
					
					<div class="container">
						<p>tekst</p>
					</div>
		   
		   </section>
            
        <?php elseif( get_row_layout() == 'sekcija-slike' ): ?>
            
			<?php
            $repeter2 = get_sub_field('slike');   //d($title);
			//var_dump($repeter2);
            ?>
            
            
    <section class="section gallery-section">
        <div class="container">
            <div class="columns slides slides-mobile-only carousel-1">
			
				<?php if($repeter2): ?>
								
									<?php foreach($repeter2 as $r): ?>
																		
									 <div class="column is-4">
                    <img src="<?php echo $r['slika']; ?>">
                </div>
									
									<?php endforeach; ?>
								<?php endif; ?>
			
			
               
            </div>
        </div>
    </section>
	
	
		<?php elseif( get_row_layout() == 'sekcija-pomoc' ): ?>
		
		
			<?php
            $naslov = get_sub_field('naslov'); 
			$teskt = get_sub_field('teskt'); 
			$slika_desno = get_sub_field('slika_desno'); 
			$desno_tekst = get_sub_field('desno_tekst'); 
            ?>
		
		    <section class="section help">
        <div class="container">
            <div class="columns">
                <div class="column is-7 is-12-mobile">
                    <h2><font size="6em"><?php echo $naslov; ?></font></h2>
                    <div>
                        <font size="4.5em"><?php echo $teskt; ?></font>
                    </div>
                </div>
                 <div class="column is-2 is-12-mobile">
                   <!--
				   <div class="icons">
                      <img src="/wp-content/themes/flatsome/landing-builder/img/phone_icon2.svg">

                    </div>
                    <a href="tel:224835299">+36 21 201 0214</a>
					-->
                </div>
                <div class="column is-3 is-12-mobile">
                    <div class="icons">
                        <img src="<?php echo $slika_desno; ?>">
                    </div>
                    <div>
                        <a class="mp_vigo_email" href="mailto:info@mistermega.eu"><?php echo $desno_tekst; ?></a></div>
                </div>
            </div>
        </div>
    </section>
            
        <?php elseif( get_row_layout() == 'sekcija-ocene-strank' ): ?>
            
			<?php
			$naslov = get_sub_field('naslov'); 
			
            $ime1 = get_sub_field('komentar_1_ime'); 
            $ime2 = get_sub_field('komentar_2_ime');
			$ime3 = get_sub_field('komentar_3_ime'); 
			$ime4 = get_sub_field('komentar_4_ime'); 
			$ime5 = get_sub_field('komentar_5_ime'); 
			$ime6 = get_sub_field('komentar_6_ime');  	

			$kom1 = get_sub_field('komentar_1_mnenje');
			$kom2 = get_sub_field('komentar_2_mnenje');  	
			$kom3 = get_sub_field('komentar_3_mnenje');  	
			$kom4 = get_sub_field('komentar_4_mnenje');  	
			$kom5 = get_sub_field('komentar_5_mnenje');  	
			$kom6 = get_sub_field('komentar_6_mnenje');  	  		

			$komentar_zvezdice_1 = get_sub_field('komentar_zvezdice_1');
			$komentar_zvezdice_2 = get_sub_field('komentar_zvezdice_2');  	
			$komentar_zvezdice_3 = get_sub_field('komentar_zvezdice_3');  	
			$komentar_zvezdice_4 = get_sub_field('komentar_zvezdice_4');
			$komentar_zvezdice_5 = get_sub_field('komentar_zvezdice_5');  	
			$komentar_zvezdice_6 = get_sub_field('komentar_zvezdice_6');  	
			
			$img1= get_sub_field('komentar_1_slika'); 
			$img2= get_sub_field('komentar_2_slika'); 
			$img3= get_sub_field('komentar_3_slika'); 
			$img4= get_sub_field('komentar_4_slika'); 
			$img5= get_sub_field('komentar_5_slika'); 
			$img6= get_sub_field('komentar_6_slika'); 

			$kom_ozadje = get_sub_field('barva_ozadja_komentar');  	
            ?>
            
              <section style="background: <?php echo $kom_ozadje; ?> !important;" class="section comments">
        <div class="container">
            <h2 style="padding-top: 20px;" class="title is-2"><?php echo $naslov; ?></h2>
            <div class="columns">
               <div class="column is-4 is-12-mobile">
                    <div class="fullcomment">
                      <div class="namecomment"><img src="<?php echo $komentar_zvezdice_1; ?>"><p><?php echo $ime1; ?>
</p> </div>
                        <div class="contentcomment"><p><?php echo $kom1; ?>
					</p>					<img src="<?php echo $img1; ?>">
                        </div>                      
                    </div>
                    <div>
                       <div class="fullcomment">
                      <div class="namecomment"><img src="<?php echo $komentar_zvezdice_2; ?>"><p><?php echo $ime2; ?>
</p> </div>
                        <div class="contentcomment"><p><?php echo $kom2; ?> 
</p><img src="<?php echo $img2; ?>">
                        </div>                      
                    </div>
                    </div>            
                    
                </div>
                <div class="column is-4 is-12-mobile">
                        <div class="fullcomment">
                      <div class="namecomment"><img src="<?php echo $komentar_zvezdice_3; ?>"><p><?php echo $ime3; ?>
</p> </div>
                        <div class="contentcomment"><p><?php echo $kom3; ?>
						</p><img src="<?php echo $img3; ?>">
                        </div>                      
                    </div>
                    <div>
                        <div class="fullcomment">
                      <div class="namecomment"><img src="<?php echo $komentar_zvezdice_4; ?>"><p><?php echo $ime4; ?>
</p> </div>
                        <div class="contentcomment"><p><?php echo $kom4; ?>
</p><img src="<?php echo $img4; ?>">
                        </div>                      
                    </div>
                    </div>
                   
                </div>
                 <div class="column is-4 is-12-mobile">
                  <div>
                    <div class="is-hidden-mobile">
                       <div class="fullcomment">
                      <div class="namecomment"><img src="<?php echo $komentar_zvezdice_5; ?>"><p><?php echo $ime5; ?>
</p> </div>
                        <div class="contentcomment"><p><?php echo $kom5; ?></p>
						<img src="<?php echo $img5; ?>">
                        </div>                      
                    </div>
                  </div>
                    </div>
                    
                    
                     <div class="is-hidden-mobile2">
                       <div class="fullcomment">
                      <div class="namecomment"><img src="<?php echo $komentar_zvezdice_6; ?>"><p><?php echo $ime6; ?>
</p> </div>
                        <div class="contentcomment"><p><?php echo $kom6; ?>
</p>				<img src="<?php echo $img6; ?>">
                        </div>                      
                    </div>
                    </div>
                </div>
                
                <!--
                <div class="is-hidden-mobile">
                  <div class="fullcomment">
                 <div class="namecomment"><img src="/wp-content/themes/flatsome/landing-builder/img/5_zvezdice.svg"><p>Hxxxxx J.</p> </div>
                   <div class="contentcomment"><p>Jestem bardzo zadowolona</p>
                   </div>                      
               </div>
               </div>
             -->
           </div>
                
            </div>
        </div>
    </section>
            
            
            
          
        <?php
            endif;
         endwhile;
     endif;
   ?>


<!-- end modularna vsebina -->
  
   

	
	
    <!--START sticky bar-->
<style>
    @media only screen and (max-width: 520px) {
        section.ssatc-sticky-add-to-cart.animated {
            display: none;
        }

        span.price {
            color: #fff !important;
        }

        section.custom-sticky-add-to-cart {
            display: block !important;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 999999;
            padding: 10px 0px 10px 10px;
            overflow: hidden;
            zoom: 1;
            background-color: #3e2823;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .custom-sticky-add-to-cart .price .woocommerce-Price-amount {
            color: #fff;
        }


        .custom-sticky-add-to-cart ins span.woocommerce-Price-amount.amount::after {
            display: none;
        }


        .custom-sticky-add-to-cart .livechat_button {
            display: inline;
            width: 14%;
            min-width: 40px;
        }

        .custom-sticky-add-to-cart .ssatc-content {
            display: inline-block;
            width: 86%;
        }

        .custom-sticky-add-to-cart .ssatc-content .price {
            text-align: center;
            margin-left: 25px;
        }

        .custom-sticky-add-to-cart .ssatc-content .button.alt {
            float: right;
        }

        .custom-sticky-add-to-cart .livechat_button img {
            width: 40px;
            float: left;
        }

        .button.alt {
            background-color: #c69955 !important;
            border-color: #c69955;
            color: #fff;
            width:110px;
        }

        ins {
            text-decoration: none !important;
        }

        ins span.woocommerce-Price-amount.amount {
            font-weight: 600;
            font-size: 1.7em;
        }

        del span.woocommerce-Price-amount.amount {
            opacity: .5;
            font-weight: 400 !important;
            font-size: 1.1em;
            text-decoration: line-through;
        }

            .custom-sticky-add-to-cart .ssatc-content .price {
            text-align: center;
            margin-left: 10px;
            line-height: normal;
        }
    }
</style>

	<?php
	// foter fields
	$povezava_1 = get_field("povezava_1");
	$povezava_2 = get_field("povezava_2");
	$povezava_1_link = get_field("povezava_1_link");
	$povezava_2_link = get_field("povezava_2_link");
	
	$tekst_nad_sliko1 = get_field("tekst_nad_sliko 1");
	$tekst_nad_sliko2 = get_field("tekst_nad_sliko 2");

	$footer_slika_1 = get_field("footer_slika_1");
	$footer_slika_22 = get_field("footer_slika_2");
	$footer_slika__logo2 = get_field("footer_slika__logo 2");

	?>

	

    <footer style="display: block !important;" class="footer">
        <div class="container">
            <div class="columns">
                <div class="first">

                    <a href="<?php echo $povezava_1_link; ?>"><?php echo $povezava_1; ?></a>

                    <a href="<?php echo $povezava_2_link; ?>"><?php echo $povezava_2; ?></a>
                </div>
                <div class="second">
                    <div class="item">
                        <div><?php echo $tekst_nad_sliko1; ?></div>
                        <img src="<?php echo $footer_slika_1; ?>">
                    </div>
                    <div class="item">
                        <div><?php echo $tekst_nad_sliko2; ?></div>
                        <img src="<?php echo $footer_slika_22; ?>">
                    </div>
                </div>
            </div>
           <div class="is-hidden-mobile">
            <span class="logo">
                <img style="width: 100%; height: auto;" src="<?php echo $footer_slika__logo2; ?>" class="will-scroll" data-scroll=".section.top">
            </span>
           </div>
        </div>
    </footer>
     
     
      
        <!--
        <div class="buyer-notification">
            <div class="container">
                <div class="wrapper">
                    <div class="rows">
                        <span class="close">X</span>
                        <div class="split">
                            <div class="img-wrapper">
                                <div class="img"></div>
                            </div>
                            <div class="data">
                                <div class="row">
                                    <div>
                                        <div class="name">Marta</div>
                                        z <div class="place">Łódź</div>
                                    </div>
                                </div>
                                <div class="message">kupiła 2 zegarki</div>
                            </div>
                        </div>
                        <div class="product">
                             <span>Ceramiczna prostownica do włosów </span>
                            <strong>TOURMALINE</strong>
                        </div>
                    </div>

                </div>
            </div>
        </div>
		-->

	<?php
	// foter fields
	$mobilni_footer_cena_1 = get_field("mobilni_footer_cena_1");
	$mobilni_footer_cena_2 = get_field("mobilni_footer_cena_2");
	$mobilni_footer_tekst_kupi = get_field("mobilni_footer_tekst_kupi");
	$mobilni_footer_tekst_kupi_copy = get_field("mobilni_footer_tekst_kupi_copy"); // barva pasice
	$mobilni_footer_tekst_kupi_copy2 = get_field("mobilni_footer_tekst_kupi_copy2"); // barva gumba

	$cena_barva_1 = get_field("mobilni_footer_cena_1_barva");
	$cena_barva_2 = get_field("mobilni_footer_cena_2_barva");
	?>
		
		<section style="background: <?php echo $mobilni_footer_tekst_kupi_copy; ?> !important;" id="sticky_chat" class="custom-sticky-add-to-cart" style="display: none;">
    <div class="col-full">
        <div data-id="TTAVPOWtmnh" class="livechat_button"><a href="#"></a></div>
        <div class="ssatc-content">
            <span class="price">
                <span class="price">
                    <del>
                        <span style="color: <?php echo $cena_barva_1; ?> !important;" class="woocommerce-Price-amount amount"><?php echo $mobilni_footer_cena_1; ?>
                        </span>
                    </del>
                    <ins>
                        <span style="color: <?php echo $cena_barva_2; ?> !important;" class="woocommerce-Price-amount amount"><?php echo $mobilni_footer_cena_2; ?>
                        </span>
                    </ins>
                </span>
            </span>
            <button style="    margin-right: -30px; background: <?php echo $mobilni_footer_tekst_kupi_copy2; ?> !important;" class="button alt" onclick="submitproductform()"><strong><?php echo $mobilni_footer_tekst_kupi; ?></strong></button>
            <script>function submitproductform() {
                document.getElementById("order_link").click();
            }</script>
        </div>
    </div>
</section>
  <script  src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<script>
			
			//var basePrice = 21.90;
            //var prices = {"1":21.90,"2":35.80,"3":44.70};
	 //  $(document).ready(function() {
            var basePrice = jQuery('#price11').val();
            var prices = {"1":jQuery('#price11').val(),"2":jQuery('#price22').val(),"3":jQuery('#price22').val()};
			
			//console.log(jQuery('#price11').val());
		//	console.log(prices[1]);
			
            var quantitySelectName = 'quantity';
            var colorSelectName = 'colors';
            var currency = ' ' + jQuery('#valuta').val();
            var variations = {"Siwy":23617};
            var stats = {
                initialPeople: 70,
                initialStock: 14,
                initialSold: 60,
            };

            
            var buyers = [
            ];

            

            var dateValues = {
                text: 'Zamówisz dzisiaj, otrzymasz w ',
                days: ['niedzielę','poniedziałek','wtorek','środę','czwartek','piątek','sobotę']
            };

            function preparePersonText(person) {
                var text = 'kupił';

                if(person.sex === 'f') {
                    text+='a';
                }

                text+=': ';
                var numbers ={
                    1: '1x',
                    2: '2x',
                    3: '3x',
                    undefined: '1x'
                };

                person.q = 1;

                return text + numbers[person.q];
            }
			
	   //});
        </script>


	    
		  
<script  src="/hr/wp-content/themes/flatsome/landing-builder/slick.min.js" ></script>
     <script  src="/hr/wp-content/themes/flatsome/landing-builder/jquery.countdown.min.js" ></script>
	 
	 
	  
		<?php 
		$levo = get_field("slika_levo_slider");
	$desno = get_field("slika_desno_slider");
	?>


      <style>

			.reasons .slick-next {
					    top: 42% !important;
			}

			.reasons .slick-prev {
					top: 42% !important;
			}

			.slick-dots li.slick-active button:before {
    background: black;
}
		.slick-dots li button:before {

    border: 1px solid black;
  
}
	  
			.slick-next {
				
				background: url("<?php echo $desno; ?>");
				background-size: contain;
				background-repeat: no-repeat;
				right: 0 !important;
				
			}
			
			.slick-prev {
				
				background: url("<?php echo $levo; ?>");
				background-size: contain;
				background-repeat: no-repeat;
				left: 0 !important;
				
			}
	  
			.collapsible-trigger {
					    margin-top: -13px;
			}
			.slick-dots button {
				
				background: transparent !important;
			}
			
			
				@media only screen and (min-width: 901px) { 
			.deskto-up {
			        margin-top: 20px;
			}
			
			.intro2 {
			margin-top: 1rem !important;
    margin-bottom: -1.4rem;
			}
	  }

			@media only screen and (max-width: 900px) {
				  .collapsible {
					display: none;
				  }
				}

	@media only screen and (min-width: 901px) {
				  .collapsible {
					display: block;
				  }
				
.collapsible-trigger {
	display: none;
}
				}

	  </style>

        <script>
            $(document).ready(function() {
				
				//.collapsible-trigger {
					
				//	collapsible
					
				//}
				
				//
				
				$(".collapsible-trigger").click(function(){
				  $(".collapsible").slideToggle();
				});
				
				//order_link
				
				//nakupna
				
		
				
				
             
				$('.carousel-1').slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					mobileFirst: true,
					arrows: true,
					dots: true,
					responsive: [
						  {
								  breakpoint: 900,
								  settings: 'unslick'
						  }
					]
				  });
				  
				  $('.carousel-2').slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					mobileFirst: true,
					arrows: true,
					dots: true,
				
					
					});
					
					  $('.carousel-3').slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					mobileFirst: true,
					arrows: true,
					dots: true,
					responsive: [
						  {
								  breakpoint: 900,
								  settings: 'unslick'
						  }
					]
				  });
				  
				 
			 
			 
            });
        </script>



    
	<script type="text/javascript">
	
	//console.log("test");

var tomorrow = new Date();
tomorrow.setDate(tomorrow.getDate() + 1);

var n = tomorrow.getDate();
//console.log(n);

var m = (tomorrow.getMonth())+1;
//console.log(m);

var date1 = "2021/" + m + "/" + n;

	
  $("#getting-started").countdown( date1 , function(event) {
    $(this).html(
      event.strftime( ' <span> %D </span><span> %H </span><span> %M </span><span> %S </span>' )
    );
  });
</script>

<style>

#getting-started  {
	width: 100%;
	margin-top: 30px;
	
}

#getting-started span {
	background: #333333;
	color: #cccccc;
	padding: 20px 10px 20px 10px;
	
	border: 4px solid white;
	border-radius: 10px;
	
	font-size: 40px;
	font-wieght: block;
	text-align: center;
	width: 25%;
	display: inline-block;
	
}

.pohitite {
	    font-weight: 500;
    line-height: 1.3;
	font-size: 1.5rem;
	width: 100%;
	text-align: center;
	
	margin-top: 20px;
	margin-bottom: 0px;
}


#getting-started2 span {
	background: ##cccccc;
	color: black;
	padding: 10px 5px 10px 5px;
	
	border: 4px solid white;
	border-radius: 10px;
	
	font-size: 20px;
	font-wieght: block;
	text-align: center;
	width: 25%;
	display: inline-block;
	
}

#getting-started2  {
	width: 100%;
	margin-top: 0px;
	
}

.act_variation {
	    border: #f3ca66 solid 1.5px;
    width: 33%;
    box-shadow: 1px 1px 2px 0 rgba(0, 0, 0, 0.16);
    background-color: #fff6e1;
    cursor: pointer;
	
}

</style>



<script>
    $(document).ready(function () {
		
		//console.log("p: " + prices[1]);

		setTimeout(function(){
	//	$( ".mp_product_qty_2" ).click();
		}, 130);

        $('.price_qty_1').text(prices[1] + currency);
        $('.price_qty_2').text((prices[1] * 0.8).toFixed(2) + currency);
        $('.price_qty_3').text((prices[1] * 0.7).toFixed(2) + currency);
        
		// <?php echo get_field("izdelek_cena_1_kos"); ?>
		// <?php echo get_field("izdelek_cena_1_kos"); ?>
		
          $('.full-price').text(jQuery('#pricefull').val() + currency);
		  
          $('.discounted-price').text( jQuery('#price11').val() + currency);
		  
		  
		       $('.custom_qty table .mp_product_qty_1 ').click(function (e) {
					
					$('.izdelek1').addClass("q_act");
					$('.izdelek2').removeClass("q_act");
					$('.izdelek3').removeClass("q_act");
					
					$('.izdelek1').show();
					$('.izdelek2').hide();
					$('.izdelek3').hide();
					
				});
				
				 $('.custom_qty table .mp_product_qty_2 ').click(function (e) {
					 
					$('.izdelek1').addClass("q_act");
					$('.izdelek2').addClass("q_act");
					$('.izdelek3').removeClass("q_act");
					 
					$('.izdelek1').show();
					$('.izdelek2').show();
					$('.izdelek3').hide();
				});
				
				 $('.custom_qty table .mp_product_qty_3 ').click(function (e) {
					 
					 
					$('.izdelek1').addClass("q_act");
					$('.izdelek2').addClass("q_act");
				    $('.izdelek3').addClass("q_act");
					 
						$('.izdelek1').show();
					$('.izdelek2').show();
					$('.izdelek3').show();
				});
		  
		     $('.custom_qty table .product_qty').click(function (e) {

   
		

			
            var $productPriceTop = $('.product_price_top');
            var $this = $(this);
            $productPriceTop.addClass('product_price_basic')
                .removeClass('product_price_top');

            $this.addClass('product_price_top')
                .removeClass('product_price_basic');

            var qty = $this.children('b').text();

            //set price and qty
            var new_price = $('.product_price_top .price_qty').text();
           
		   var _href = $("#order_link").attr("href").split('&');
			
            $("#order_link").attr("href", _href[0] + '&quantity='+ qty);
			
            $('.full-price').text((basePrice * qty).toFixed(2) + currency);
            $('.discounted-price').text((new_price.substring(0,new_price.length - currency.length) * qty).toFixed(2) + currency);
        });

	// product_qty2 izbira1 product_price_basic

function getQueryVariable(url, variable) {
  	 var query = url.substring(1);
     var vars = query.split('&');
     for (var i=0; i<vars.length; i++) {
          var pair = vars[i].split('=');
          if (pair[0] == variable) {
            return pair[1];
          }
     }

     return false;
  }
  
  
  	$('#order_link' ).click(function (e) {
		e.preventDefault();
		//console.log("custom add to cart");
		
		var urlmy = $("#order_link").attr("href");
		
		//console.log(urlmy);
		
		//console.log("start1");
		
		
		
		if( $(".izdelek1").hasClass("q_act") ) {
			
			console.log("dodaj v kosarico prvega");
			
			var active_id =  $(".izdelek1 .act_variation").data("thisid");
			console.log(active_id);
			
				var base =  "?add-to-cart=" + active_id + "&quantity=1";
				console.log(base);
			
				$.ajax({url: base, async: false, success: function(result){

			  }});
			
		}
		

		
		if( $(".izdelek2").hasClass("q_act") ) {
			
			//console.log("dodaj v kosarico drugega");
			
			var active_id =  $(".izdelek2 .act_variation").data("thisid");
			//console.log(active_id);
			
			var base =  "?add-to-cart=" + active_id + "&quantity=1";
			
				$.ajax({url: base, async: false,  success: function(result){

			  }});
			
		}
		
		//console.log("after call 2");
		if( $(".izdelek3").hasClass("q_act") ) {
			
			//console.log("dodaj v kosarico tretjega");
			
						var active_id =  $(".izdelek3 .act_variation").data("thisid");
			//console.log(active_id);
			
			//var base = jQuery('#basee').val();
			var base =  "?add-to-cart=" + active_id + "&quantity=1";
			
				$.ajax({url: base,   async: false, success: function(result){
				
			  }});
			
		}
		//console.log("after call 3");
		
		// redirect
		
		var base2 = jQuery('#basee').val();
		//console.log(base2);
		window.location.href = base2;

				  

		
			  
		
	});

		$('.product_qty2' ).click(function (e) { 
				
				
				console.log("klik zbira");

				var new_href = $("#order_link").attr("href" );
				//console.log(new_href);
				
                var qqq = getQueryVariable(new_href, 'quantity');
	
				if( qqq == false || qqq == null ) {
					qqq = 1;
				}
				//console.log(qqq);
			
			
			
				var base = jQuery('#basee').val();
				
				var this_option = $(this).data("thisid") 

				
				base = base + "/?add-to-cart=" + this_option + "&quantity=" + qqq;
				
				
				console.log( $(this) );
				 console.warn( $(this).parent().parent().parent().parent().attr('id') );
				 
				 
			var string2 = " #" + $(this).parent().parent().parent().parent().attr('id') + " .product_qty2";
				 
				// console.log(string2);
		

			 console.log("remo2 " + string2);

			 
			$(  string2 ).removeClass("act_variation");

			$(this).addClass("act_variation");
			  
			  	 
			 $("#order_link").attr("href", base);
		});


    });
</script>



<?php get_footer(); ?>
