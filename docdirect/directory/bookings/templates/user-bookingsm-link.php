<?php
/**
 * User Invoices
 * return html
 */

global $current_user, $wp_roles,$userdata,$post,$paged;
$dir_obj	= new DocDirect_Scripts();
$user_identity	= $current_user->ID;
$url_identity	= $user_identity;
$rutapagina = "https://dingdoc.com.gt";
//$rutapagina = "https://localhost";
if( isset( $_GET['identity'] ) && !empty( $_GET['identity'] ) ){
	$url_identity	= $_GET['identity'];
}

if (function_exists('fw_get_db_settings_option')) {
	$currency_select = fw_get_db_settings_option('currency_select');
} else{
	$currency_select = 'USD';
}


if (empty($paged)) $paged = 1;
$limit = 50;//get_option('posts_per_page');


$meta_query_args[] = array(
							'key'     => 'bk_user_to',
							'value'   => $current_user->ID,
							'compare'   => '=',
							'type'	  => 'NUMERIC'
						);

$nuevourl =  $_SERVER['REQUEST_URI'];

function getweek_first_last_date($date)
{
    $cur_date = strtotime($date); // Change to whatever date you need
    // Get the day of the week: Sunday = 0 to Saturday = 6
    $dotw = date('w', $cur_date);
    if($dotw>1)
    {
        $pre_monday  =  $cur_date-(($dotw-1)*24*60*60);
        $next_sunday = $cur_date+((7-$dotw)*24*60*60);

    }
    else if($dotw==1)
    {
        $pre_monday  = $cur_date;
        $next_sunday =  $cur_date+((7-$dotw)*24*60*60);


    }
    else if($dotw==0)
    {
        $pre_monday  =$cur_date -(6*24*60*60);;
        $next_sunday = $cur_date;


    }

    $date_array =   array();
    $date_array['start'] = $pre_monday;
    $date_array['end'] = $next_sunday;
    return $date_array;
}



if( !empty( $_GET['by_date'] ) ){
	$hoy = $_GET['by_date'];
	$fechasrangos = getweek_first_last_date($hoy);
	$dia1 = date('Y-m-d',$fechasrangos['start']);//"2020-03-01";
	$dia2 = date('Y-m-d',$fechasrangos['end']);//"2020-03-31";


	$pos = strpos($nuevourl, '&by_date');
	if ($pos === false) {
	    	//$manana = $nuevourl."&by_date=".date('Y-m-d', strtotime($hoy . ' +1 day'));
	} else {
	   	$manana = substr($nuevourl, 0, $pos).'&by_date='.date('Y-m-d', strtotime($dia2 . ' +1 day'));
			$ayer = substr($nuevourl, 0, $pos).'&by_date='.date('Y-m-d', strtotime($dia1. ' -1 day'));
			$info2 =   substr($nuevourl, 0, $pos).'&by_date=';
			$info = str_replace("bookingsm", "bookings", $info2);

	}
	/*$meta_query_args[] = array(
							'key'     => 'bk_timestamp',
							'value'   => strtotime($_GET['by_date']),
							'compare'   => '=',
							'type'	  => 'NUMERIC'
						);*/

						$meta_query_args[] = array(
												array(
														'key' => 'bk_timestamp',
														'value' => array(strtotime($dia1), strtotime($dia2) ),
														'compare' => 'BETWEEN',
														'type' => 'NUMERIC'
												),
										);
}else{//cambio
	$hoy = date("Y-m-d");
	$fechasrangos = getweek_first_last_date($hoy);
	$dia1 = date('Y-m-d',$fechasrangos['start']);//"2020-03-01";
	$dia2 = date('Y-m-d',$fechasrangos['end']);//"2020-03-31";
	//echo $dia1.'<br>'.$dia2;

	$manana = $nuevourl."&by_date=".date('Y-m-d', strtotime($dia2. ' +1 day'));
	$ayer = $nuevourl."&by_date=".date('Y-m-d', strtotime($dia1 . ' -1 day'));
	$info2 =   $nuevourl.'&by_date=';
	$info = str_replace("bookingsm", "bookings", $info2);
	$nuevourl =  $_SERVER['REQUEST_URI'];

	/*$meta_query_args[] = array(
							'key'     => 'bk_timestamp',
							'value'   => strtotime($hoy),
							'compare'   => '=',
							'type'	  => 'NUMERIC'
						);*/

						$meta_query_args[] = array(
								        array(
								            'key' => 'bk_timestamp',
								            'value' => array(strtotime($dia1), strtotime($dia2) ),
								            'compare' => 'BETWEEN',
								            'type' => 'NUMERIC'
								        ),
								    );
}

$show_posts    = get_option('posts_per_page') ? get_option('posts_per_page') : '-1';
$args 		= array( 'posts_per_page' => $show_posts,
					 'post_type' => 'docappointments',
					 'post_status' => 'publish',
					 'ignore_sticky_posts' => 1,
					 'order'	=> 'DESC',
					 'orderby'	=> 'ID',
					 'paged' => $paged,
					);


if( !empty( $meta_query_args ) ) {
	$query_relation = array('relation' => 'AND',);
	$meta_query_args	= array_merge( $query_relation,$meta_query_args );
	$args['meta_query'] = $meta_query_args;
}

$query 		= new WP_Query($args);
$count_post = $query->found_posts;

$dir_profile_page = '';
if (function_exists('fw_get_db_settings_option')) {
	$dir_profile_page = fw_get_db_settings_option('dir_profile_page', $default_value = null);
}

$profile_page = isset($dir_profile_page[0]) ? $dir_profile_page[0] : '';
?>


<div class="doc-booking-listings dr-bookings">
  <div class="tg-dashboard tg-docappointmentlisting tg-haslayout">
    <div class="tg-heading-border tg-small">
			<h2><i class="fa fa-calendar-check-o fa-2x" aria-hidden="true"></i><br>
				 Citas</h2>
			<span style="width: 100%; text-align:center; display:block"><?php echo $dia1.'  -  '.$dia2; ?></span>
    </div>
    <form class="tg-formappointmentsearch" action="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'bookingsm', $user_identity); ?>&page_id=4423" method="get">
      <fieldset>
        <!-- <h4><?php esc_html_e('Search Here','docdirect');?>:</h4> -->
        <div class="form-group">
					<input type="hidden" class="" value="4423" name="page_id">
          <input type="hidden" class="" value="bookingsm" name="ref">
          <input type="hidden" class="" value="<?php echo intval( $user_identity ); ?>" name="identity">
          <input type="text" class="form-control booking-search-date" value="<?php echo isset( $_GET['by_date'] ) && !empty( $_GET['by_date'] ) ? $_GET['by_date'] : '';?>" name="by_date" placeholder="<?php esc_html_e('Search by date','docdirect');?>">
          <button type="submit"><i class="fa fa-search"></i></button>
        </div>
      </fieldset>
    </form>
		<div class="diasbar">
			<a class="btnb step-prevb" target="_self" href="<?php echo $rutapagina; ?><?php  echo $ayer; ?>"> <i class="fa fa-chevron-left fa-2x"></i> </a>
			<a class="btnb step-nextn" target="_self" href="<?php echo $rutapagina; ?><?php  echo $manana; ?>"> <i class="fa fa-chevron-right fa-2x"></i> </a>
		</div>
		<?php
		$horainicio = 3;
		$horafin=22;
		$espacioh = 15;
		function diaespacios($horainicio, $horafin, $espacioh){
			for ($i=$horainicio; $i<=$horafin; $i++){
				$espacionum = $i-$horainicio;
				echo "<div  class='".$i."'>".$i."</div>";
			}
		}
		 ?>
		 <style>
		 .calsemana .dia{vertical-align: top !important; }
		 .calsemana .dia.titulo div {vertical-align: top !important;background: transparent !important;
				 position: relative;line-height: 15px; font-size: 10px;}
		 .calsemana .dia div{height:<?php echo $espacioh; ?>px;display:block; background: green; position: absolute;
    width: 15px;}
		 .calsemana .dia div.pending {height:<?php echo $espacioh; ?>px;display:block; background: yellow; }
		 </style>
    <div class="tg-appointmenttable">
			<table class="table">
				<thead class="thead-inverse">
					<tr>
						<td>H</td>
						<td>L</td>
						<td>M</td>
						<td>M</td>
						<td>J</td>
						<td>V</td>
						<td>S</td>
						<td>D</td>
					</tr>
				</thead>
				<tbody class="calsemana">
					  <td  class="dia titulo"><?php diaespacios($horainicio, $horafin, $espacioh); ?></td>
						<td  class="dia" id="Mon">
							<!-- <div style="top:30px;">1</div>
							<div style="top:120px;">2</div> -->
						</td>
						<td  class="dia" id="Tue"></td>
						<td  class="dia" id="Wed"></td>
						<td  class="dia" id="Thu"></td>
						<td  class="dia" id="Fri"></td>
						<td  class="dia" id="Sat"></td>
						<td  class="dia" id="Sun"></td>
				<tbody>
			</table>
      <table class="table">
        <!-- <thead class="thead-inverse">
          <tr>
            <th><?php //esc_html_e('id.','docdirect');?></th>
            <th>Paciente</th>
            <th>Ver</th>
            <th></th>
          </tr>
        </thead> -->
        <tbody>
        <?php
			$services_cats = get_user_meta($user_identity , 'services_cats' , true);
			$booking_services = get_user_meta($user_identity , 'booking_services' , true);
			$date_format = get_option('date_format');
			$time_format = get_option('time_format');

			$counter	= 0;
			if( $query->have_posts() ):
			 while($query->have_posts()) : $query->the_post();
			 	global $post;

				$counter++;
			    $bk_code          = get_post_meta($post->ID, 'bk_code',true);
				$bk_category      = get_post_meta($post->ID, 'bk_category',true);
				$bk_service       = get_post_meta($post->ID, 'bk_service',true);
				$bk_booking_date  = get_post_meta($post->ID, 'bk_booking_date',true);
				$bk_slottime 	  = get_post_meta($post->ID, 'bk_slottime',true);
				$bk_subject       = get_post_meta($post->ID, 'bk_subject',true);
				$bk_username      = get_post_meta($post->ID, 'bk_username',true);
				$bk_userphone 	  = get_post_meta($post->ID, 'bk_userphone',true);
				$bk_useremail     = get_post_meta($post->ID, 'bk_useremail',true);
				$bk_booking_note  = get_post_meta($post->ID, 'bk_booking_note',true);
				$bk_payment       = get_post_meta($post->ID, 'bk_payment',true);
				$bk_user_to       = get_post_meta($post->ID, 'bk_user_to',true);
				$bk_timestamp     = get_post_meta($post->ID, 'bk_timestamp',true);
				$bk_status        = get_post_meta($post->ID, 'bk_status',true);
				$bk_user_from     = get_post_meta($post->ID, 'bk_user_from',true);
				$bk_currency 	  = get_post_meta($post->ID, 'bk_currency', true);
				$bk_paid_amount   = get_post_meta($post->ID, 'bk_paid_amount', true);
				$bk_transaction_status = get_post_meta($post->ID, 'bk_transaction_status', true);

				$payment_amount  = $bk_currency.$bk_paid_amount;

				$time = explode('-',$bk_slottime);


		  ?>
          <tr class="booking-<?php echo intval( $post->ID );?>" style="display:none">

            <td>
								 <script>
								 	function reenviar(bookingdate){

										//alert('<?php echo $rutapagina.$info;?>'+bookingdate);
										window.location.href = '<?php echo $rutapagina.$info;?>'+bookingdate;
									}
								 </script>

									<span><?php

									echo $bk_booking_date;
									$timestamp = strtotime($bk_booking_date);
									$day = date('D', $timestamp);
									$hora =  (int)substr($time[0], 0, 2);
									$espacio = ($hora-$horainicio)*$espacioh;
									if ($bk_status != "cancelled"){
										echo "<script>
										document.getElementById('".$day."').innerHTML +='<div onclick=\"reenviar(\'".$bk_booking_date."\')\" class=\"".$bk_status."\" id=\"".$day.$hora."\" style=\"margin-top:".$espacio."px;\">&nbsp;</div;';
										</script>";
									}

								  echo $day;
									echo '<br>';
									echo substr($time[0], 0, 2);
									echo '<br>';
									echo $bk_status;
									?>
								</span>

						</td>


          </tr>

          <?php
		  endwhile; wp_reset_postdata();

		  ?>
		<?php endif;?>
        </tbody>
      </table>
      <div class="col-md-xs-12">
		<?php
            if( $count_post > $limit ) {
                docdirect_prepare_pagination($count_post,$limit);
            }
        ?>
      </div>
    </div>
  </div>
</div>
