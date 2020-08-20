<?php
/**
 * User cron
 * return html
 */

global $post;
//$dir_obj	= new DocDirect_Scripts();
$hoy = date("Y-m-d");
$hora = date("H",strtotime('-6 hour'));
$horamas2 = ((int)$hora)+2;
$meta_query_args[] = array(
						'key'     => 'bk_timestamp',
						'value'   => strtotime($hoy),
						'compare'   => '=',
						'type'	  => 'NUMERIC'
					);
$meta_query_args[] = array(
						'key'     => 'bk_status',
						'value'   => 'approved',
						'compare'   => '=',
						'type'	  => 'STRING'
					);


$args 		= array( 'posts_per_page' => 200,
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
//echo $count_post."<br>";
//echo $horamas2;
			$date_format = get_option('date_format');
			$time_format = get_option('time_format');

			$counter	= 0;
			$enviados = 0;
			$citas = "";
			if( $query->have_posts() ):
				 while($query->have_posts()) : $query->the_post();
					 	global $post;

						$counter++;

						$bk_code  = get_post_meta($post->ID, 'bk_code',true);
						$bk_booking_date  = get_post_meta($post->ID, 'bk_booking_date',true);
						$bk_slottime 	  = get_post_meta($post->ID, 'bk_slottime',true);
						$bk_username      = get_post_meta($post->ID, 'bk_username',true);

						$bk_timestamp     = get_post_meta($post->ID, 'bk_timestamp',true);
						$bk_status        = get_post_meta($post->ID, 'bk_status',true);
						$bk_user_from     = get_post_meta($post->ID, 'bk_user_from',true);
						$bk_user_to     = get_post_meta($post->ID, 'bk_user_to',true);

						$doc_name = docdirect_get_username($bk_user_to);
						$time = explode('-',$bk_slottime);
						$horacita = substr($time[0], 0, 2);

						if ($horamas2 == (int)$horacita){
							//echo esc_attr($bk_user_from );
							//echo esc_attr( $doc_name);
							$horacita =  date_i18n($time_format,strtotime('2016-01-01 '.$time[0]) );
							$enviados ++;							
							$citas .= $bk_code.", ";
							$jsonData = '{
							    "topic": "transactional_notification",
							    "platform": 1,
								"message": {
							        "title":"Recordatorio",
							        "body": "Tienes una cita con '.$doc_name.' a las '.$horacita.'"
							      },
								"recipients": {
							      "aliases": ["'.$bk_user_from.'"]
							    }
							  }';
							$headers = array(
										'x-pushbots-appid: 5e6c80811a115b1d6c2da246',
										'x-pushbots-secret: dd528d673b9da321e2193c5a190830fc',
										'Content-Type: application/json',
										'Content-Length: ' . strlen($jsonData)
									);

							//$payload = json_encode($data);

							$ch = curl_init();
							curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
							curl_setopt($ch, CURLOPT_HEADER, FALSE);
							curl_setopt($ch, CURLOPT_POST, TRUE);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
							curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE );
							curl_setopt($ch, CURLOPT_URL, 'https://api.pushbots.com/3/push/transactional');
							curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

							$result = curl_exec($ch);
							curl_close($ch);
							//echo $jsonData;


						}
				 endwhile; wp_reset_postdata();
			 endif;


$mensaje = "Mensaje enviados: ".$enviados."\nUsuarios: ". $citas;
$mensaje = wordwrap($mensaje, 70, "\r\n");
mail('figarcia@gmail.com', 'Cron push', $mensaje);
?>
