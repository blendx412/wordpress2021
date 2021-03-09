<?php
/**
 * Template Name: 01. Landing Dizajn 1
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

elseif( $domain2 ==  "cartflip.cz" ) {
	
	$domain = "https://" . "cartflip.cz". "/ukoncit-nakup";
}
elseif( $domain2 ==  "cartflip.hu" ) {
	
	$domain = "https://" . "cartflip.hu". "/vasarlas-befejezese";
}
elseif( $domain2 ==  "cartflip.pl" ) {
	
	$domain = "https://" . "cartflip.pl" . "/zakonczenie-zakupu";
}
elseif( $domain2 ==  "cartflip.sk" ) {
	
	$domain = "https://" . "cartflip.sk". "/dokoncite-nakup";
}
elseif( $domain2 ==  "cartflip.eu" ) {
	
	$domain = "https://" . "cartflip.eu". "/ukoncit-nakup";
}
elseif( $domain2 ==  "cartflip.si" ) {
	
	$domain = "https://" . "cartflip.si". "/zakljucek-nakupa";
}

$domain = "https://" . "mrmaks.eu/hr". "/ukoncit-nakup";


/*
dobimo tukaj vse basic stvari:
*/
$izdelek = get_field('podatki_o_izdelku_copy');  

// check produty type
$_product = wc_get_product( $izdelek );

$product_type =  $_product->get_type();

    if ($_product->is_type('variable' )) {
        	$available_variations = $_product->get_available_variations();
			$available_variations = array_reverse($available_variations);
			
			if( count($available_variations[0]['attributes']) == 1 ) {
				$product_type = "enojne";
			}
			else {
				$product_type = "dvojne";
			}
	}

$cena1 = $_product->get_price();
$cena2 = $_product->get_price() - round($_product->get_price()*0.2, 2);
$cena3 = $_product->get_price() - round($_product->get_price()*0.3, 2);

$skupaj1 = $cena1;
$skupaj2 = $cena1 * 2;
$skupaj3 = $cena1 * 3;


$regular_price = $_product->get_regular_price();
$sale_price = $_product->get_sale_price();
    if ($_product->is_type('variable' )) {
        $variations = $_product->get_available_variations();
        $regular_price = $variations[0]['display_regular_price'];
        $sale_price = $variations[0]['display_price'];
    }

$currency_symbol = get_woocommerce_currency_symbol();
$valuta = $currency_symbol;

$levo = "https://mistermega.si/wp-content/uploads/2020/01/prev2.png";
$desno = "https://mistermega.si/wp-content/uploads/2020/01/next2.png";


/*
dobimo tukaj vse basic stvari:
*/

$polna_cena = false;
if( $domain2 == "MISTERMEGA.SI" || $domain2 == "mistermega.si" || $domain2 == "cartflip.si" || $domain2 == "CARTFLIP.SI" ) { 
	$polna_cena = true;
}
?>
 
 <?php if ( $polna_cena == true ): ?>
 <style>
     .full-price {
         display: none !important;
     }
 </style>
<?php endif; ?>
		

	<?php 
	// header fields
	$pasica = get_field("zgornja_info_pasica");// var_dump($pasica);
	$pasica_tekst = get_field("pasica_tekst");
	$pasica_barva = get_field("pasica_barva");
	$pasica_barva_tekst = get_field("pasica_barva_tekst");

	$logo = "https://mrmaks.eu/hr/wp-content/uploads/2020/12/mrmaks.png";
	$header_main_color = get_field("header_main_color");
	$middle_header = get_field("middle_header");
	?>
	
	
		<input style="display: none;" id="price11" value="<?php echo $cena1; ?>">
		<input style="display: none;" id="price22" value="<?php echo $cena2; ?>">
		<input style="display: none;" id="price33" value="<?php echo $cena3; ?>">
        <input style="display: none;" id="pricefull" value="<?php echo $regular_price; ?>">
		<input style="display: none;" id="valuta" value="<?php echo $valuta; ?>">	
		 <input style="display: none;" id="basee" value="<?php echo $domain; ?>">
	

<style>
	.fa,.fab,.fad,.fal,.far,.fas{-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;display:inline-block;font-style:normal;font-variant:normal;text-rendering:auto;line-height:1}.fa-lg{font-size:1.33333em;line-height:.75em;vertical-align:-.0667em}.fa-xs{font-size:.75em}.fa-sm{font-size:.875em}.fa-1x{font-size:1em}.fa-2x{font-size:2em}.fa-3x{font-size:3em}.fa-4x{font-size:4em}.fa-5x{font-size:5em}.fa-6x{font-size:6em}.fa-7x{font-size:7em}.fa-8x{font-size:8em}.fa-9x{font-size:9em}.fa-10x{font-size:10em}.fa-fw{text-align:center;width:1.25em}.fa-ul{list-style-type:none;margin-left:2.5em;padding-left:0}.fa-ul>li{position:relative}.fa-li{left:-2em;position:absolute;text-align:center;width:2em;line-height:inherit}.fa-border{border:.08em solid #eee;border-radius:.1em;padding:.2em .25em .15em}.fa-pull-left{float:left}.fa-pull-right{float:right}.fa.fa-pull-left,.fab.fa-pull-left,.fal.fa-pull-left,.far.fa-pull-left,.fas.fa-pull-left{margin-right:.3em}.fa.fa-pull-right,.fab.fa-pull-right,.fal.fa-pull-right,.far.fa-pull-right,.fas.fa-pull-right{margin-left:.3em}.fa-spin{-webkit-animation:fa-spin 2s linear infinite;animation:fa-spin 2s linear infinite}.fa-pulse{-webkit-animation:fa-spin 1s steps(8) infinite;animation:fa-spin 1s steps(8) infinite}@-webkit-keyframes fa-spin{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}to{-webkit-transform:rotate(1turn);transform:rotate(1turn)}}@keyframes fa-spin{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}to{-webkit-transform:rotate(1turn);transform:rotate(1turn)}}.fa-rotate-90{-ms-filter:"progid:DXImageTransform.Microsoft.BasicImage(rotation=1)";-webkit-transform:rotate(90deg);transform:rotate(90deg)}.fa-rotate-180{-ms-filter:"progid:DXImageTransform.Microsoft.BasicImage(rotation=2)";-webkit-transform:rotate(180deg);transform:rotate(180deg)}.fa-rotate-270{-ms-filter:"progid:DXImageTransform.Microsoft.BasicImage(rotation=3)";-webkit-transform:rotate(270deg);transform:rotate(270deg)}.fa-flip-horizontal{-ms-filter:"progid:DXImageTransform.Microsoft.BasicImage(rotation=0, mirror=1)";-webkit-transform:scaleX(-1);transform:scaleX(-1)}.fa-flip-vertical{-webkit-transform:scaleY(-1);transform:scaleY(-1)}.fa-flip-both,.fa-flip-horizontal.fa-flip-vertical,.fa-flip-vertical{-ms-filter:"progid:DXImageTransform.Microsoft.BasicImage(rotation=2, mirror=1)"}.fa-flip-both,.fa-flip-horizontal.fa-flip-vertical{-webkit-transform:scale(-1);transform:scale(-1)}:root .fa-flip-both,:root .fa-flip-horizontal,:root .fa-flip-vertical,:root .fa-rotate-90,:root .fa-rotate-180,:root .fa-rotate-270{-webkit-filter:none;filter:none}.fa-stack{display:inline-block;height:2em;line-height:2em;position:relative;vertical-align:middle;width:2.5em}.fa-stack-1x,.fa-stack-2x{left:0;position:absolute;text-align:center;width:100%}.fa-stack-1x{line-height:inherit}.fa-stack-2x{font-size:2em}.fa-inverse{color:#fff}.fa-500px:before{content:"\f26e"}.fa-accessible-icon:before{content:"\f368"}.fa-accusoft:before{content:"\f369"}.fa-acquisitions-incorporated:before{content:"\f6af"}.fa-ad:before{content:"\f641"}.fa-address-book:before{content:"\f2b9"}.fa-address-card:before{content:"\f2bb"}.fa-adjust:before{content:"\f042"}.fa-adn:before{content:"\f170"}.fa-adobe:before{content:"\f778"}.fa-adversal:before{content:"\f36a"}.fa-affiliatetheme:before{content:"\f36b"}.fa-air-freshener:before{content:"\f5d0"}.fa-airbnb:before{content:"\f834"}.fa-algolia:before{content:"\f36c"}.fa-align-center:before{content:"\f037"}.fa-align-justify:before{content:"\f039"}.fa-align-left:before{content:"\f036"}.fa-align-right:before{content:"\f038"}.fa-alipay:before{content:"\f642"}.fa-allergies:before{content:"\f461"}.fa-amazon:before{content:"\f270"}.fa-amazon-pay:before{content:"\f42c"}.fa-ambulance:before{content:"\f0f9"}.fa-american-sign-language-interpreting:before{content:"\f2a3"}.fa-amilia:before{content:"\f36d"}.fa-anchor:before{content:"\f13d"}.fa-android:before{content:"\f17b"}.fa-angellist:before{content:"\f209"}.fa-angle-double-down:before{content:"\f103"}.fa-angle-double-left:before{content:"\f100"}.fa-angle-double-right:before{content:"\f101"}.fa-angle-double-up:before{content:"\f102"}.fa-angle-down:before{content:"\f107"}.fa-angle-left:before{content:"\f104"}.fa-angle-right:before{content:"\f105"}.fa-angle-up:before{content:"\f106"}.fa-angry:before{content:"\f556"}.fa-angrycreative:before{content:"\f36e"}.fa-angular:before{content:"\f420"}.fa-ankh:before{content:"\f644"}.fa-app-store:before{content:"\f36f"}.fa-app-store-ios:before{content:"\f370"}.fa-apper:before{content:"\f371"}.fa-apple:before{content:"\f179"}.fa-apple-alt:before{content:"\f5d1"}.fa-apple-pay:before{content:"\f415"}.fa-archive:before{content:"\f187"}.fa-archway:before{content:"\f557"}.fa-arrow-alt-circle-down:before{content:"\f358"}.fa-arrow-alt-circle-left:before{content:"\f359"}.fa-arrow-alt-circle-right:before{content:"\f35a"}.fa-arrow-alt-circle-up:before{content:"\f35b"}.fa-arrow-circle-down:before{content:"\f0ab"}.fa-arrow-circle-left:before{content:"\f0a8"}.fa-arrow-circle-right:before{content:"\f0a9"}.fa-arrow-circle-up:before{content:"\f0aa"}.fa-arrow-down:before{content:"\f063"}.fa-arrow-left:before{content:"\f060"}.fa-arrow-right:before{content:"\f061"}.fa-arrow-up:before{content:"\f062"}.fa-arrows-alt:before{content:"\f0b2"}.fa-arrows-alt-h:before{content:"\f337"}.fa-arrows-alt-v:before{content:"\f338"}.fa-artstation:before{content:"\f77a"}.fa-assistive-listening-systems:before{content:"\f2a2"}.fa-asterisk:before{content:"\f069"}.fa-asymmetrik:before{content:"\f372"}.fa-at:before{content:"\f1fa"}.fa-atlas:before{content:"\f558"}.fa-atlassian:before{content:"\f77b"}.fa-atom:before{content:"\f5d2"}.fa-audible:before{content:"\f373"}.fa-audio-description:before{content:"\f29e"}.fa-autoprefixer:before{content:"\f41c"}.fa-avianex:before{content:"\f374"}.fa-aviato:before{content:"\f421"}.fa-award:before{content:"\f559"}.fa-aws:before{content:"\f375"}.fa-baby:before{content:"\f77c"}.fa-baby-carriage:before{content:"\f77d"}.fa-backspace:before{content:"\f55a"}.fa-backward:before{content:"\f04a"}.fa-bacon:before{content:"\f7e5"}.fa-bahai:before{content:"\f666"}.fa-balance-scale:before{content:"\f24e"}.fa-balance-scale-left:before{content:"\f515"}.fa-balance-scale-right:before{content:"\f516"}.fa-ban:before{content:"\f05e"}.fa-band-aid:before{content:"\f462"}.fa-bandcamp:before{content:"\f2d5"}.fa-barcode:before{content:"\f02a"}.fa-bars:before{content:"\f0c9"}.fa-baseball-ball:before{content:"\f433"}.fa-basketball-ball:before{content:"\f434"}.fa-bath:before{content:"\f2cd"}.fa-battery-empty:before{content:"\f244"}.fa-battery-full:before{content:"\f240"}.fa-battery-half:before{content:"\f242"}.fa-battery-quarter:before{content:"\f243"}.fa-battery-three-quarters:before{content:"\f241"}.fa-battle-net:before{content:"\f835"}.fa-bed:before{content:"\f236"}.fa-beer:before{content:"\f0fc"}.fa-behance:before{content:"\f1b4"}.fa-behance-square:before{content:"\f1b5"}.fa-bell:before{content:"\f0f3"}.fa-bell-slash:before{content:"\f1f6"}.fa-bezier-curve:before{content:"\f55b"}.fa-bible:before{content:"\f647"}.fa-bicycle:before{content:"\f206"}.fa-biking:before{content:"\f84a"}.fa-bimobject:before{content:"\f378"}.fa-binoculars:before{content:"\f1e5"}.fa-biohazard:before{content:"\f780"}.fa-birthday-cake:before{content:"\f1fd"}.fa-bitbucket:before{content:"\f171"}.fa-bitcoin:before{content:"\f379"}.fa-bity:before{content:"\f37a"}.fa-black-tie:before{content:"\f27e"}.fa-blackberry:before{content:"\f37b"}.fa-blender:before{content:"\f517"}.fa-blender-phone:before{content:"\f6b6"}.fa-blind:before{content:"\f29d"}.fa-blog:before{content:"\f781"}.fa-blogger:before{content:"\f37c"}.fa-blogger-b:before{content:"\f37d"}.fa-bluetooth:before{content:"\f293"}.fa-bluetooth-b:before{content:"\f294"}.fa-bold:before{content:"\f032"}.fa-bolt:before{content:"\f0e7"}.fa-bomb:before{content:"\f1e2"}.fa-bone:before{content:"\f5d7"}.fa-bong:before{content:"\f55c"}.fa-book:before{content:"\f02d"}.fa-book-dead:before{content:"\f6b7"}.fa-book-medical:before{content:"\f7e6"}.fa-book-open:before{content:"\f518"}.fa-book-reader:before{content:"\f5da"}.fa-bookmark:before{content:"\f02e"}.fa-bootstrap:before{content:"\f836"}.fa-border-all:before{content:"\f84c"}.fa-border-none:before{content:"\f850"}.fa-border-style:before{content:"\f853"}.fa-bowling-ball:before{content:"\f436"}.fa-box:before{content:"\f466"}.fa-box-open:before{content:"\f49e"}.fa-box-tissue:before{content:"\f95b"}.fa-boxes:before{content:"\f468"}.fa-braille:before{content:"\f2a1"}.fa-brain:before{content:"\f5dc"}.fa-bread-slice:before{content:"\f7ec"}.fa-briefcase:before{content:"\f0b1"}.fa-briefcase-medical:before{content:"\f469"}.fa-broadcast-tower:before{content:"\f519"}.fa-broom:before{content:"\f51a"}.fa-brush:before{content:"\f55d"}.fa-btc:before{content:"\f15a"}.fa-buffer:before{content:"\f837"}.fa-bug:before{content:"\f188"}.fa-building:before{content:"\f1ad"}.fa-bullhorn:before{content:"\f0a1"}.fa-bullseye:before{content:"\f140"}.fa-burn:before{content:"\f46a"}.fa-buromobelexperte:before{content:"\f37f"}.fa-bus:before{content:"\f207"}.fa-bus-alt:before{content:"\f55e"}.fa-business-time:before{content:"\f64a"}.fa-buy-n-large:before{content:"\f8a6"}.fa-buysellads:before{content:"\f20d"}.fa-calculator:before{content:"\f1ec"}.fa-calendar:before{content:"\f133"}.fa-calendar-alt:before{content:"\f073"}.fa-calendar-check:before{content:"\f274"}.fa-calendar-day:before{content:"\f783"}.fa-calendar-minus:before{content:"\f272"}.fa-calendar-plus:before{content:"\f271"}.fa-calendar-times:before{content:"\f273"}.fa-calendar-week:before{content:"\f784"}.fa-camera:before{content:"\f030"}.fa-camera-retro:before{content:"\f083"}.fa-campground:before{content:"\f6bb"}.fa-canadian-maple-leaf:before{content:"\f785"}.fa-candy-cane:before{content:"\f786"}.fa-cannabis:before{content:"\f55f"}.fa-capsules:before{content:"\f46b"}.fa-car:before{content:"\f1b9"}.fa-car-alt:before{content:"\f5de"}.fa-car-battery:before{content:"\f5df"}.fa-car-crash:before{content:"\f5e1"}.fa-car-side:before{content:"\f5e4"}.fa-caravan:before{content:"\f8ff"}.fa-caret-down:before{content:"\f0d7"}.fa-caret-left:before{content:"\f0d9"}.fa-caret-right:before{content:"\f0da"}.fa-caret-square-down:before{content:"\f150"}.fa-caret-square-left:before{content:"\f191"}.fa-caret-square-right:before{content:"\f152"}.fa-caret-square-up:before{content:"\f151"}.fa-caret-up:before{content:"\f0d8"}.fa-carrot:before{content:"\f787"}.fa-cart-arrow-down:before{content:"\f218"}.fa-cart-plus:before{content:"\f217"}.fa-cash-register:before{content:"\f788"}.fa-cat:before{content:"\f6be"}.fa-cc-amazon-pay:before{content:"\f42d"}.fa-cc-amex:before{content:"\f1f3"}.fa-cc-apple-pay:before{content:"\f416"}.fa-cc-diners-club:before{content:"\f24c"}.fa-cc-discover:before{content:"\f1f2"}.fa-cc-jcb:before{content:"\f24b"}.fa-cc-mastercard:before{content:"\f1f1"}.fa-cc-paypal:before{content:"\f1f4"}.fa-cc-stripe:before{content:"\f1f5"}.fa-cc-visa:before{content:"\f1f0"}.fa-centercode:before{content:"\f380"}.fa-centos:before{content:"\f789"}.fa-certificate:before{content:"\f0a3"}.fa-chair:before{content:"\f6c0"}.fa-chalkboard:before{content:"\f51b"}.fa-chalkboard-teacher:before{content:"\f51c"}.fa-charging-station:before{content:"\f5e7"}.fa-chart-area:before{content:"\f1fe"}.fa-chart-bar:before{content:"\f080"}.fa-chart-line:before{content:"\f201"}.fa-chart-pie:before{content:"\f200"}.fa-check:before{content:"\f00c"}.fa-check-circle:before{content:"\f058"}.fa-check-double:before{content:"\f560"}.fa-check-square:before{content:"\f14a"}.fa-cheese:before{content:"\f7ef"}.fa-chess:before{content:"\f439"}.fa-chess-bishop:before{content:"\f43a"}.fa-chess-board:before{content:"\f43c"}.fa-chess-king:before{content:"\f43f"}.fa-chess-knight:before{content:"\f441"}.fa-chess-pawn:before{content:"\f443"}.fa-chess-queen:before{content:"\f445"}.fa-chess-rook:before{content:"\f447"}.fa-chevron-circle-down:before{content:"\f13a"}.fa-chevron-circle-left:before{content:"\f137"}.fa-chevron-circle-right:before{content:"\f138"}.fa-chevron-circle-up:before{content:"\f139"}.fa-chevron-down:before{content:"\f078"}.fa-chevron-left:before{content:"\f053"}.fa-chevron-right:before{content:"\f054"}.fa-chevron-up:before{content:"\f077"}.fa-child:before{content:"\f1ae"}.fa-chrome:before{content:"\f268"}.fa-chromecast:before{content:"\f838"}.fa-church:before{content:"\f51d"}.fa-circle:before{content:"\f111"}.fa-circle-notch:before{content:"\f1ce"}.fa-city:before{content:"\f64f"}.fa-clinic-medical:before{content:"\f7f2"}.fa-clipboard:before{content:"\f328"}.fa-clipboard-check:before{content:"\f46c"}.fa-clipboard-list:before{content:"\f46d"}.fa-clock:before{content:"\f017"}.fa-clone:before{content:"\f24d"}.fa-closed-captioning:before{content:"\f20a"}.fa-cloud:before{content:"\f0c2"}.fa-cloud-download-alt:before{content:"\f381"}.fa-cloud-meatball:before{content:"\f73b"}.fa-cloud-moon:before{content:"\f6c3"}.fa-cloud-moon-rain:before{content:"\f73c"}.fa-cloud-rain:before{content:"\f73d"}.fa-cloud-showers-heavy:before{content:"\f740"}.fa-cloud-sun:before{content:"\f6c4"}.fa-cloud-sun-rain:before{content:"\f743"}.fa-cloud-upload-alt:before{content:"\f382"}.fa-cloudscale:before{content:"\f383"}.fa-cloudsmith:before{content:"\f384"}.fa-cloudversify:before{content:"\f385"}.fa-cocktail:before{content:"\f561"}.fa-code:before{content:"\f121"}.fa-code-branch:before{content:"\f126"}.fa-codepen:before{content:"\f1cb"}.fa-codiepie:before{content:"\f284"}.fa-coffee:before{content:"\f0f4"}.fa-cog:before{content:"\f013"}.fa-cogs:before{content:"\f085"}.fa-coins:before{content:"\f51e"}.fa-columns:before{content:"\f0db"}.fa-comment:before{content:"\f075"}.fa-comment-alt:before{content:"\f27a"}.fa-comment-dollar:before{content:"\f651"}.fa-comment-dots:before{content:"\f4ad"}.fa-comment-medical:before{content:"\f7f5"}.fa-comment-slash:before{content:"\f4b3"}.fa-comments:before{content:"\f086"}.fa-comments-dollar:before{content:"\f653"}.fa-compact-disc:before{content:"\f51f"}.fa-compass:before{content:"\f14e"}.fa-compress:before{content:"\f066"}.fa-compress-alt:before{content:"\f422"}.fa-compress-arrows-alt:before{content:"\f78c"}.fa-concierge-bell:before{content:"\f562"}.fa-confluence:before{content:"\f78d"}.fa-connectdevelop:before{content:"\f20e"}.fa-contao:before{content:"\f26d"}.fa-cookie:before{content:"\f563"}.fa-cookie-bite:before{content:"\f564"}.fa-copy:before{content:"\f0c5"}.fa-copyright:before{content:"\f1f9"}.fa-cotton-bureau:before{content:"\f89e"}.fa-couch:before{content:"\f4b8"}.fa-cpanel:before{content:"\f388"}.fa-creative-commons:before{content:"\f25e"}.fa-creative-commons-by:before{content:"\f4e7"}.fa-creative-commons-nc:before{content:"\f4e8"}.fa-creative-commons-nc-eu:before{content:"\f4e9"}.fa-creative-commons-nc-jp:before{content:"\f4ea"}.fa-creative-commons-nd:before{content:"\f4eb"}.fa-creative-commons-pd:before{content:"\f4ec"}.fa-creative-commons-pd-alt:before{content:"\f4ed"}.fa-creative-commons-remix:before{content:"\f4ee"}.fa-creative-commons-sa:before{content:"\f4ef"}.fa-creative-commons-sampling:before{content:"\f4f0"}.fa-creative-commons-sampling-plus:before{content:"\f4f1"}.fa-creative-commons-share:before{content:"\f4f2"}.fa-creative-commons-zero:before{content:"\f4f3"}.fa-credit-card:before{content:"\f09d"}.fa-critical-role:before{content:"\f6c9"}.fa-crop:before{content:"\f125"}.fa-crop-alt:before{content:"\f565"}.fa-cross:before{content:"\f654"}.fa-crosshairs:before{content:"\f05b"}.fa-crow:before{content:"\f520"}.fa-crown:before{content:"\f521"}.fa-crutch:before{content:"\f7f7"}.fa-css3:before{content:"\f13c"}.fa-css3-alt:before{content:"\f38b"}.fa-cube:before{content:"\f1b2"}.fa-cubes:before{content:"\f1b3"}.fa-cut:before{content:"\f0c4"}.fa-cuttlefish:before{content:"\f38c"}.fa-d-and-d:before{content:"\f38d"}.fa-d-and-d-beyond:before{content:"\f6ca"}.fa-dailymotion:before{content:"\f952"}.fa-dashcube:before{content:"\f210"}.fa-database:before{content:"\f1c0"}.fa-deaf:before{content:"\f2a4"}.fa-delicious:before{content:"\f1a5"}.fa-democrat:before{content:"\f747"}.fa-deploydog:before{content:"\f38e"}.fa-deskpro:before{content:"\f38f"}.fa-desktop:before{content:"\f108"}.fa-dev:before{content:"\f6cc"}.fa-deviantart:before{content:"\f1bd"}.fa-dharmachakra:before{content:"\f655"}.fa-dhl:before{content:"\f790"}.fa-diagnoses:before{content:"\f470"}.fa-diaspora:before{content:"\f791"}.fa-dice:before{content:"\f522"}.fa-dice-d20:before{content:"\f6cf"}.fa-dice-d6:before{content:"\f6d1"}.fa-dice-five:before{content:"\f523"}.fa-dice-four:before{content:"\f524"}.fa-dice-one:before{content:"\f525"}.fa-dice-six:before{content:"\f526"}.fa-dice-three:before{content:"\f527"}.fa-dice-two:before{content:"\f528"}.fa-digg:before{content:"\f1a6"}.fa-digital-ocean:before{content:"\f391"}.fa-digital-tachograph:before{content:"\f566"}.fa-directions:before{content:"\f5eb"}.fa-discord:before{content:"\f392"}.fa-discourse:before{content:"\f393"}.fa-disease:before{content:"\f7fa"}.fa-divide:before{content:"\f529"}.fa-dizzy:before{content:"\f567"}.fa-dna:before{content:"\f471"}.fa-dochub:before{content:"\f394"}.fa-docker:before{content:"\f395"}.fa-dog:before{content:"\f6d3"}.fa-dollar-sign:before{content:"\f155"}.fa-dolly:before{content:"\f472"}.fa-dolly-flatbed:before{content:"\f474"}.fa-donate:before{content:"\f4b9"}.fa-door-closed:before{content:"\f52a"}.fa-door-open:before{content:"\f52b"}.fa-dot-circle:before{content:"\f192"}.fa-dove:before{content:"\f4ba"}.fa-download:before{content:"\f019"}.fa-draft2digital:before{content:"\f396"}.fa-drafting-compass:before{content:"\f568"}.fa-dragon:before{content:"\f6d5"}.fa-draw-polygon:before{content:"\f5ee"}.fa-dribbble:before{content:"\f17d"}.fa-dribbble-square:before{content:"\f397"}.fa-dropbox:before{content:"\f16b"}.fa-drum:before{content:"\f569"}.fa-drum-steelpan:before{content:"\f56a"}.fa-drumstick-bite:before{content:"\f6d7"}.fa-drupal:before{content:"\f1a9"}.fa-dumbbell:before{content:"\f44b"}.fa-dumpster:before{content:"\f793"}.fa-dumpster-fire:before{content:"\f794"}.fa-dungeon:before{content:"\f6d9"}.fa-dyalog:before{content:"\f399"}.fa-earlybirds:before{content:"\f39a"}.fa-ebay:before{content:"\f4f4"}.fa-edge:before{content:"\f282"}.fa-edit:before{content:"\f044"}.fa-egg:before{content:"\f7fb"}.fa-eject:before{content:"\f052"}.fa-elementor:before{content:"\f430"}.fa-ellipsis-h:before{content:"\f141"}.fa-ellipsis-v:before{content:"\f142"}.fa-ello:before{content:"\f5f1"}.fa-ember:before{content:"\f423"}.fa-empire:before{content:"\f1d1"}.fa-envelope:before{content:"\f0e0"}.fa-envelope-open:before{content:"\f2b6"}.fa-envelope-open-text:before{content:"\f658"}.fa-envelope-square:before{content:"\f199"}.fa-envira:before{content:"\f299"}.fa-equals:before{content:"\f52c"}.fa-eraser:before{content:"\f12d"}.fa-erlang:before{content:"\f39d"}.fa-ethereum:before{content:"\f42e"}.fa-ethernet:before{content:"\f796"}.fa-etsy:before{content:"\f2d7"}.fa-euro-sign:before{content:"\f153"}.fa-evernote:before{content:"\f839"}.fa-exchange-alt:before{content:"\f362"}.fa-exclamation:before{content:"\f12a"}.fa-exclamation-circle:before{content:"\f06a"}.fa-exclamation-triangle:before{content:"\f071"}.fa-expand:before{content:"\f065"}.fa-expand-alt:before{content:"\f424"}.fa-expand-arrows-alt:before{content:"\f31e"}.fa-expeditedssl:before{content:"\f23e"}.fa-external-link-alt:before{content:"\f35d"}.fa-external-link-square-alt:before{content:"\f360"}.fa-eye:before{content:"\f06e"}.fa-eye-dropper:before{content:"\f1fb"}.fa-eye-slash:before{content:"\f070"}.fa-facebook:before{content:"\f09a"}.fa-facebook-f:before{content:"\f39e"}.fa-facebook-messenger:before{content:"\f39f"}.fa-facebook-square:before{content:"\f082"}.fa-fan:before{content:"\f863"}.fa-fantasy-flight-games:before{content:"\f6dc"}.fa-fast-backward:before{content:"\f049"}.fa-fast-forward:before{content:"\f050"}.fa-faucet:before{content:"\f905"}.fa-fax:before{content:"\f1ac"}.fa-feather:before{content:"\f52d"}.fa-feather-alt:before{content:"\f56b"}.fa-fedex:before{content:"\f797"}.fa-fedora:before{content:"\f798"}.fa-female:before{content:"\f182"}.fa-fighter-jet:before{content:"\f0fb"}.fa-figma:before{content:"\f799"}.fa-file:before{content:"\f15b"}.fa-file-alt:before{content:"\f15c"}.fa-file-archive:before{content:"\f1c6"}.fa-file-audio:before{content:"\f1c7"}.fa-file-code:before{content:"\f1c9"}.fa-file-contract:before{content:"\f56c"}.fa-file-csv:before{content:"\f6dd"}.fa-file-download:before{content:"\f56d"}.fa-file-excel:before{content:"\f1c3"}.fa-file-export:before{content:"\f56e"}.fa-file-image:before{content:"\f1c5"}.fa-file-import:before{content:"\f56f"}.fa-file-invoice:before{content:"\f570"}.fa-file-invoice-dollar:before{content:"\f571"}.fa-file-medical:before{content:"\f477"}.fa-file-medical-alt:before{content:"\f478"}.fa-file-pdf:before{content:"\f1c1"}.fa-file-powerpoint:before{content:"\f1c4"}.fa-file-prescription:before{content:"\f572"}.fa-file-signature:before{content:"\f573"}.fa-file-upload:before{content:"\f574"}.fa-file-video:before{content:"\f1c8"}.fa-file-word:before{content:"\f1c2"}.fa-fill:before{content:"\f575"}.fa-fill-drip:before{content:"\f576"}.fa-film:before{content:"\f008"}.fa-filter:before{content:"\f0b0"}.fa-fingerprint:before{content:"\f577"}.fa-fire:before{content:"\f06d"}.fa-fire-alt:before{content:"\f7e4"}.fa-fire-extinguisher:before{content:"\f134"}.fa-firefox:before{content:"\f269"}.fa-firefox-browser:before{content:"\f907"}.fa-first-aid:before{content:"\f479"}.fa-first-order:before{content:"\f2b0"}.fa-first-order-alt:before{content:"\f50a"}.fa-firstdraft:before{content:"\f3a1"}.fa-fish:before{content:"\f578"}.fa-fist-raised:before{content:"\f6de"}.fa-flag:before{content:"\f024"}.fa-flag-checkered:before{content:"\f11e"}.fa-flag-usa:before{content:"\f74d"}.fa-flask:before{content:"\f0c3"}.fa-flickr:before{content:"\f16e"}.fa-flipboard:before{content:"\f44d"}.fa-flushed:before{content:"\f579"}.fa-fly:before{content:"\f417"}.fa-folder:before{content:"\f07b"}.fa-folder-minus:before{content:"\f65d"}.fa-folder-open:before{content:"\f07c"}.fa-folder-plus:before{content:"\f65e"}.fa-font:before{content:"\f031"}.fa-font-awesome:before{content:"\f2b4"}.fa-font-awesome-alt:before{content:"\f35c"}.fa-font-awesome-flag:before{content:"\f425"}.fa-font-awesome-logo-full:before{content:"\f4e6"}.fa-fonticons:before{content:"\f280"}.fa-fonticons-fi:before{content:"\f3a2"}.fa-football-ball:before{content:"\f44e"}.fa-fort-awesome:before{content:"\f286"}.fa-fort-awesome-alt:before{content:"\f3a3"}.fa-forumbee:before{content:"\f211"}.fa-forward:before{content:"\f04e"}.fa-foursquare:before{content:"\f180"}.fa-free-code-camp:before{content:"\f2c5"}.fa-freebsd:before{content:"\f3a4"}.fa-frog:before{content:"\f52e"}.fa-frown:before{content:"\f119"}.fa-frown-open:before{content:"\f57a"}.fa-fulcrum:before{content:"\f50b"}.fa-funnel-dollar:before{content:"\f662"}.fa-futbol:before{content:"\f1e3"}.fa-galactic-republic:before{content:"\f50c"}.fa-galactic-senate:before{content:"\f50d"}.fa-gamepad:before{content:"\f11b"}.fa-gas-pump:before{content:"\f52f"}.fa-gavel:before{content:"\f0e3"}.fa-gem:before{content:"\f3a5"}.fa-genderless:before{content:"\f22d"}.fa-get-pocket:before{content:"\f265"}.fa-gg:before{content:"\f260"}.fa-gg-circle:before{content:"\f261"}.fa-ghost:before{content:"\f6e2"}.fa-gift:before{content:"\f06b"}.fa-gifts:before{content:"\f79c"}.fa-git:before{content:"\f1d3"}.fa-git-alt:before{content:"\f841"}.fa-git-square:before{content:"\f1d2"}.fa-github:before{content:"\f09b"}.fa-github-alt:before{content:"\f113"}.fa-github-square:before{content:"\f092"}.fa-gitkraken:before{content:"\f3a6"}.fa-gitlab:before{content:"\f296"}.fa-gitter:before{content:"\f426"}.fa-glass-cheers:before{content:"\f79f"}.fa-glass-martini:before{content:"\f000"}.fa-glass-martini-alt:before{content:"\f57b"}.fa-glass-whiskey:before{content:"\f7a0"}.fa-glasses:before{content:"\f530"}.fa-glide:before{content:"\f2a5"}.fa-glide-g:before{content:"\f2a6"}.fa-globe:before{content:"\f0ac"}.fa-globe-africa:before{content:"\f57c"}.fa-globe-americas:before{content:"\f57d"}.fa-globe-asia:before{content:"\f57e"}.fa-globe-europe:before{content:"\f7a2"}.fa-gofore:before{content:"\f3a7"}.fa-golf-ball:before{content:"\f450"}.fa-goodreads:before{content:"\f3a8"}.fa-goodreads-g:before{content:"\f3a9"}.fa-google:before{content:"\f1a0"}.fa-google-drive:before{content:"\f3aa"}.fa-google-play:before{content:"\f3ab"}.fa-google-plus:before{content:"\f2b3"}.fa-google-plus-g:before{content:"\f0d5"}.fa-google-plus-square:before{content:"\f0d4"}.fa-google-wallet:before{content:"\f1ee"}.fa-gopuram:before{content:"\f664"}.fa-graduation-cap:before{content:"\f19d"}.fa-gratipay:before{content:"\f184"}.fa-grav:before{content:"\f2d6"}.fa-greater-than:before{content:"\f531"}.fa-greater-than-equal:before{content:"\f532"}.fa-grimace:before{content:"\f57f"}.fa-grin:before{content:"\f580"}.fa-grin-alt:before{content:"\f581"}.fa-grin-beam:before{content:"\f582"}.fa-grin-beam-sweat:before{content:"\f583"}.fa-grin-hearts:before{content:"\f584"}.fa-grin-squint:before{content:"\f585"}.fa-grin-squint-tears:before{content:"\f586"}.fa-grin-stars:before{content:"\f587"}.fa-grin-tears:before{content:"\f588"}.fa-grin-tongue:before{content:"\f589"}.fa-grin-tongue-squint:before{content:"\f58a"}.fa-grin-tongue-wink:before{content:"\f58b"}.fa-grin-wink:before{content:"\f58c"}.fa-grip-horizontal:before{content:"\f58d"}.fa-grip-lines:before{content:"\f7a4"}.fa-grip-lines-vertical:before{content:"\f7a5"}.fa-grip-vertical:before{content:"\f58e"}.fa-gripfire:before{content:"\f3ac"}.fa-grunt:before{content:"\f3ad"}.fa-guitar:before{content:"\f7a6"}.fa-gulp:before{content:"\f3ae"}.fa-h-square:before{content:"\f0fd"}.fa-hacker-news:before{content:"\f1d4"}.fa-hacker-news-square:before{content:"\f3af"}.fa-hackerrank:before{content:"\f5f7"}.fa-hamburger:before{content:"\f805"}.fa-hammer:before{content:"\f6e3"}.fa-hamsa:before{content:"\f665"}.fa-hand-holding:before{content:"\f4bd"}.fa-hand-holding-heart:before{content:"\f4be"}.fa-hand-holding-medical:before{content:"\f95c"}.fa-hand-holding-usd:before{content:"\f4c0"}.fa-hand-holding-water:before{content:"\f4c1"}.fa-hand-lizard:before{content:"\f258"}.fa-hand-middle-finger:before{content:"\f806"}.fa-hand-paper:before{content:"\f256"}.fa-hand-peace:before{content:"\f25b"}.fa-hand-point-down:before{content:"\f0a7"}.fa-hand-point-left:before{content:"\f0a5"}.fa-hand-point-right:before{content:"\f0a4"}.fa-hand-point-up:before{content:"\f0a6"}.fa-hand-pointer:before{content:"\f25a"}.fa-hand-rock:before{content:"\f255"}.fa-hand-scissors:before{content:"\f257"}.fa-hand-sparkles:before{content:"\f95d"}.fa-hand-spock:before{content:"\f259"}.fa-hands:before{content:"\f4c2"}.fa-hands-helping:before{content:"\f4c4"}.fa-hands-wash:before{content:"\f95e"}.fa-handshake:before{content:"\f2b5"}.fa-handshake-alt-slash:before{content:"\f95f"}.fa-handshake-slash:before{content:"\f960"}.fa-hanukiah:before{content:"\f6e6"}.fa-hard-hat:before{content:"\f807"}.fa-hashtag:before{content:"\f292"}.fa-hat-cowboy:before{content:"\f8c0"}.fa-hat-cowboy-side:before{content:"\f8c1"}.fa-hat-wizard:before{content:"\f6e8"}.fa-hdd:before{content:"\f0a0"}.fa-head-side-cough:before{content:"\f961"}.fa-head-side-cough-slash:before{content:"\f962"}.fa-head-side-mask:before{content:"\f963"}.fa-head-side-virus:before{content:"\f964"}.fa-heading:before{content:"\f1dc"}.fa-headphones:before{content:"\f025"}.fa-headphones-alt:before{content:"\f58f"}.fa-headset:before{content:"\f590"}.fa-heart:before{content:"\f004"}.fa-heart-broken:before{content:"\f7a9"}.fa-heartbeat:before{content:"\f21e"}.fa-helicopter:before{content:"\f533"}.fa-highlighter:before{content:"\f591"}.fa-hiking:before{content:"\f6ec"}.fa-hippo:before{content:"\f6ed"}.fa-hips:before{content:"\f452"}.fa-hire-a-helper:before{content:"\f3b0"}.fa-history:before{content:"\f1da"}.fa-hockey-puck:before{content:"\f453"}.fa-holly-berry:before{content:"\f7aa"}.fa-home:before{content:"\f015"}.fa-hooli:before{content:"\f427"}.fa-hornbill:before{content:"\f592"}.fa-horse:before{content:"\f6f0"}.fa-horse-head:before{content:"\f7ab"}.fa-hospital:before{content:"\f0f8"}.fa-hospital-alt:before{content:"\f47d"}.fa-hospital-symbol:before{content:"\f47e"}.fa-hospital-user:before{content:"\f80d"}.fa-hot-tub:before{content:"\f593"}.fa-hotdog:before{content:"\f80f"}.fa-hotel:before{content:"\f594"}.fa-hotjar:before{content:"\f3b1"}.fa-hourglass:before{content:"\f254"}.fa-hourglass-end:before{content:"\f253"}.fa-hourglass-half:before{content:"\f252"}.fa-hourglass-start:before{content:"\f251"}.fa-house-damage:before{content:"\f6f1"}.fa-house-user:before{content:"\f965"}.fa-houzz:before{content:"\f27c"}.fa-hryvnia:before{content:"\f6f2"}.fa-html5:before{content:"\f13b"}.fa-hubspot:before{content:"\f3b2"}.fa-i-cursor:before{content:"\f246"}.fa-ice-cream:before{content:"\f810"}.fa-icicles:before{content:"\f7ad"}.fa-icons:before{content:"\f86d"}.fa-id-badge:before{content:"\f2c1"}.fa-id-card:before{content:"\f2c2"}.fa-id-card-alt:before{content:"\f47f"}.fa-ideal:before{content:"\f913"}.fa-igloo:before{content:"\f7ae"}.fa-image:before{content:"\f03e"}.fa-images:before{content:"\f302"}.fa-imdb:before{content:"\f2d8"}.fa-inbox:before{content:"\f01c"}.fa-indent:before{content:"\f03c"}.fa-industry:before{content:"\f275"}.fa-infinity:before{content:"\f534"}.fa-info:before{content:"\f129"}.fa-info-circle:before{content:"\f05a"}.fa-instagram:before{content:"\f16d"}.fa-instagram-square:before{content:"\f955"}.fa-intercom:before{content:"\f7af"}.fa-internet-explorer:before{content:"\f26b"}.fa-invision:before{content:"\f7b0"}.fa-ioxhost:before{content:"\f208"}.fa-italic:before{content:"\f033"}.fa-itch-io:before{content:"\f83a"}.fa-itunes:before{content:"\f3b4"}.fa-itunes-note:before{content:"\f3b5"}.fa-java:before{content:"\f4e4"}.fa-jedi:before{content:"\f669"}.fa-jedi-order:before{content:"\f50e"}.fa-jenkins:before{content:"\f3b6"}.fa-jira:before{content:"\f7b1"}.fa-joget:before{content:"\f3b7"}.fa-joint:before{content:"\f595"}.fa-joomla:before{content:"\f1aa"}.fa-journal-whills:before{content:"\f66a"}.fa-js:before{content:"\f3b8"}.fa-js-square:before{content:"\f3b9"}.fa-jsfiddle:before{content:"\f1cc"}.fa-kaaba:before{content:"\f66b"}.fa-kaggle:before{content:"\f5fa"}.fa-key:before{content:"\f084"}.fa-keybase:before{content:"\f4f5"}.fa-keyboard:before{content:"\f11c"}.fa-keycdn:before{content:"\f3ba"}.fa-khanda:before{content:"\f66d"}.fa-kickstarter:before{content:"\f3bb"}.fa-kickstarter-k:before{content:"\f3bc"}.fa-kiss:before{content:"\f596"}.fa-kiss-beam:before{content:"\f597"}.fa-kiss-wink-heart:before{content:"\f598"}.fa-kiwi-bird:before{content:"\f535"}.fa-korvue:before{content:"\f42f"}.fa-landmark:before{content:"\f66f"}.fa-language:before{content:"\f1ab"}.fa-laptop:before{content:"\f109"}.fa-laptop-code:before{content:"\f5fc"}.fa-laptop-house:before{content:"\f966"}.fa-laptop-medical:before{content:"\f812"}.fa-laravel:before{content:"\f3bd"}.fa-lastfm:before{content:"\f202"}.fa-lastfm-square:before{content:"\f203"}.fa-laugh:before{content:"\f599"}.fa-laugh-beam:before{content:"\f59a"}.fa-laugh-squint:before{content:"\f59b"}.fa-laugh-wink:before{content:"\f59c"}.fa-layer-group:before{content:"\f5fd"}.fa-leaf:before{content:"\f06c"}.fa-leanpub:before{content:"\f212"}.fa-lemon:before{content:"\f094"}.fa-less:before{content:"\f41d"}.fa-less-than:before{content:"\f536"}.fa-less-than-equal:before{content:"\f537"}.fa-level-down-alt:before{content:"\f3be"}.fa-level-up-alt:before{content:"\f3bf"}.fa-life-ring:before{content:"\f1cd"}.fa-lightbulb:before{content:"\f0eb"}.fa-line:before{content:"\f3c0"}.fa-link:before{content:"\f0c1"}.fa-linkedin:before{content:"\f08c"}.fa-linkedin-in:before{content:"\f0e1"}.fa-linode:before{content:"\f2b8"}.fa-linux:before{content:"\f17c"}.fa-lira-sign:before{content:"\f195"}.fa-list:before{content:"\f03a"}.fa-list-alt:before{content:"\f022"}.fa-list-ol:before{content:"\f0cb"}.fa-list-ul:before{content:"\f0ca"}.fa-location-arrow:before{content:"\f124"}.fa-lock:before{content:"\f023"}.fa-lock-open:before{content:"\f3c1"}.fa-long-arrow-alt-down:before{content:"\f309"}.fa-long-arrow-alt-left:before{content:"\f30a"}.fa-long-arrow-alt-right:before{content:"\f30b"}.fa-long-arrow-alt-up:before{content:"\f30c"}.fa-low-vision:before{content:"\f2a8"}.fa-luggage-cart:before{content:"\f59d"}.fa-lungs:before{content:"\f604"}.fa-lungs-virus:before{content:"\f967"}.fa-lyft:before{content:"\f3c3"}.fa-magento:before{content:"\f3c4"}.fa-magic:before{content:"\f0d0"}.fa-magnet:before{content:"\f076"}.fa-mail-bulk:before{content:"\f674"}.fa-mailchimp:before{content:"\f59e"}.fa-male:before{content:"\f183"}.fa-mandalorian:before{content:"\f50f"}.fa-map:before{content:"\f279"}.fa-map-marked:before{content:"\f59f"}.fa-map-marked-alt:before{content:"\f5a0"}.fa-map-marker:before{content:"\f041"}.fa-map-marker-alt:before{content:"\f3c5"}.fa-map-pin:before{content:"\f276"}.fa-map-signs:before{content:"\f277"}.fa-markdown:before{content:"\f60f"}.fa-marker:before{content:"\f5a1"}.fa-mars:before{content:"\f222"}.fa-mars-double:before{content:"\f227"}.fa-mars-stroke:before{content:"\f229"}.fa-mars-stroke-h:before{content:"\f22b"}.fa-mars-stroke-v:before{content:"\f22a"}.fa-mask:before{content:"\f6fa"}.fa-mastodon:before{content:"\f4f6"}.fa-maxcdn:before{content:"\f136"}.fa-mdb:before{content:"\f8ca"}.fa-medal:before{content:"\f5a2"}.fa-medapps:before{content:"\f3c6"}.fa-medium:before{content:"\f23a"}.fa-medium-m:before{content:"\f3c7"}.fa-medkit:before{content:"\f0fa"}.fa-medrt:before{content:"\f3c8"}.fa-meetup:before{content:"\f2e0"}.fa-megaport:before{content:"\f5a3"}.fa-meh:before{content:"\f11a"}.fa-meh-blank:before{content:"\f5a4"}.fa-meh-rolling-eyes:before{content:"\f5a5"}.fa-memory:before{content:"\f538"}.fa-mendeley:before{content:"\f7b3"}.fa-menorah:before{content:"\f676"}.fa-mercury:before{content:"\f223"}.fa-meteor:before{content:"\f753"}.fa-microblog:before{content:"\f91a"}.fa-microchip:before{content:"\f2db"}.fa-microphone:before{content:"\f130"}.fa-microphone-alt:before{content:"\f3c9"}.fa-microphone-alt-slash:before{content:"\f539"}.fa-microphone-slash:before{content:"\f131"}.fa-microscope:before{content:"\f610"}.fa-microsoft:before{content:"\f3ca"}.fa-minus:before{content:"\f068"}.fa-minus-circle:before{content:"\f056"}.fa-minus-square:before{content:"\f146"}.fa-mitten:before{content:"\f7b5"}.fa-mix:before{content:"\f3cb"}.fa-mixcloud:before{content:"\f289"}.fa-mixer:before{content:"\f956"}.fa-mizuni:before{content:"\f3cc"}.fa-mobile:before{content:"\f10b"}.fa-mobile-alt:before{content:"\f3cd"}.fa-modx:before{content:"\f285"}.fa-monero:before{content:"\f3d0"}.fa-money-bill:before{content:"\f0d6"}.fa-money-bill-alt:before{content:"\f3d1"}.fa-money-bill-wave:before{content:"\f53a"}.fa-money-bill-wave-alt:before{content:"\f53b"}.fa-money-check:before{content:"\f53c"}.fa-money-check-alt:before{content:"\f53d"}.fa-monument:before{content:"\f5a6"}.fa-moon:before{content:"\f186"}.fa-mortar-pestle:before{content:"\f5a7"}.fa-mosque:before{content:"\f678"}.fa-motorcycle:before{content:"\f21c"}.fa-mountain:before{content:"\f6fc"}.fa-mouse:before{content:"\f8cc"}.fa-mouse-pointer:before{content:"\f245"}.fa-mug-hot:before{content:"\f7b6"}.fa-music:before{content:"\f001"}.fa-napster:before{content:"\f3d2"}.fa-neos:before{content:"\f612"}.fa-network-wired:before{content:"\f6ff"}.fa-neuter:before{content:"\f22c"}.fa-newspaper:before{content:"\f1ea"}.fa-nimblr:before{content:"\f5a8"}.fa-node:before{content:"\f419"}.fa-node-js:before{content:"\f3d3"}.fa-not-equal:before{content:"\f53e"}.fa-notes-medical:before{content:"\f481"}.fa-npm:before{content:"\f3d4"}.fa-ns8:before{content:"\f3d5"}.fa-nutritionix:before{content:"\f3d6"}.fa-object-group:before{content:"\f247"}.fa-object-ungroup:before{content:"\f248"}.fa-odnoklassniki:before{content:"\f263"}.fa-odnoklassniki-square:before{content:"\f264"}.fa-oil-can:before{content:"\f613"}.fa-old-republic:before{content:"\f510"}.fa-om:before{content:"\f679"}.fa-opencart:before{content:"\f23d"}.fa-openid:before{content:"\f19b"}.fa-opera:before{content:"\f26a"}.fa-optin-monster:before{content:"\f23c"}.fa-orcid:before{content:"\f8d2"}.fa-osi:before{content:"\f41a"}.fa-otter:before{content:"\f700"}.fa-outdent:before{content:"\f03b"}.fa-page4:before{content:"\f3d7"}.fa-pagelines:before{content:"\f18c"}.fa-pager:before{content:"\f815"}.fa-paint-brush:before{content:"\f1fc"}.fa-paint-roller:before{content:"\f5aa"}.fa-palette:before{content:"\f53f"}.fa-palfed:before{content:"\f3d8"}.fa-pallet:before{content:"\f482"}.fa-paper-plane:before{content:"\f1d8"}.fa-paperclip:before{content:"\f0c6"}.fa-parachute-box:before{content:"\f4cd"}.fa-paragraph:before{content:"\f1dd"}.fa-parking:before{content:"\f540"}.fa-passport:before{content:"\f5ab"}.fa-pastafarianism:before{content:"\f67b"}.fa-paste:before{content:"\f0ea"}.fa-patreon:before{content:"\f3d9"}.fa-pause:before{content:"\f04c"}.fa-pause-circle:before{content:"\f28b"}.fa-paw:before{content:"\f1b0"}.fa-paypal:before{content:"\f1ed"}.fa-peace:before{content:"\f67c"}.fa-pen:before{content:"\f304"}.fa-pen-alt:before{content:"\f305"}.fa-pen-fancy:before{content:"\f5ac"}.fa-pen-nib:before{content:"\f5ad"}.fa-pen-square:before{content:"\f14b"}.fa-pencil-alt:before{content:"\f303"}.fa-pencil-ruler:before{content:"\f5ae"}.fa-penny-arcade:before{content:"\f704"}.fa-people-arrows:before{content:"\f968"}.fa-people-carry:before{content:"\f4ce"}.fa-pepper-hot:before{content:"\f816"}.fa-percent:before{content:"\f295"}.fa-percentage:before{content:"\f541"}.fa-periscope:before{content:"\f3da"}.fa-person-booth:before{content:"\f756"}.fa-phabricator:before{content:"\f3db"}.fa-phoenix-framework:before{content:"\f3dc"}.fa-phoenix-squadron:before{content:"\f511"}.fa-phone:before{content:"\f095"}.fa-phone-alt:before{content:"\f879"}.fa-phone-slash:before{content:"\f3dd"}.fa-phone-square:before{content:"\f098"}.fa-phone-square-alt:before{content:"\f87b"}.fa-phone-volume:before{content:"\f2a0"}.fa-photo-video:before{content:"\f87c"}.fa-php:before{content:"\f457"}.fa-pied-piper:before{content:"\f2ae"}.fa-pied-piper-alt:before{content:"\f1a8"}.fa-pied-piper-hat:before{content:"\f4e5"}.fa-pied-piper-pp:before{content:"\f1a7"}.fa-pied-piper-square:before{content:"\f91e"}.fa-piggy-bank:before{content:"\f4d3"}.fa-pills:before{content:"\f484"}.fa-pinterest:before{content:"\f0d2"}.fa-pinterest-p:before{content:"\f231"}.fa-pinterest-square:before{content:"\f0d3"}.fa-pizza-slice:before{content:"\f818"}.fa-place-of-worship:before{content:"\f67f"}.fa-plane:before{content:"\f072"}.fa-plane-arrival:before{content:"\f5af"}.fa-plane-departure:before{content:"\f5b0"}.fa-plane-slash:before{content:"\f969"}.fa-play:before{content:"\f04b"}.fa-play-circle:before{content:"\f144"}.fa-playstation:before{content:"\f3df"}.fa-plug:before{content:"\f1e6"}.fa-plus:before{content:"\f067"}.fa-plus-circle:before{content:"\f055"}.fa-plus-square:before{content:"\f0fe"}.fa-podcast:before{content:"\f2ce"}.fa-poll:before{content:"\f681"}.fa-poll-h:before{content:"\f682"}.fa-poo:before{content:"\f2fe"}.fa-poo-storm:before{content:"\f75a"}.fa-poop:before{content:"\f619"}.fa-portrait:before{content:"\f3e0"}.fa-pound-sign:before{content:"\f154"}.fa-power-off:before{content:"\f011"}.fa-pray:before{content:"\f683"}.fa-praying-hands:before{content:"\f684"}.fa-prescription:before{content:"\f5b1"}.fa-prescription-bottle:before{content:"\f485"}.fa-prescription-bottle-alt:before{content:"\f486"}.fa-print:before{content:"\f02f"}.fa-procedures:before{content:"\f487"}.fa-product-hunt:before{content:"\f288"}.fa-project-diagram:before{content:"\f542"}.fa-pump-medical:before{content:"\f96a"}.fa-pump-soap:before{content:"\f96b"}.fa-pushed:before{content:"\f3e1"}.fa-puzzle-piece:before{content:"\f12e"}.fa-python:before{content:"\f3e2"}.fa-qq:before{content:"\f1d6"}.fa-qrcode:before{content:"\f029"}.fa-question:before{content:"\f128"}.fa-question-circle:before{content:"\f059"}.fa-quidditch:before{content:"\f458"}.fa-quinscape:before{content:"\f459"}.fa-quora:before{content:"\f2c4"}.fa-quote-left:before{content:"\f10d"}.fa-quote-right:before{content:"\f10e"}.fa-quran:before{content:"\f687"}.fa-r-project:before{content:"\f4f7"}.fa-radiation:before{content:"\f7b9"}.fa-radiation-alt:before{content:"\f7ba"}.fa-rainbow:before{content:"\f75b"}.fa-random:before{content:"\f074"}.fa-raspberry-pi:before{content:"\f7bb"}.fa-ravelry:before{content:"\f2d9"}.fa-react:before{content:"\f41b"}.fa-reacteurope:before{content:"\f75d"}.fa-readme:before{content:"\f4d5"}.fa-rebel:before{content:"\f1d0"}.fa-receipt:before{content:"\f543"}.fa-record-vinyl:before{content:"\f8d9"}.fa-recycle:before{content:"\f1b8"}.fa-red-river:before{content:"\f3e3"}.fa-reddit:before{content:"\f1a1"}.fa-reddit-alien:before{content:"\f281"}.fa-reddit-square:before{content:"\f1a2"}.fa-redhat:before{content:"\f7bc"}.fa-redo:before{content:"\f01e"}.fa-redo-alt:before{content:"\f2f9"}.fa-registered:before{content:"\f25d"}.fa-remove-format:before{content:"\f87d"}.fa-renren:before{content:"\f18b"}.fa-reply:before{content:"\f3e5"}.fa-reply-all:before{content:"\f122"}.fa-replyd:before{content:"\f3e6"}.fa-republican:before{content:"\f75e"}.fa-researchgate:before{content:"\f4f8"}.fa-resolving:before{content:"\f3e7"}.fa-restroom:before{content:"\f7bd"}.fa-retweet:before{content:"\f079"}.fa-rev:before{content:"\f5b2"}.fa-ribbon:before{content:"\f4d6"}.fa-ring:before{content:"\f70b"}.fa-road:before{content:"\f018"}.fa-robot:before{content:"\f544"}.fa-rocket:before{content:"\f135"}.fa-rocketchat:before{content:"\f3e8"}.fa-rockrms:before{content:"\f3e9"}.fa-route:before{content:"\f4d7"}.fa-rss:before{content:"\f09e"}.fa-rss-square:before{content:"\f143"}.fa-ruble-sign:before{content:"\f158"}.fa-ruler:before{content:"\f545"}.fa-ruler-combined:before{content:"\f546"}.fa-ruler-horizontal:before{content:"\f547"}.fa-ruler-vertical:before{content:"\f548"}.fa-running:before{content:"\f70c"}.fa-rupee-sign:before{content:"\f156"}.fa-sad-cry:before{content:"\f5b3"}.fa-sad-tear:before{content:"\f5b4"}.fa-safari:before{content:"\f267"}.fa-salesforce:before{content:"\f83b"}.fa-sass:before{content:"\f41e"}.fa-satellite:before{content:"\f7bf"}.fa-satellite-dish:before{content:"\f7c0"}.fa-save:before{content:"\f0c7"}.fa-schlix:before{content:"\f3ea"}.fa-school:before{content:"\f549"}.fa-screwdriver:before{content:"\f54a"}.fa-scribd:before{content:"\f28a"}.fa-scroll:before{content:"\f70e"}.fa-sd-card:before{content:"\f7c2"}.fa-search:before{content:"\f002"}.fa-search-dollar:before{content:"\f688"}.fa-search-location:before{content:"\f689"}.fa-search-minus:before{content:"\f010"}.fa-search-plus:before{content:"\f00e"}.fa-searchengin:before{content:"\f3eb"}.fa-seedling:before{content:"\f4d8"}.fa-sellcast:before{content:"\f2da"}.fa-sellsy:before{content:"\f213"}.fa-server:before{content:"\f233"}.fa-servicestack:before{content:"\f3ec"}.fa-shapes:before{content:"\f61f"}.fa-share:before{content:"\f064"}.fa-share-alt:before{content:"\f1e0"}.fa-share-alt-square:before{content:"\f1e1"}.fa-share-square:before{content:"\f14d"}.fa-shekel-sign:before{content:"\f20b"}.fa-shield-alt:before{content:"\f3ed"}.fa-shield-virus:before{content:"\f96c"}.fa-ship:before{content:"\f21a"}.fa-shipping-fast:before{content:"\f48b"}.fa-shirtsinbulk:before{content:"\f214"}.fa-shoe-prints:before{content:"\f54b"}.fa-shopify:before{content:"\f957"}.fa-shopping-bag:before{content:"\f290"}.fa-shopping-basket:before{content:"\f291"}.fa-shopping-cart:before{content:"\f07a"}.fa-shopware:before{content:"\f5b5"}.fa-shower:before{content:"\f2cc"}.fa-shuttle-van:before{content:"\f5b6"}.fa-sign:before{content:"\f4d9"}.fa-sign-in-alt:before{content:"\f2f6"}.fa-sign-language:before{content:"\f2a7"}.fa-sign-out-alt:before{content:"\f2f5"}.fa-signal:before{content:"\f012"}.fa-signature:before{content:"\f5b7"}.fa-sim-card:before{content:"\f7c4"}.fa-simplybuilt:before{content:"\f215"}.fa-sistrix:before{content:"\f3ee"}.fa-sitemap:before{content:"\f0e8"}.fa-sith:before{content:"\f512"}.fa-skating:before{content:"\f7c5"}.fa-sketch:before{content:"\f7c6"}.fa-skiing:before{content:"\f7c9"}.fa-skiing-nordic:before{content:"\f7ca"}.fa-skull:before{content:"\f54c"}.fa-skull-crossbones:before{content:"\f714"}.fa-skyatlas:before{content:"\f216"}.fa-skype:before{content:"\f17e"}.fa-slack:before{content:"\f198"}.fa-slack-hash:before{content:"\f3ef"}.fa-slash:before{content:"\f715"}.fa-sleigh:before{content:"\f7cc"}.fa-sliders-h:before{content:"\f1de"}.fa-slideshare:before{content:"\f1e7"}.fa-smile:before{content:"\f118"}.fa-smile-beam:before{content:"\f5b8"}.fa-smile-wink:before{content:"\f4da"}.fa-smog:before{content:"\f75f"}.fa-smoking:before{content:"\f48d"}.fa-smoking-ban:before{content:"\f54d"}.fa-sms:before{content:"\f7cd"}.fa-snapchat:before{content:"\f2ab"}.fa-snapchat-ghost:before{content:"\f2ac"}.fa-snapchat-square:before{content:"\f2ad"}.fa-snowboarding:before{content:"\f7ce"}.fa-snowflake:before{content:"\f2dc"}.fa-snowman:before{content:"\f7d0"}.fa-snowplow:before{content:"\f7d2"}.fa-soap:before{content:"\f96e"}.fa-socks:before{content:"\f696"}.fa-solar-panel:before{content:"\f5ba"}.fa-sort:before{content:"\f0dc"}.fa-sort-alpha-down:before{content:"\f15d"}.fa-sort-alpha-down-alt:before{content:"\f881"}.fa-sort-alpha-up:before{content:"\f15e"}.fa-sort-alpha-up-alt:before{content:"\f882"}.fa-sort-amount-down:before{content:"\f160"}.fa-sort-amount-down-alt:before{content:"\f884"}.fa-sort-amount-up:before{content:"\f161"}.fa-sort-amount-up-alt:before{content:"\f885"}.fa-sort-down:before{content:"\f0dd"}.fa-sort-numeric-down:before{content:"\f162"}.fa-sort-numeric-down-alt:before{content:"\f886"}.fa-sort-numeric-up:before{content:"\f163"}.fa-sort-numeric-up-alt:before{content:"\f887"}.fa-sort-up:before{content:"\f0de"}.fa-soundcloud:before{content:"\f1be"}.fa-sourcetree:before{content:"\f7d3"}.fa-spa:before{content:"\f5bb"}.fa-space-shuttle:before{content:"\f197"}.fa-speakap:before{content:"\f3f3"}.fa-speaker-deck:before{content:"\f83c"}.fa-spell-check:before{content:"\f891"}.fa-spider:before{content:"\f717"}.fa-spinner:before{content:"\f110"}.fa-splotch:before{content:"\f5bc"}.fa-spotify:before{content:"\f1bc"}.fa-spray-can:before{content:"\f5bd"}.fa-square:before{content:"\f0c8"}.fa-square-full:before{content:"\f45c"}.fa-square-root-alt:before{content:"\f698"}.fa-squarespace:before{content:"\f5be"}.fa-stack-exchange:before{content:"\f18d"}.fa-stack-overflow:before{content:"\f16c"}.fa-stackpath:before{content:"\f842"}.fa-stamp:before{content:"\f5bf"}.fa-star:before{content:"\f005"}.fa-star-and-crescent:before{content:"\f699"}.fa-star-half:before{content:"\f089"}.fa-star-half-alt:before{content:"\f5c0"}.fa-star-of-david:before{content:"\f69a"}.fa-star-of-life:before{content:"\f621"}.fa-staylinked:before{content:"\f3f5"}.fa-steam:before{content:"\f1b6"}.fa-steam-square:before{content:"\f1b7"}.fa-steam-symbol:before{content:"\f3f6"}.fa-step-backward:before{content:"\f048"}.fa-step-forward:before{content:"\f051"}.fa-stethoscope:before{content:"\f0f1"}.fa-sticker-mule:before{content:"\f3f7"}.fa-sticky-note:before{content:"\f249"}.fa-stop:before{content:"\f04d"}.fa-stop-circle:before{content:"\f28d"}.fa-stopwatch:before{content:"\f2f2"}.fa-stopwatch-20:before{content:"\f96f"}.fa-store:before{content:"\f54e"}.fa-store-alt:before{content:"\f54f"}.fa-store-alt-slash:before{content:"\f970"}.fa-store-slash:before{content:"\f971"}.fa-strava:before{content:"\f428"}.fa-stream:before{content:"\f550"}.fa-street-view:before{content:"\f21d"}.fa-strikethrough:before{content:"\f0cc"}.fa-stripe:before{content:"\f429"}.fa-stripe-s:before{content:"\f42a"}.fa-stroopwafel:before{content:"\f551"}.fa-studiovinari:before{content:"\f3f8"}.fa-stumbleupon:before{content:"\f1a4"}.fa-stumbleupon-circle:before{content:"\f1a3"}.fa-subscript:before{content:"\f12c"}.fa-subway:before{content:"\f239"}.fa-suitcase:before{content:"\f0f2"}.fa-suitcase-rolling:before{content:"\f5c1"}.fa-sun:before{content:"\f185"}.fa-superpowers:before{content:"\f2dd"}.fa-superscript:before{content:"\f12b"}.fa-supple:before{content:"\f3f9"}.fa-surprise:before{content:"\f5c2"}.fa-suse:before{content:"\f7d6"}.fa-swatchbook:before{content:"\f5c3"}.fa-swift:before{content:"\f8e1"}.fa-swimmer:before{content:"\f5c4"}.fa-swimming-pool:before{content:"\f5c5"}.fa-symfony:before{content:"\f83d"}.fa-synagogue:before{content:"\f69b"}.fa-sync:before{content:"\f021"}.fa-sync-alt:before{content:"\f2f1"}.fa-syringe:before{content:"\f48e"}.fa-table:before{content:"\f0ce"}.fa-table-tennis:before{content:"\f45d"}.fa-tablet:before{content:"\f10a"}.fa-tablet-alt:before{content:"\f3fa"}.fa-tablets:before{content:"\f490"}.fa-tachometer-alt:before{content:"\f3fd"}.fa-tag:before{content:"\f02b"}.fa-tags:before{content:"\f02c"}.fa-tape:before{content:"\f4db"}.fa-tasks:before{content:"\f0ae"}.fa-taxi:before{content:"\f1ba"}.fa-teamspeak:before{content:"\f4f9"}.fa-teeth:before{content:"\f62e"}.fa-teeth-open:before{content:"\f62f"}.fa-telegram:before{content:"\f2c6"}.fa-telegram-plane:before{content:"\f3fe"}.fa-temperature-high:before{content:"\f769"}.fa-temperature-low:before{content:"\f76b"}.fa-tencent-weibo:before{content:"\f1d5"}.fa-tenge:before{content:"\f7d7"}.fa-terminal:before{content:"\f120"}.fa-text-height:before{content:"\f034"}.fa-text-width:before{content:"\f035"}.fa-th:before{content:"\f00a"}.fa-th-large:before{content:"\f009"}.fa-th-list:before{content:"\f00b"}.fa-the-red-yeti:before{content:"\f69d"}.fa-theater-masks:before{content:"\f630"}.fa-themeco:before{content:"\f5c6"}.fa-themeisle:before{content:"\f2b2"}.fa-thermometer:before{content:"\f491"}.fa-thermometer-empty:before{content:"\f2cb"}.fa-thermometer-full:before{content:"\f2c7"}.fa-thermometer-half:before{content:"\f2c9"}.fa-thermometer-quarter:before{content:"\f2ca"}.fa-thermometer-three-quarters:before{content:"\f2c8"}.fa-think-peaks:before{content:"\f731"}.fa-thumbs-down:before{content:"\f165"}.fa-thumbs-up:before{content:"\f164"}.fa-thumbtack:before{content:"\f08d"}.fa-ticket-alt:before{content:"\f3ff"}.fa-times:before{content:"\f00d"}.fa-times-circle:before{content:"\f057"}.fa-tint:before{content:"\f043"}.fa-tint-slash:before{content:"\f5c7"}.fa-tired:before{content:"\f5c8"}.fa-toggle-off:before{content:"\f204"}.fa-toggle-on:before{content:"\f205"}.fa-toilet:before{content:"\f7d8"}.fa-toilet-paper:before{content:"\f71e"}.fa-toilet-paper-slash:before{content:"\f972"}.fa-toolbox:before{content:"\f552"}.fa-tools:before{content:"\f7d9"}.fa-tooth:before{content:"\f5c9"}.fa-torah:before{content:"\f6a0"}.fa-torii-gate:before{content:"\f6a1"}.fa-tractor:before{content:"\f722"}.fa-trade-federation:before{content:"\f513"}.fa-trademark:before{content:"\f25c"}.fa-traffic-light:before{content:"\f637"}.fa-trailer:before{content:"\f941"}.fa-train:before{content:"\f238"}.fa-tram:before{content:"\f7da"}.fa-transgender:before{content:"\f224"}.fa-transgender-alt:before{content:"\f225"}.fa-trash:before{content:"\f1f8"}.fa-trash-alt:before{content:"\f2ed"}.fa-trash-restore:before{content:"\f829"}.fa-trash-restore-alt:before{content:"\f82a"}.fa-tree:before{content:"\f1bb"}.fa-trello:before{content:"\f181"}.fa-tripadvisor:before{content:"\f262"}.fa-trophy:before{content:"\f091"}.fa-truck:before{content:"\f0d1"}.fa-truck-loading:before{content:"\f4de"}.fa-truck-monster:before{content:"\f63b"}.fa-truck-moving:before{content:"\f4df"}.fa-truck-pickup:before{content:"\f63c"}.fa-tshirt:before{content:"\f553"}.fa-tty:before{content:"\f1e4"}.fa-tumblr:before{content:"\f173"}.fa-tumblr-square:before{content:"\f174"}.fa-tv:before{content:"\f26c"}.fa-twitch:before{content:"\f1e8"}.fa-twitter:before{content:"\f099"}.fa-twitter-square:before{content:"\f081"}.fa-typo3:before{content:"\f42b"}.fa-uber:before{content:"\f402"}.fa-ubuntu:before{content:"\f7df"}.fa-uikit:before{content:"\f403"}.fa-umbraco:before{content:"\f8e8"}.fa-umbrella:before{content:"\f0e9"}.fa-umbrella-beach:before{content:"\f5ca"}.fa-underline:before{content:"\f0cd"}.fa-undo:before{content:"\f0e2"}.fa-undo-alt:before{content:"\f2ea"}.fa-uniregistry:before{content:"\f404"}.fa-unity:before{content:"\f949"}.fa-universal-access:before{content:"\f29a"}.fa-university:before{content:"\f19c"}.fa-unlink:before{content:"\f127"}.fa-unlock:before{content:"\f09c"}.fa-unlock-alt:before{content:"\f13e"}.fa-untappd:before{content:"\f405"}.fa-upload:before{content:"\f093"}.fa-ups:before{content:"\f7e0"}.fa-usb:before{content:"\f287"}.fa-user:before{content:"\f007"}.fa-user-alt:before{content:"\f406"}.fa-user-alt-slash:before{content:"\f4fa"}.fa-user-astronaut:before{content:"\f4fb"}.fa-user-check:before{content:"\f4fc"}.fa-user-circle:before{content:"\f2bd"}.fa-user-clock:before{content:"\f4fd"}.fa-user-cog:before{content:"\f4fe"}.fa-user-edit:before{content:"\f4ff"}.fa-user-friends:before{content:"\f500"}.fa-user-graduate:before{content:"\f501"}.fa-user-injured:before{content:"\f728"}.fa-user-lock:before{content:"\f502"}.fa-user-md:before{content:"\f0f0"}.fa-user-minus:before{content:"\f503"}.fa-user-ninja:before{content:"\f504"}.fa-user-nurse:before{content:"\f82f"}.fa-user-plus:before{content:"\f234"}.fa-user-secret:before{content:"\f21b"}.fa-user-shield:before{content:"\f505"}.fa-user-slash:before{content:"\f506"}.fa-user-tag:before{content:"\f507"}.fa-user-tie:before{content:"\f508"}.fa-user-times:before{content:"\f235"}.fa-users:before{content:"\f0c0"}.fa-users-cog:before{content:"\f509"}.fa-usps:before{content:"\f7e1"}.fa-ussunnah:before{content:"\f407"}.fa-utensil-spoon:before{content:"\f2e5"}.fa-utensils:before{content:"\f2e7"}.fa-vaadin:before{content:"\f408"}.fa-vector-square:before{content:"\f5cb"}.fa-venus:before{content:"\f221"}.fa-venus-double:before{content:"\f226"}.fa-venus-mars:before{content:"\f228"}.fa-viacoin:before{content:"\f237"}.fa-viadeo:before{content:"\f2a9"}.fa-viadeo-square:before{content:"\f2aa"}.fa-vial:before{content:"\f492"}.fa-vials:before{content:"\f493"}.fa-viber:before{content:"\f409"}.fa-video:before{content:"\f03d"}.fa-video-slash:before{content:"\f4e2"}.fa-vihara:before{content:"\f6a7"}.fa-vimeo:before{content:"\f40a"}.fa-vimeo-square:before{content:"\f194"}.fa-vimeo-v:before{content:"\f27d"}.fa-vine:before{content:"\f1ca"}.fa-virus:before{content:"\f974"}.fa-virus-slash:before{content:"\f975"}.fa-viruses:before{content:"\f976"}.fa-vk:before{content:"\f189"}.fa-vnv:before{content:"\f40b"}.fa-voicemail:before{content:"\f897"}.fa-volleyball-ball:before{content:"\f45f"}.fa-volume-down:before{content:"\f027"}.fa-volume-mute:before{content:"\f6a9"}.fa-volume-off:before{content:"\f026"}.fa-volume-up:before{content:"\f028"}.fa-vote-yea:before{content:"\f772"}.fa-vr-cardboard:before{content:"\f729"}.fa-vuejs:before{content:"\f41f"}.fa-walking:before{content:"\f554"}.fa-wallet:before{content:"\f555"}.fa-warehouse:before{content:"\f494"}.fa-water:before{content:"\f773"}.fa-wave-square:before{content:"\f83e"}.fa-waze:before{content:"\f83f"}.fa-weebly:before{content:"\f5cc"}.fa-weibo:before{content:"\f18a"}.fa-weight:before{content:"\f496"}.fa-weight-hanging:before{content:"\f5cd"}.fa-weixin:before{content:"\f1d7"}.fa-whatsapp:before{content:"\f232"}.fa-whatsapp-square:before{content:"\f40c"}.fa-wheelchair:before{content:"\f193"}.fa-whmcs:before{content:"\f40d"}.fa-wifi:before{content:"\f1eb"}.fa-wikipedia-w:before{content:"\f266"}.fa-wind:before{content:"\f72e"}.fa-window-close:before{content:"\f410"}.fa-window-maximize:before{content:"\f2d0"}.fa-window-minimize:before{content:"\f2d1"}.fa-window-restore:before{content:"\f2d2"}.fa-windows:before{content:"\f17a"}.fa-wine-bottle:before{content:"\f72f"}.fa-wine-glass:before{content:"\f4e3"}.fa-wine-glass-alt:before{content:"\f5ce"}.fa-wix:before{content:"\f5cf"}.fa-wizards-of-the-coast:before{content:"\f730"}.fa-wolf-pack-battalion:before{content:"\f514"}.fa-won-sign:before{content:"\f159"}.fa-wordpress:before{content:"\f19a"}.fa-wordpress-simple:before{content:"\f411"}.fa-wpbeginner:before{content:"\f297"}.fa-wpexplorer:before{content:"\f2de"}.fa-wpforms:before{content:"\f298"}.fa-wpressr:before{content:"\f3e4"}.fa-wrench:before{content:"\f0ad"}.fa-x-ray:before{content:"\f497"}.fa-xbox:before{content:"\f412"}.fa-xing:before{content:"\f168"}.fa-xing-square:before{content:"\f169"}.fa-y-combinator:before{content:"\f23b"}.fa-yahoo:before{content:"\f19e"}.fa-yammer:before{content:"\f840"}.fa-yandex:before{content:"\f413"}.fa-yandex-international:before{content:"\f414"}.fa-yarn:before{content:"\f7e3"}.fa-yelp:before{content:"\f1e9"}.fa-yen-sign:before{content:"\f157"}.fa-yin-yang:before{content:"\f6ad"}.fa-yoast:before{content:"\f2b1"}.fa-youtube:before{content:"\f167"}.fa-youtube-square:before{content:"\f431"}.fa-zhihu:before{content:"\f63f"}.sr-only{border:0;clip:rect(0,0,0,0);height:1px;margin:-1px;overflow:hidden;padding:0;position:absolute;width:1px}.sr-only-focusable:active,.sr-only-focusable:focus{clip:auto;height:auto;margin:0;overflow:visible;position:static;width:auto}@font-face{font-family:"Font Awesome 5 Brands";font-style:normal;font-weight:400;font-display:block;
	
	src:url(/hr/wp-content/themes/flatsome/landing-builder/free-fa-brands-400.eot);
	src:url(/hr/wp-content/themes/flatsome/landing-builder/free-fa-brands-400.eot?#iefix) format("embedded-opentype"),
	url(/hr/wp-content/themes/flatsome/landing-builder/free-fa-brands-400.woff2) format("woff2"),
	url(/hr/wp-content/themes/flatsome/landing-builder/free-fa-brands-400.woff) format("woff"),
	url(/hr/wp-content/themes/flatsome/landing-builder/free-fa-brands-400.ttf) format("truetype"),
	url(/hr/wp-content/themes/flatsome/landing-builder/free-fa-brands-400.svg#fontawesome) format("svg")}.fab{font-family:"Font Awesome 5 Brands"}@font-face{font-family:"Font Awesome 5 Free";font-style:normal;font-weight:400;font-display:block;src:url(../webfonts/free-fa-regular-400.eot);src:url(../webfonts/free-fa-regular-400.eot?#iefix) format("embedded-opentype"),url(../webfonts/free-fa-regular-400.woff2) format("woff2"),url(../webfonts/free-fa-regular-400.woff) format("woff"),url(../webfonts/free-fa-regular-400.ttf) format("truetype"),url(../webfonts/free-fa-regular-400.svg#fontawesome) format("svg")}@font-face{font-family:"Font Awesome 5 Pro";font-style:normal;font-weight:400;font-display:block;src:url(../webfonts/free-fa-regular-400.eot);src:url(../webfonts/free-fa-regular-400.eot?#iefix) format("embedded-opentype"),url(../webfonts/free-fa-regular-400.woff2) format("woff2"),url(../webfonts/free-fa-regular-400.woff) format("woff"),url(../webfonts/free-fa-regular-400.ttf) format("truetype"),url(../webfonts/free-fa-regular-400.svg#fontawesome) format("svg")}.fab,.far{font-weight:400}@font-face{font-family:"Font Awesome 5 Free";font-style:normal;font-weight:900;font-display:block;src:url(../webfonts/free-fa-solid-900.eot);src:url(../webfonts/free-fa-solid-900.eot?#iefix) format("embedded-opentype"),url(../webfonts/free-fa-solid-900.woff2) format("woff2"),url(../webfonts/free-fa-solid-900.woff) format("woff"),url(../webfonts/free-fa-solid-900.ttf) format("truetype"),url(../webfonts/free-fa-solid-900.svg#fontawesome) format("svg")}@font-face{font-family:"Font Awesome 5 Pro";font-style:normal;font-weight:900;font-display:block;
	
	src:url(/hr/wp-content/themes/flatsome/landing-builder/free-fa-solid-900.eot);
	src:url(/hr/wp-content/themes/flatsome/landing-builder/free-fa-solid-900.eot?#iefix) format("embedded-opentype"),
	url(/hr/wp-content/themes/flatsome/landing-builder/free-fa-solid-900.woff2) format("woff2"),
	url(/hr/wp-content/themes/flatsome/landing-builder/free-fa-solid-900.woff) format("woff"),
	url(/hr/wp-content/themes/flatsome/landing-builder/free-fa-solid-900.ttf) format("truetype"),
	url(/hr/wp-content/themes/flatsome/landing-builder/free-fa-solid-900.svg#fontawesome) format("svg")}.fa,.far,.fas{font-family:"Font Awesome 5 Free"}.fa,.fas{font-weight:900}
	
    header, footer, #wpadminbar, #main-menu, #login-form-popup, .cc-banner, .cc-bottom, .cc-window, .cc-window, .cc-floating, .cc-type-info, .cc-theme-block, .cc-bottom, .cc-color-override--1766633788, #cc-countdown-wrap, .footer-badges, .checkout-countdown-wrapper     {
        
        display: none !important;
    }
	
	
	
	
    
	.act_variation {
	    border: #f3ca66 solid 1.5px;
    width: 33%;
    box-shadow: 1px 1px 2px 0 rgba(0, 0, 0, 0.16);
    background-color: #fff6e1;
    cursor: pointer;
	
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
	<style>
			.uvodna2 .primary {
				
				margin-left: 0px !important;
			}
			
			@media only screen and (min-width: 901px) {
			 .uvodna2 .button-kupi {
				margin: 0 !important;
			  }
			}		
			
			.uvodna2 .button-kupi {
		background: linear-gradient(0deg, rgb(20, 138, 26) 0%, rgb(76, 204, 37) 100%);
		display: flex;
    min-width: 150px;
    max-width: 350px;
    text-align: center;
    justify-content: center;
    position: relative;
    z-index: 1;
    font-size: 16px;
    line-height: 1;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    color: #ffffff;
    background-color: #ffffff;
    border-radius: 8px;
    margin: 10px auto;
    box-shadow: 0px 3px 5px 0px rgba(37, 58, 150, 0.19);
	}
			</style>
<style>
	#nakup222 .fas { 
	    padding-right: 5px;
		font-size: 18px;
	}
	#nakup222 {
		    display: flex;
			align-items: center;
			padding: 80px 50px;
			min-height: 100px;
			    margin: 0;
			

	}
	
	@media only screen and (min-width: 900px) { 
				
		.tekstOpisdesno {
			width: 50% !important;
		}
	}
	
	@media only screen and (max-width: 900px) {
#nakup222 {
		    display: block;
			align-items: center;
			padding: 80px 50px;
			padding-left: 40px !important;
			padding-right: 40px !important;
			overflow-x: hidden;

			

	}
	.no-pad {
		padding: 0 !important;
	}
}
	#nakup222 p {
		
	}
	#nakup222 .mb-30  {
		    margin-bottom: 30px !important;
	}
	#nakup222 .mb-10  {
		    margin-bottom: 10px !important;
	}
	#nakup222 .mt-10 {
		margin-top: 10px !important;
	}
	#nakup222 .mb-5 {
		    margin-bottom: 5px !important;
	}
	#nakup222 .mini-headline {
	 position: absolute;
  font-wieght: bold !important;
    background-color: #FFE24E;
    /* margin-left: 25px; */
    margin-top: 20px;
    /* margin-bottom: 50px; */
    padding: 10px 30px;
    border-radius: 25px;
    box-shadow: 4px 4px 5px grey;
	left: 50%;
    transform: translate(-50%, 0) rotate(-7deg);
	}
	#nakup222 #getting-started {
		    margin-top: 90px;
	}
	#nakup222 .button-kupi {
		background: linear-gradient(0deg, rgb(20, 138, 26) 0%, rgb(76, 204, 37) 100%);
		display: flex;
    min-width: 150px;
    max-width: 350px;
    text-align: center;
    justify-content: center;
    position: relative;
    z-index: 1;
    font-size: 16px;
    line-height: 1;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    color: #ffffff;
    background-color: #ffffff;
    border-radius: 8px;
    margin: 10px auto;
    box-shadow: 0px 3px 5px 0px rgba(37, 58, 150, 0.19);
	}
	#nakup222 .safe {
		position: absolute;
    color: rgb(85, 90, 105);
    top: -9px;
    left: 7px;
    font-weight: 500;
    padding: 0px 10px;
    font-size: 0.7em;
    background-color: white;
    border-radius: 5px;
	}
	#nakup222 .cards {
		    border: solid 2px rgb(85, 90, 105);
    padding: 5px;
    padding-top: 8px;
    border-radius: 5px;
    display: flex;
    align-items: center;
	    padding-left: 20px;
    padding-right: 20px;
	margin-top: 20px;
	}
	#nakup222 .nakupX {
		background-color: white;
    border-radius: 10px;
    border-top: solid 4px #1ad4cb;
    box-shadow: 0px 19px 30px -5px rgba(0, 0, 0, 0.15);
	grid-column: 7/13;
    padding: 30px;
	padding-right: 30px !important;
    padding-left: 30px !important;
	}
	#nakup222 .payment-methods {
		display: flex;
    margin-top: 1rem;
    justify-content: space-between;
    align-items: center;
	}
	#nakup222 .payments{
		    width: 60%;
	}	
	#nakup222 .sender {
			width: 40%;
			    display: flex;
    font-weight: 800;
    border-radius: 5px;
    padding-left: 10px;
    padding-right: 10px;
    flex-direction: row;
    align-items: center;
    border: 1px solid #efefef;
	height: 45px;
		}
	 #nakup222  .sender span {
    font-size: .8rem;
	    width: 75%;
    margin-left: 10px;
}
	@media only screen and (max-width: 900px) { 
				#nakup222 #getting-started  { 
						width: 100% !important;
						margin-top: 120px !important;
				}
				#nakup222 #getting-started2  { 
					width: 100% !important;
				}
	}
	#nakup222 #getting-started  {
border: 1px solid white;
border-radius: 5px;
width: 50%;
	    width: 50%;
    display: block;
    margin: 100px auto 0 auto;
}
 #nakup222 #getting-started span {
			background: #db1010;
			color: white;
			padding: 10px 10px 5px 10px;
			font-size: 16px !important;
			border: 4px solid white;
			border-radius: 10px;
			
			font-size: 40px;
			font-wieght: block;
			text-align: center;
			width: 25%;
			display: inline-block;
			border-bottom: none !important;
			  border-bottom-left-radius: 0 !important;
				    border-bottom-right-radius: 0 !important;
		}
			#nakup222 #getting-started2  {
				border: 1px solid white;
				border-radius: 5px;
				width: 50%;
						width: 50%;
					display: block;
					margin: -8px auto 0 auto;
						width: 50%;
					display: block;
					margin-top: -8px !important;
}
 #nakup222 #getting-started2 span {
			background: #db1010;
			color: white;
			border-top-left-radius: 0 !important;
			border-top-right-radius: 0 !important;
			padding: 0px 10px 10px 10px;
			font-size: 12px !important;
			border: 4px solid white;
			border-radius: 10px;
			border-top: none !important;
			font-size: 40px;
			font-wieght: block;
			text-align: center;
			width: 25%;
			display: inline-block;
			border-top: none !important;
	
}
</style>
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
</style>
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


<!--
        <div class="container">
            <span class="arrows-scroll slide-in-top"></span>
        </div>
		-->
		
            <section  style="  height: 4rem;"  class="sticky">
			
			
    <div  style="background: <?php echo $header_main_color; ?> !important;     height: 4rem;" class="wrapper">
	
			
				
				<?php if($pasica): ?>
			
			<div style="top: 0;
    position: absolute;
    /* text-align: center; */
    left: 0; width: 100%; display: block;text-align: center; background: <?php echo $pasica_barva; ?>; height: 20px;">
				<div class="container text-center">
				<p style="font-size: 13px; padding-top: 2px;    display: block;    margin: 0 auto; text-align: center; font-weight: bold; color: <?php echo $pasica_barva_tekst;?>" >
				<?php echo $pasica_tekst; ?></p>
				</div>
			</div>
	<?php endif; ?>
				
			
			
			
	
        <div style="    margin-top: 18px;" class="container">
            <div class="logo">
                <span>
                  
                  <img  style="width: auto; height:38px; " src="<?php echo $logo; ?>" />
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


<!-- uvodna -->
<?php
            $img = get_field('uvodna_slika_gif');   
			$tekst1 = get_field('uvodna_naslov');  
			
			$tekst2 = $regular_price;  
			$tekst3 = $sale_price;  

			
			if( $domain2 == "MISTERMEGA.SI" || $domain2 == "mistermega.si" || $domain2 == "cartflip.si" || $domain2 == "CARTFLIP.SI" ) {
				$tekst2 = "";  
				$tekst3 = $regular_price;
			}

			$tekst4 = get_field('uvodna_znizanje');  
			$tekst5 = get_field('uvodna_tekst_last');  
			$color1 =  get_field('uvodna_barva_ozadje');  
			$color2 =  get_field('uvodna_barva_tekst');  
			$color3 =  get_field('uvodna_barva_ozadje_2'); 
			$video = get_field('uvodna_video_file');   
			$video2 = get_field('uvodna_video_file_2');   
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
                           
						   <?php if($tekst2 != null && $tekst2 != ""): ?>
						   <span class="old"><?php echo $tekst2; ?><?php echo $valuta; ?> </span>
                           <?php endif; ?>

						   <span class="new"><b><?php echo $tekst3; ?><?php echo $valuta; ?>
							</b></span>
							
							
                        </div>
                        <div class="savings">
                            <?php echo $tekst4; ?>
                        </div>
                        <a href="#nakup" style="background: <?php echo $color3; ?> !important;" class="banner secondary will-scroll mp_cta_main">
                            <b><?php echo $tekst5; ?></b>
                        </a>
               
                     </div>
                </div>
            </div>
        </div>

</section>
<!-- uvodna -->


<!-- 4 ikone v vrsto -->  
<?php
			
			$tekst1 = get_field('sec_5vrsto_tekst_1');  
			$tekst2 = get_field('sec_5vrsto_tekst_2');  
			$tekst3 = get_field('sec_5vrsto_tekst_3');  
			$tekst4 = get_field('sec_5vrsto_tekst_4');  
			
			$img1 = get_field('sec_4vrsto_ikona_1');  
			$img2 = get_field('sec_4vrsto_ikona_2');  
			$img3 = get_field('sec_4vrsto_ikona_3');  
			$img4 = get_field('sec_4vrsto_ikona_4');  

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
<!-- 4 ikone v vrsto -->  


<!-- slika levo, ikonce desno -->

<?php
			$naslov = get_field('slika_ikone_naslov');  
            $img = get_field('slika_ikone_slika_leva');   
			
			$tekst1 = get_field('slika_ikone_ikona_1_tekst');  
			$tekst2 = get_field('slika_ikone_ikona_2_tekst');  
			$tekst3 = get_field('slika_ikone_ikona_3_tekst');  
			$tekst4 = get_field('slika_ikone_ikona_4_tekst');  
			
			$img1 = get_field('slika_ikone_ikona_1');  
			$img2 = get_field('slika_ikone_ikona_2');  
			$img3 = get_field('slika_ikone_ikona_3');  
			$img4 = get_field('slika_ikone_ikona_4');  
			
			$color11 = get_field('slika_ikone_barva_tekst');  
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

<section  class="section watch">
    <div class="container">
        <div class="columns">
            <div class="column is-12-mobile is-1">
            </div>
            <div class="column is-12-mobile is-4">

                <img style="display: block;  margin: 0 auto;" src="<?php echo $img; ?>" />

            </div>

            <div class="column is-9 deskto-up tekstOpisdesno ">
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


<!-- slika levo, ikonce desno -->

  


            

<!-- okrogle slike -->
<?php 
					 
			$naslov = get_field('okrogle_slike_naslov');  
            $naslov2 = get_field('okrogle_slike_tekst');   
			
			$tekst1 = get_field('okrogle_slike_tekst_1');  
			$tekst2 = get_field('okrogle_slike_tekst_2');  
			$tekst3 = get_field('okrogle_slike_tekst_3');  
			
			$img1 = get_field('okrogle_slike_slika_1');  
			$img2 = get_field('okrogle_slike_slika_2');  
			$img3 = get_field('okrogle_slike_slika_3');  

			$color11 = get_field('okrogle_slike_barva_ozadje');  
			
			$bgimage = get_field('okrogle_slike_barva_ozadje_copy');  
			
			
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
            
<!-- okrogle slike -->



<!-- nakupna -->

<?php
            $repeter = get_field('nakupna_slike');   //d($title);
			
			$tekst1 = get_field('nakupna_tekst');  
			$tekst2 = get_field('nakupna_tekst_2');  	
			$tekst3 = get_field('nakupna_tekst_3');  
			$tekst4 = get_field('nakupna_tekst_4');  
			$ocena = get_field('nakupna_ocena');  
			$bestseller = get_field('nakupna_bestseller');  
			
			$bestseller_barva = get_field('nakupna_bestseller_barva');  
			
			$kolicina = get_field('nakupna_kolicina');  
			
			$izdelek = get_field('podatki_o_izdelku_copy');  
			 
			$payments_slike = get_field('nakupna_payments_slike');  
			$dostava = get_field('nakupna_dostava');  
			$dostava_tekst = get_field('nakupna_dostava_tekst');  
			$dodaj_v_kosarico = get_field('nakupna_dodaj_v_kosarico');  
			$na_voljo_se = get_field('nakupna_na_voljo_se');  
			
			$dodaj_v_kosarico2 = get_field('nakupna_dodaj_v_kosarico_color');  
			
			$dni = get_field('nakupna_time1');  
			$ur =  get_field('nakupna_time2');  
			$min = get_field('nakupna_time3');  
			$sek =  get_field('nakupna_time4');  
			
?>
            
   

<section  class="section buy">
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
				  
				  
			<?php if( $product_type == "dvojne" ): ?>
			
			<div id="buy" class="column is-6 is-12-mobile scroll-target">
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
								
							
								<p style="text-align: center;     margin-top: 1.3rem !important;   margin-bottom: 0.7rem;">
									<?php echo $vartekst; ?> 1:
								</p>	
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
			
			
			<?php elseif( $product_type == "enojne"  ): ?>
			
			
			
			
				<div id="buy" class="column is-6 is-12-mobile scroll-target">
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
							
							<form id="nakupna" method="GET" action="<?php echo $domain; ?>/?add-to-cart=<?php echo $izdelek; ?>" accept-charset="UTF-8">
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
													class="price_qty price_qty_1"><?php echo $cena1; ?></span><span
													class="per_qty">/<?php echo $kolicina; ?></span></td>
											<td class="product_qty mp_product_qty_2 product_price_basic"><b>2</b>x <span
													class="price_qty price_qty_2"><?php echo $cena2; ?></span><span
													class="per_qty">/<?php echo $kolicina; ?></span></td>
											<td class="product_qty mp_product_qty_3 product_price_basic"><b>3</b>x <span
													class="price_qty price_qty_3"><?php echo $cena3; ?></span><span
													class="per_qty">/<?php echo $kolicina; ?></span></td>
										</tr>
									</table>
									
							
								
								<p style="text-align: center;     margin-top: 1.3rem !important;   margin-bottom: 0.7rem;">
									<?php echo $vartekst; ?> 1:
								</p>	
								
				
									
								   <div id="izdelek1" class="bottom_table izdelek1 q_act">
									   <table>
										<tr>
										<?php $c = 0; ?>
											
											<?php foreach($available_variations as $vv): ?>
											
											<td data-thisid="<?php echo $vv['variation_id']; ?>" class="product_qty2 izbira1 product_price_basic <?php if( $c == 0){ echo 'act_variation';} ?>">
											
											 <span class="price_qty1 color1"><?php echo reset($vv['attributes']) ?></span><span
													class="per_qty1"></span>
													</td>
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
											
											<td data-thisid="<?php echo $vv['variation_id']; ?>" class="product_qty2 izbira1 product_price_basic <?php if( $c == 0){ echo 'act_variation';} ?>">
											
											 <span class="price_qty1 color1"><?php echo reset($vv['attributes']) ?></span><span
													class="per_qty1"></span>
													</td>
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
											
											<td data-thisid="<?php echo $vv['variation_id']; ?>" class="product_qty2 izbira1 product_price_basic <?php if( $c == 0){ echo 'act_variation';} ?>">
											
											 <span class="price_qty1 color1"><?php echo reset($vv['attributes']) ?></span><span
													class="per_qty1"></span>
													</td>
											<?php $c = $c +1; ?>
											<?php endforeach; ?>
										</tr>
									</table>
								</div>
								
								<style>
								.bottom_table td { width: auto !important; }
								
								.bottom_table table {table-layout: fixed; }
								    
								</style>
								
								
									
									
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
							<a style="background: <?php echo $dodaj_v_kosarico2; ?> !important;" id="order_link" href="<?php echo $domain; ?>/?add-to-cart=<?php echo $izdelek; ?>" class="button is-primary submit mp_submit"><?php echo $dodaj_v_kosarico;  ?></a>
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
				
			
			
			<?php else: ?>
			
			
			
		
			<div id="buy" class="column is-6 is-12-mobile scroll-target">
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
                        
                        <form id="nakupna" method="GET" action="<?php echo $domain; ?>/?add-to-cart=<?php echo $izdelek; ?>" accept-charset="UTF-8">
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
                                                class="price_qty price_qty_1"><?php echo $cena1; ?></span><span
                                                class="per_qty">/<?php echo $kolicina; ?></span></td>
                                        <td class="product_qty mp_product_qty_2 product_price_basic"><b>2</b>x <span
                                                class="price_qty price_qty_2"><?php echo $cena2; ?></span><span
                                                class="per_qty">/<?php echo $kolicina; ?></span></td>
                                        <td class="product_qty mp_product_qty_3 product_price_basic"><b>3</b>x <span
                                                class="price_qty price_qty_3"><?php echo $cena3; ?></span><span
                                                class="per_qty">/<?php echo $kolicina; ?></span></td>
                                    </tr>
                                </table>
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
                        <a style="background: <?php echo $dodaj_v_kosarico2; ?> !important;" id="order_link" href="<?php echo $domain; ?>/?add-to-cart=<?php echo $izdelek; ?>" class="button is-primary submit mp_submit"><?php echo $dodaj_v_kosarico;  ?></a>
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
		
			
			
			<?php endif; ?>
	
			
					
					
					
					
                </div>


        </div>

</section>



<!-- nakupna -->

<!-- lastnosti -->          
<?php
            $naslov = get_field('lastnosti_naslov');   //d($title);
			$text1 = get_field('lastnosti_tekst_1');
			$text2 = get_field('lastnosti_tekst_2');
			$text3 = get_field('lastnosti_tekst_3');
			$text4 = get_field('lastnosti_tekst_4');
			$text5 = get_field('lastnosti_tekst_5');
			$text6 = get_field('lastnosti_tekst_6');

			$color11 = get_field('lastnosti_barva_ozadje');  
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
<!-- lastnosti -->      

	   
     
<!-- galerija --> 
<?php
            $repeter2 = get_field('galerija_slike');   //d($title);
?>
            
            
<section class="section gallery-section">
        <div class="container">
            <div class="columns slides slides-mobile-only carousel-1">
			
				<?php if($repeter2): ?>
								
									<?php foreach($repeter2 as $r): ?>
																		
									 <div class="column is-4">
                    <img src="<?php echo $r['slikag']; ?>">
                </div>
									
									<?php endforeach; ?>
								<?php endif; ?>
			
			
               
            </div>
        </div>
</section>
<!-- galerija --> 


            
<?php
			$naslov = get_field('komentarji_naslov'); 
			
            $ime1 = get_field('komentarji_ime_1'); 
            $ime2 = get_field('komentarji_ime_2');
			$ime3 = get_field('komentarji_ime_3'); 
			$ime4 = get_field('komentarji_ime_4'); 
			$ime5 = get_field('komentarji_ime_5'); 
			$ime6 = get_field('komentarji_ime_6');  	

			$kom1 = get_field('komentar_mnenje_1');
			$kom2 = get_field('komentar_mnenje_2');  	
			$kom3 = get_field('komentar_mnenje_3');  	
			$kom4 = get_field('komentar_mnenje_4');  	
			$kom5 = get_field('komentar_mnenje_5');  	
			$kom6 = get_field('komentar_mnenje_6');  	  		

			$komentar_zvezdice_1 = "https://mistermega.si/wp-content/uploads/2020/01/Stars_5.png";
			$komentar_zvezdice_2 = "https://mistermega.si/wp-content/uploads/2020/01/Stars_5.png";
			$komentar_zvezdice_3 = "https://mistermega.si/wp-content/uploads/2020/01/Stars_5.png";
			$komentar_zvezdice_4 = "https://mistermega.si/wp-content/uploads/2020/01/Stars_5.png";
			$komentar_zvezdice_5 = "https://mistermega.si/wp-content/uploads/2020/01/Stars_5.png";
			$komentar_zvezdice_6 = "https://mistermega.si/wp-content/uploads/2020/01/Stars_5.png";
			
			$img1= get_field('komentar_slika_1'); 
			$img2= get_field('komentar_slika_2'); 
			$img3= get_field('komentar_slika_3'); 
			$img4= get_field('komentar_slika_4'); 
			$img5= get_field('komentar_slika_5'); 
			$img6= get_field('komentar_slika_6'); 

			$kom_ozadje = get_field('komentar_barva_ozadja_komentar');  	
?>
            
<section style=" background: <?php echo $kom_ozadje; ?> !important;" class="section comments">
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
                

           </div>
                
            </div>
        </div>

</section>
            
            




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

	

    <footer style=" display: block !important;" class="footer">
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
		

<section style=" background: <?php echo $mobilni_footer_tekst_kupi_copy; ?> !important;" id="sticky_chat" class="custom-sticky-add-to-cart" style="display: none;">
    <div class="col-full">
        <div data-id="TTAVPOWtmnh" class="livechat_button"><a href="#"></a></div>
        <div class="ssatc-content">
            <span class="price">
                <span class="price">
					
					<?php 
					
					$mobilni_footer_cena_1 = $regular_price;
					$mobilni_footer_cena_2 = $sale_price;  
					

					if( $domain2 == "MISTERMEGA.SI" || $domain2 == "mistermega.si" || $domain2 == "cartflip.si" || $domain2 == "CARTFLIP.SI" ) {
						$mobilni_footer_cena_1 = "";  
						$mobilni_footer_cena_2 = $regular_price;
					}
				
					?>
					
					<?php if( $mobilni_footer_cena_1 != null && $mobilni_footer_cena_1 != "" ): ?>
                    <del>
                        <span style="color: <?php echo $cena_barva_1; ?> !important;" class="woocommerce-Price-amount amount"><?php echo $mobilni_footer_cena_1; ?><?php echo $valuta; ?>
                        </span>
                    </del>
					<?php endif; ?>
					
                    <ins>
                        <span style="color: <?php echo $cena_barva_2; ?> !important;" class="woocommerce-Price-amount amount"><?php echo $mobilni_footer_cena_2; ?><?php echo $valuta; ?>
                        </span>
                    </ins>
                </span>
            </span>
            <a  href="#buy" style="    margin-right: -30px; background: <?php echo $mobilni_footer_tekst_kupi_copy2; ?> !important;" class="button alt" ><strong><?php echo $mobilni_footer_tekst_kupi; ?></strong></a>
        </div>
    </div>
</section>


  
  
<script  src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<script  src="/hr/wp-content/themes/flatsome/landing-builder/slick.min.js" ></script>
<script  src="/hr/wp-content/themes/flatsome/landing-builder/jquery.countdown.min.js" ></script>



<?php if( $product_type == "dvojne" ): ?>

<script>function submitproductform() {
                document.getElementById("order_link").click();
            }</script>

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
		
		<script>
    $(document).ready(function () {
		
		//console.log("p: " + prices[1]);

	

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


		setTimeout(function(){
		//$( ".mp_product_qty_2 " ).click();
		}, 130);

    });
</script>


<?php elseif( $product_type == "enojne" ): ?>

<script>
			
            var basePrice = jQuery('#price11').val();
            var prices = {"1":jQuery('#price11').val(),"2":jQuery('#price22').val(),"3":jQuery('#price22').val()};
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
			
</script>
<script>
    $(document).ready(function() {

				$(".collapsible-trigger").click(function(){
				  $(".collapsible").slideToggle();
				});
				
				$('#order_link').on('click', function(e){
						$("#nakupna").submit()

				});

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
<script>
    $(document).ready(function () {
		
        $('.price_qty_1').text(prices[1] + currency);
        $('.price_qty_2').text((prices[1] * 0.8).toFixed(2) + currency);
        $('.price_qty_3').text((prices[1] * 0.7).toFixed(2) + currency);
		
          $('.full-price').text(jQuery('#pricefull').val() + currency);
		  
          $('.discounted-price').text( jQuery('#price11').val() + currency);

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
    });
</script>

<script>
    $(document).ready(function () {
		
		//console.log("p: " + prices[1]);

	
	

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
				//console.log("klik zbira");

				var new_href = $("#order_link").attr("href" );
				//console.log(new_href);
				
                var qqq = getQueryVariable(new_href, 'quantity');
	
				if( qqq == false || qqq == null ) {
					qqq = 1;
				}

				var base = jQuery('#basee').val();
				
				var this_option = $(this).data("thisid") 

				
				base = base + "/?add-to-cart=" + this_option + "&quantity=" + qqq;
				
				 
				 var string2 = " #" + $(this).parent().parent().parent().parent().attr('id') + " .product_qty2";
				 
				// console.log(string2);
		
			  $("#order_link").attr("href", base);
			 
			 $(  string2 ).removeClass("act_variation");

			  $(this).addClass("act_variation");
		});

    });
</script>


<?php else: ?>
<!-- simple -->
<script>
			
            var basePrice = jQuery('#price11').val();
            var prices = {"1":jQuery('#price11').val(),"2":jQuery('#price22').val(),"3":jQuery('#price22').val()};
			
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
			
        </script>
<script>
            $(document).ready(function() {
				
				$(".collapsible-trigger").click(function(){
				  $(".collapsible").slideToggle();
				});
				
				//nakupna
				
				$('#order_link').on('click', function(e){
						$("#nakupna").submit()
				});

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
<script>
    $(document).ready(function () {
		
        $('.price_qty_1').text(prices[1] + currency);
        $('.price_qty_2').text((prices[1] * 0.8).toFixed(2) + currency);
        $('.price_qty_3').text((prices[1] * 0.7).toFixed(2) + currency);
        
          $('.full-price').text(jQuery('#pricefull').val() + currency);
		  
          $('.discounted-price').text( jQuery('#price11').val() + currency);

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
    });
</script>

<!-- simple -->
<?php endif; ?>


<script type="text/javascript">

var tomorrow = new Date();
tomorrow.setDate(tomorrow.getDate() + 1);

var n = tomorrow.getDate();
console.log(n);

var m = (tomorrow.getMonth())+1;
console.log(m);

var date1 = "2021/" + m + "/" + n;

	
  $("#getting-started").countdown( date1 , function(event) {
    $(this).html(
      event.strftime( ' <span> %D </span><span> %H </span><span> %M </span><span> %S </span>' )
    );
  });
</script>


<?php get_footer(); ?>
