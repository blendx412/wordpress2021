<?php
/**
 * Template Name: Statistic
 *
 */

get_header();
?>

<h1 style="text-align: center; margin-top: 30px;">Statistika</h1>
<!-- All orders -->

	<?php

	//Februar
	$args_f = array(
		'limit'           => 2500,
		'date_query' => array(
		'after'     => '02/01/2021' . ' 00:00:00',
		'before'    => '02/28/2021' . ' 23:59:59',
		),
		'status'          => array('wc-processing')
	);
	$query_f = new WC_Order_Query( $args_f );
	$orders_f = $query_f->get_orders();
	//$billing_first_name = $orders->get_billing_first_name();
	//echo 'test '.$billing_first_name;
	

	$counter_kolo_srece_f = 0;
	$counter_kolo_srece_izdelki_f = 0;
	$kolo_izdelki_f = 0;

	foreach($orders_f as $o) {

		// d($o->get_items());
		// die();

		$order_f = wc_get_order( $o );
		$billing_name_f = $order_f->get_billing_first_name();

		$kolo_items_f = $o->get_items();	//polje izdelkov, kupljenih s kolom srece
		
		if ($billing_name_f != "test" && $billing_name_f != "TEST" && $billing_name_f != "Test"){
			foreach( $kolo_items_f as $k ) {
				if ($k->get_total() == "60"){
					$kolo_izdelki_f += 1;

				}
				
			}	
	
		

	


		if( $o->GET_META( "jackopot" ) == "1" ) {
			
			$counter_kolo_srece_f += 1;
		}
			}
	}
	
	$odstotek_f = ($counter_kolo_srece_f/count($orders_f))*100;

	//Januar
	$args = array(
	  'limit'           => 2500,
	  'date_query' => array(
		'after'     => '01/01/2021' . ' 00:00:00',
		'before'    => '01/31/2021' . ' 23:59:59',
		),
	  'status'          => array('wc-processing')
	);
	$query = new WC_Order_Query( $args );
	$orders = $query->get_orders();
	//$billing_first_name = $orders->get_billing_first_name();
	//echo 'test '.$billing_first_name;
	

	$counter_kolo_srece = 0;
	$counter_kolo_srece_izdelki = 0;
	$kolo_izdelki = 0;

	foreach($orders as $o) {

		// d($o->get_items());
		// die();

		$order = wc_get_order( $o );
		$billing_name = $order->get_billing_first_name();

		$kolo_items = $o->get_items();	//polje izdelkov, kupljenih s kolom srece
		
		if ($billing_name != "test" && $billing_name != "TEST" && $billing_name != "Test"){
			foreach( $kolo_items as $k ) {
				if ($k->get_total() == "60"){
					$kolo_izdelki += 1;

				}
				
			}	
	
		

	


		if( $o->GET_META( "jackopot" ) == "1" ) {
			
			$counter_kolo_srece += 1;
		}
			}
	}
	
	$odstotek = ($counter_kolo_srece/count($orders))*100;

	//December
	$today_date_start_d = '12/01/2020' . ' 00:00:00';
	$today_date_end_d = '12/31/2020' . ' 23:59:59';

	$args1 = array(
	  'limit'           => 2500,
	  'date_query' => array(
		'after'     => $today_date_start_d,
		'before'    =>$today_date_end_d,
		),
	  'status'          => array('wc-processing')
	);
	$query1 = new WC_Order_Query( $args1 );
	$orders_d = $query1->get_orders();


	$counter_kolo_srece_d = 0;
	$counter_kolo_srece_izdelki_d = 0;
	$kolo_izdelki_d = 0;

	foreach($orders_d as $o) {

		// d($o->get_items());
		// die();

		$order_d = wc_get_order( $o );
		$billing_name_d = $order_d->get_billing_first_name();

		$kolo_items_d = $o->get_items();	//polje izdelkov, kupljenih s kolom srece

		if ($billing_name_d != "test" && $billing_name_d != "TEST" && $billing_name_d != "Test"){
			foreach( $kolo_items_d as $k ) {
			if ($k->get_total() == "60"){
				$kolo_izdelki_d += 1;
			
			}
			// $test = $k->get_total();
			// 	echo $test.'<br/>';
		}


		if( $o->GET_META( "jackopot" ) == "1" ) {
			
			$counter_kolo_srece_d += 1;
		}
		}
	
		
		
	}
	$odstotek_d = ($counter_kolo_srece_d/count($orders_d))*100;


	//November
	$today_date_start_n = '11/01/2020' . ' 00:00:00';
	$today_date_end_n = '11/30/2020' . ' 23:59:59';

	$args2 = array(
	  'limit'           => 2500,
	  'date_query' => array(
		'after'     => $today_date_start_n,
		'before'    =>$today_date_end_n,
		),
	  'status'          => array('wc-processing')
	);
	$query2 = new WC_Order_Query( $args2 );
	$orders_n = $query2->get_orders();
	//echo 'nov '.count($orders_n).'<br/>';

	$counter_kolo_srece_n = 0;
	$counter_kolo_srece_izdelki_n = 0;
	$kolo_izdelki_n = 0;

	foreach($orders_n as $o) {

		// d($o->get_items());
		// die();

		$order_n = wc_get_order( $o );
		$billing_name_n = $order_n->get_billing_first_name();

		//echo 'billing'.

		$kolo_items_n = $o->get_items();	//polje izdelkov, kupljenih s kolom srece
		
		
		
		if ($billing_name_n != "test" && $billing_name_n != "TEST" && $billing_name_n != "Test"){
			foreach( $kolo_items_n as $k ) {
			if ($k->get_total() == "60"){

				$kolo_izdelki_n += 1;
				
			}
		}


		if( $o->GET_META( "jackopot" ) == "1" ) {
			
				$counter_kolo_srece_n += 1;
			}
		}
		
		
	}

	$odstotek_n = ($counter_kolo_srece_n/count($orders_n))*100;

	/*
	$args2 = array(
		'limit'           => 100,
		'date_query' => array(
		  'after'     => $today_date_start,
		  'before'    =>$today_date_end,
		  ),
		'status'          => array('wc-processing', 'wc-completed'),
		
		'meta_query'  => array(
	
			 'key'     => 'jackopot',  // which meta to query
			 'value'   => "1",  // value for comparison,
			  'compare' => '=',

			
			),
	  );

	  $query2 = new WC_Order_Query( $args2 );
	  $orders_kolo = $query2->get_orders();
	*/


	$sum_vsa = count($orders)+count($orders_d)+count($orders_n)+count($orders_f);
	$sum_kolo = $counter_kolo_srece+$counter_kolo_srece_d+$counter_kolo_srece_n+$counter_kolo_srece_f;
	$sum_perc = ($sum_kolo/$sum_vsa)*100;

	 ?>

<table>
	<tr>
		<th>Mesec</th>
		<th>Vsa naročila</th>
		<th>Kolo sreče</th>
		<th>Kolicina izdelkov, kupljenih preko kolesa srece</th>
	  	<th>Odstotek narocil (kolo srece / vsa narocila)</th>
	</tr>
	<tr>
	  <td>Februar</td>
	  <td><?php echo count($orders_f)?></td>
	  <td><?php echo $counter_kolo_srece_f?></td>
	  <td><?php echo $kolo_izdelki_f?></td>
	  <td><?php echo $odstotek_f?>%</td>
	</tr>
	<tr>
	  <td>Januar</td>
	  <td><?php echo count($orders)?></td>
	  <td><?php echo $counter_kolo_srece?></td>
	  <td><?php echo $kolo_izdelki?></td>
	  <td><?php echo $odstotek?>%</td>
	</tr>
	<tr>
	  <td>December</td>
	  <td><?php echo count($orders_d)?></td>
	  <td><?php echo $counter_kolo_srece_d?></td>
	  <td><?php echo $kolo_izdelki_d?></td>
	  <td><?php echo $odstotek_d?>%</td>
	</tr>
	<tr>
	  <td>November</td>
	  <td><?php echo count($orders_n)?></td>
	  <td><?php echo $counter_kolo_srece_n?></td>
	  <td><?php echo $kolo_izdelki_n?></td>
	  <td><?php echo $odstotek_n?>%</td>
	</tr>
	<tr>
	  <td></td>
	  <td><?php echo $sum_vsa?></td>
	  <td><?php echo $sum_kolo?></td>
	  <td><?php echo $kolo_izdelki+$kolo_izdelki_d+$kolo_izdelki_n+$kolo_izdelki_f?></td>
	  <td><?php echo $sum_perc?>%</td>
	</tr>
</table>


<?php
get_footer();

?>
