<?php
/**
 * User Invoices
 * return html
 */

global $current_user, $wp_roles,$userdata,$post,$paged;
$dir_obj	= new DocDirect_Scripts();
$user_identity	= $current_user->ID;
$url_identity	= $user_identity;

if( isset( $_GET['identity'] ) && !empty( $_GET['identity'] ) ){
	$url_identity	= $_GET['identity'];
}

if (function_exists('fw_get_db_settings_option')) {
	$currency_select = fw_get_db_settings_option('currency_select');
} else{
	$currency_select = 'USD';
}


if (empty($paged)) $paged = 1;
$limit = get_option('posts_per_page');


$meta_query_args[] = array(
							'key'     => 'bk_user_from',
							'value'   => $current_user->ID,
							'compare'   => '=',
							'type'	  => 'NUMERIC'
						);

$nuevourl =  $_SERVER['REQUEST_URI'];

if( !empty( $_GET['by_date'] ) ){
	$hoy = $_GET['by_date'];
	$pos = strpos($nuevourl, '&by_date');
	if ($pos === false) {
	    	//$manana = $nuevourl."&by_date=".date('Y-m-d', strtotime($hoy . ' +1 day'));
	} else {
	   	$manana = substr($nuevourl, 0, $pos).'&by_date='.date('Y-m-d', strtotime($hoy . ' +1 day'));
			$ayer = substr($nuevourl, 0, $pos).'&by_date='.date('Y-m-d', strtotime($hoy . ' -1 day'));
	}

	$meta_query_args[] = array(
							'key'     => 'bk_timestamp',
							'value'   => strtotime($_GET['by_date']),
							'compare'   => '=',
							'type'	  => 'NUMERIC'
						);
}else{//cambio
	$hoy = date("Y-m-d");
	$manana = $nuevourl."&by_date=".date('Y-m-d', strtotime($hoy . ' +1 day'));
	$ayer = $nuevourl."&by_date=".date('Y-m-d', strtotime($hoy . ' -1 day'));
	$nuevourl =  $_SERVER['REQUEST_URI'];

	$meta_query_args[] = array(
							'key'     => 'bk_timestamp',
							'value'   => strtotime($hoy),
							'compare'   => '=',
							'type'	  => 'NUMERIC'
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
    </div>
    <form class="tg-formappointmentsearch" action="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'bookings', $user_identity); ?>&page_id=4423" method="get">
      <fieldset>
        <!-- <h4><?php esc_html_e('Search Here','docdirect');?>:</h4> -->
        <div class="form-group">
					<input type="hidden" class="" value="4423" name="page_id">
          <input type="hidden" class="" value="bookings" name="ref">
          <input type="hidden" class="" value="<?php echo intval( $user_identity ); ?>" name="identity">
          <input type="text" class="form-control booking-search-date" value="<?php echo isset( $_GET['by_date'] ) && !empty( $_GET['by_date'] ) ? $_GET['by_date'] : '';?>" name="by_date" placeholder="<?php esc_html_e('Search by date','docdirect');?>">
          <button type="submit"><i class="fa fa-search"></i></button>
        </div>
      </fieldset>
    </form>
		<div class="diasbar">
			<a class="btnb step-prevb" target="_self" href="https://dingdoc.com.gt<?php  echo $ayer; ?>"> <i class="fa fa-chevron-left fa-2x"></i> </a>
			<a class="btnb step-nextn" target="_self" href="https://dingdoc.com.gt<?php  echo $manana; ?>"> <i class="fa fa-chevron-right fa-2x"></i> </a>
		</div>
    <div class="tg-appointmenttable">
      <table class="table">
        <!-- <thead class="thead-inverse">
          <tr>
            <th><?php //esc_html_e('id.','docdirect');?></th>
            <th>Doctor</th>
            <th>Ver</th>
            <th></th>
          </tr>
        </thead> -->
        <tbody>
        <?php

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

				$services_cats = get_user_meta($bk_user_to , 'services_cats' , true);
				$booking_services = get_user_meta($bk_user_to , 'booking_services' , true);
				$doc_name = docdirect_get_username($bk_user_to);
				$address			 = get_user_meta($bk_user_to , 'address' , true);

				$payment_amount  = $bk_currency.$bk_paid_amount;

				$time = explode('-',$bk_slottime);

				$trClass	= 'booking-odd';
				if( $counter % 2 == 0 ){
					$trClass	= 'booking-even';
				}
		  ?>
          <tr class="<?php echo esc_attr( $trClass );?> booking-<?php echo intval( $post->ID );?>">
						<!-- <td data-name="id">
							<?php
							//echo esc_attr( docdirect_prepare_order_status( 'value',$bk_status ) );
							$replacements2 = array(
									'Complete' => 'Completado',
									'Pendiente' => 'Pendiente',
									'Rejected' => 'Cancelado'
							);

							$estado =esc_attr( docdirect_prepare_order_status( 'value',$bk_status ) );
							$estado2 = str_replace(array_keys($replacements2), $replacements2, $estado);
							/*if ($estado2 == 'Completado'){
								echo '<i class="fa fa-check"></i>';
							}*/
							?><?php //echo esc_attr( $bk_code );?>

						</td> -->
						<td data-name="subject" class="citassubject get-detail"  href="javascript:;">
							<span><?php echo esc_attr( $bk_code );?></span>
							<span><?php echo esc_attr( $doc_name);?></span>
							<?php if( !empty( $bk_booking_date ) ){?>
									<span><?php
									//echo date($date_format,strtotime($bk_booking_date));
									$replacements = array(
											'January' => 'Enero',
											'February' => 'Febrero',
											'March' => 'Marzo',
											'April' => 'Abril',
											'May' => 'Mayo',
											'June' => 'Junio',
											'July' => 'Julio',
											'August' => 'Agosto',
											'September' => 'Septiembre',
											'October' => 'Octubre',
											'November' => 'Noviembre',
											'December' => 'Diciembre'
									);
									$fecha = date($date_format,strtotime($bk_booking_date));

									echo str_replace(array_keys($replacements), $replacements, $fecha);
									?>
								</span>
							<?php }?>
								<span><?php echo date_i18n($time_format,strtotime('2016-01-01 '.$time[0]) );?>&nbsp;-&nbsp;<?php echo date_i18n($time_format,strtotime('2016-01-01 '.$time[1]) );?></span>
						</td>
				<!-- <td data-name="phone"><?php //echo esc_attr( $bk_userphone );?></td> -->
            <!-- <td data-name="notes"><a class="get-detail" href="javascript:;"><i class="fa fa-eye"></i></a></td> -->
            <td style="width: 33%;">
							<?php if( isset( $bk_status ) && $bk_status == 'approved' ){?>

									<a class="tg-btnclose get-process tg-btn-reagendar" data-type="cancel" data-type2="reagendar" data-url="<?php echo get_author_posts_url($bk_user_to); ?>&tipo=link" data-id="<?php echo intval( $post->ID );?>" href="javascript:;">
										<i class="fa fa-refresh" aria-hidden="true"></i>
									</a>
									<a class="tg-btnclose get-process" data-type="cancel" data-id="<?php echo intval( $post->ID );?>" href="javascript:;">
										<i class="fa fa-times" aria-hidden="true"></i>
									</a>
                	<a class="tg-btncheck appointment-actioned fa fa-check" href="javascript:;"><?php esc_html_e('Approved','docdirect');?></a>
                <?php }else if( isset( $bk_status ) && $bk_status == 'cancelled' ){?>
                	<a class="tg-btncheck appointment-actioned" href="javascript:;">Cancelada</a>
               <?php }else if( isset( $bk_status ) && $bk_status == 'pending' ){?>
								 <a class="tg-btnclose get-process tg-btn-reagendar" data-type="cancel" data-type2="reagendar" data-url="<?php echo get_author_posts_url($bk_user_to); ?>&tipo=link" data-id="<?php echo intval( $post->ID );?>" href="javascript:;">
									 <i class="fa fa-refresh" aria-hidden="true"></i>
								 </a>
                	<a class="tg-btncheck appointment-actioned appointment-pending" href="javascript:;"><?php esc_html_e('Pending','docdirect');?></a>
                <?php }?>

            </td>
          </tr>
          <tr class="tg-appointmentdetail bk-elm-hide">
            <td colspan="6">
                <div class="appointment-data-wrap">
                    <ul class="tg-leftcol">
                      <!-- <li>
                            <strong><?php //esc_html_e('tracking id','docdirect');?>:</strong>
                            <span><?php //echo esc_attr( $bk_code );?></span>
                      </li> -->
                      <!-- <li>
                            <strong><?php esc_html_e('Doctor Name','docdirect');?>:</strong>
                           <span><?php echo esc_attr( $doc_name );?></span>
                      </li> -->
                      <li>
                           <span><?php esc_html_e('Location of visit','docdirect');?>: <?php echo esc_attr( $address );?></span>
                      </li>
                      <li>
                            <?php if( !empty( $services_cats[$bk_category] ) ){?>
                                <span><?php esc_html_e('Category','docdirect');?>: <?php echo esc_attr( $services_cats[$bk_category] );?></span>
                            <?php }?>
                      </li>
                      <li>
                            <?php if( !empty( $booking_services[$bk_service] ) ){?>
                                <span><?php esc_html_e('Service','docdirect');?>: <?php echo esc_attr( $booking_services[$bk_service]['title'] );?></span>
                            <?php }?>
                      </li>
                      <li><span><?php esc_html_e('Phone','docdirect');?>: <?php echo esc_attr( $bk_userphone );?></span> </li>
                      <li><span><?php esc_html_e('User Name','docdirect');?>: <?php echo esc_attr( $bk_username );?></span> </li>
                      <li><span><?php esc_html_e('Email','docdirect');?>: <?php echo esc_attr( $bk_useremail );?></span> </li>
                      <!-- <li>
                            <strong><?php esc_html_e('Appointment date','docdirect');?>:</strong>
                            <?php //if( !empty( $bk_booking_date ) ){?>
	                                <span><?php
																	//echo date($date_format,strtotime($bk_booking_date));
																	//echo str_replace(array_keys($replacements), $replacements, $fecha);
																	?>
																</span>
	                            <?php //}?>

                      </li> -->
                      <!-- <li>
                            <strong><?php //esc_html_e('Meeting Time','docdirect');?>:</strong>
                            <span><?php //echo date_i18n($time_format,strtotime('2016-01-01 '.$time[0]) );?>&nbsp;-&nbsp;<?php echo date_i18n($time_format,strtotime('2016-01-01 '.$time[1]) );?></span>
                      </li> -->
                      <li>
														<span><?php esc_html_e('Status','docdirect');?>: <?php
														//echo esc_attr( docdirect_prepare_order_status( 'value',$bk_status ) );
														$replacements2 = array(
																'Complete' => 'Completado',
																'Pendiente' => 'Pendiente',
																'Rejected' => 'Cancelado'
														);

														$estado =esc_attr( docdirect_prepare_order_status( 'value',$bk_status ) );
														echo str_replace(array_keys($replacements2), $replacements2, $estado);


														?></span>
                      </li>
                      <li>
                            <span><?php esc_html_e('Payment Type','docdirect');?>: <?php echo esc_attr( docdirect_prepare_payment_type( 'value',$bk_payment ) );?></span>
                      </li>
                      <?php if( !empty( $payment_amount ) ){?>
                          <!-- <li>
                                <strong><?php esc_html_e('Appointment Fee','docdirect');?>:</strong>
                                <span><?php echo esc_attr( $payment_amount );?></span>
                          </li> -->
                      <?php }?>
                      <?php if( !empty( $bk_transaction_status ) ){?>
                          <!-- <li>
                             <strong><?php esc_html_e('Payment Status','docdirect');?>:</strong>
                             <span><?php echo esc_attr( docdirect_prepare_order_status( 'value',$bk_transaction_status ) );?></span>
                          </li> -->
                      <?php }?>
                    </ul>
                    <div class="tg-rightcol">
                      <?php if( !empty( $bk_booking_note ) ){?>
                          <div class="tg-description">
                            <p>Notas: <?php echo esc_attr( $bk_booking_note );?></p>
                          </div>
                      <?php }?>
                    </div>
                  </div>
              </td>
          </tr>
          <?php
		  endwhile; wp_reset_postdata();
		  else:
		  ?>
		  <tr>
			<td colspan="6">
				<?php DoctorDirectory_NotificationsHelper::informations(esc_html__('No appointments found.','docdirect'));?>
			</td>
		  </tr>
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
<script type="text/template" id="tmpl-status-approved">
	<a class="tg-btncheck appointment-actioned fa fa-check" href="javascript:;"><?php esc_html_e('Approved','docdirect');?></a>
</script>
