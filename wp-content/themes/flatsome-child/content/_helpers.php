<?php
//outputs a string with print_r() inside of <pre> tags
function epr( $str = null ) {
	echo "<pre>";
	print_r( $str );
	echo "</pre>";
} //end function epr()


function addDays($days,$format="d.m.Y"){
    for($i=0;$i<$days;$i++){
        $day = date('N',strtotime("+".($i+1)."day"));
        if($day>5)
            $days++;
    }
    return date($format,strtotime("+$i day"));
}

function get_shipping_date() {
	
		$shippingDateTime = "";
	$check = get_field("pc_spremeni_datum_dostave");
	
	
	if($check != true) {
	
	$duration = '1';	
	if(date("G") >= 11) {  // 14=13
	  $duration = '2';   // popoldne
	}
	
	$nowDayEN = date( 'l',  strtotime('now'));
	$shippingTime = addDays($duration);
	$datetime = new DateTime($shippingTime);
    $shippingDate = $datetime->format('d.m.Y');
    $dayEN = date( 'l',  strtotime($shippingTime));
    $localDay = return_local_day_name($dayEN);
    $shippingDateTime = [
    	'day_text' => $localDay,
    	'day_date' => $shippingDate
    ];
    return $shippingDateTime;
	
	 
	} 
	else {
		
		$tmp = get_field("pc_cas_dostave_od");
		$tmp = 	str_replace("/",".",$tmp);
		$shippingDateTime = [
    	'day_text' => "",
    	'day_date' => $tmp
		];
		return $shippingDateTime;
		
	}
}

function get_shipping_date2() {
	
		$shippingDateTime = "";
	$check = get_field("pc_spremeni_datum_dostave");
	
	
	if($check != true) {
	
	$duration = '5';
	if(date("G") >= 11) {  // 2
	  $duration = '6';   // 3
	}
	
	$nowDayEN = date( 'l',  strtotime('now'));
	$shippingTime = addDays($duration);
	$datetime = new DateTime($shippingTime);
    $shippingDate = $datetime->format('d.m.Y');
    $dayEN = date( 'l',  strtotime($shippingTime));
    $localDay = return_local_day_name($dayEN);
    $shippingDateTime = [
    	'day_text' => $localDay,
    	'day_date' => $shippingDate
    ];
    return $shippingDateTime;
	
		}
	
	else {
		
		$tmp = get_field("pc_cas_dostave_do");
		$tmp = 	str_replace("/",".",$tmp);
		$shippingDateTime = [
    	'day_text' => "",
    	'day_date' => $tmp
		];
		return $shippingDateTime;
		
	}
	
}







function return_local_day_name($en_name) {
	switch ($en_name) {
		case 'Monday':
			return 'ponedjeljak';
			break;
		case 'Tuesday':
			return 'utorak';
			break;
		case 'Wednesday':
			return 'srijeda';
			break;
		case 'Thursday':
			return 'četvrtak';
			break;
		case 'Friday':
			return 'petek';
			break;
		case 'Saturday':
			return 'subota';
			break;
		case 'Sunday':
			return 'nedjelja';
			break;

		default:
			return '';
			break;
	}
}

?>
