<?php
/**
 *
 * Author Header Template.
 *
 * @package   Docdirect
 * @author    themographics
 * @link      https://themeforest.net/user/themographics/portfolio
 * @since 1.0
 */
/**
 * Get User Queried Object Data
 */
global $current_user;
$author_profile = $wp_query->get_queried_object();
$directory_type	= $author_profile->directory_type;
$featured_string   = $author_profile->user_featured;
$username 		= docdirect_get_username($author_profile->ID);
$review_data	= docdirect_get_everage_rating ( $author_profile->ID );
$banner			= docdirect_get_user_banner(array('width'=>1920,'height'=>450), $author_profile->ID);
$privacy		= docdirect_get_privacy_settings($author_profile->ID); //Privacy settings
$subcategory = get_user_meta( $author_profile->ID, 'doc_sub_categories', true);

$avatar = apply_filters(
				'docdirect_get_user_avatar_filter',
				 docdirect_get_user_avatar(array('width'=>365,'height'=>365), $author_profile->ID),
				 array('width'=>365,'height'=>365) //size width,height
			);

if(function_exists('fw_get_db_settings_option')) {
	$reviews_switch    = fw_get_db_post_option($directory_type, 'reviews', true);
}

$banner_parallax	= '';
if( !empty( $banner ) ){
	$banner_parallax	= 'data-appear-top-offset="600" data-parallax="scroll" data-image-src="'.$banner.'"';
}

?>
<div id="tg-userbanner" class="tg-userbanner tg-haslayout" <?php //echo ($banner_parallax);?>>
	<div class="container">
		<div class="row">


		 <div class="col-sm-12 col-xs-12">
			<figure class="tg-userimg detail-avatar">
				<a href="<?php echo esc_url(get_author_posts_url($author_profile->ID));?>"><img src="<?php echo esc_attr( $avatar );?>" alt="<?php echo esc_attr( $author_profile->first_name.' '.$author_profile->last_name );?>"></a>
				<?php //do_action('docdirect_display_provider_category',$author_profile->ID);?>
			</figure>
		</div>


		<div class="col-sm-12 col-xs-12">
			<div class="tg-userbanner-content">
				<h1><?php echo esc_attr( $username );?></h1>
				<?php
				$db_subcategory		    = get_user_meta( $author_profile->ID, 'doc_sub_categories', true);
				$sub_category_terms = wp_get_post_terms($directory_type, 'sub_category', array("fields" => "all"));
				foreach ( $sub_category_terms as $pterm ) {
					if ($pterm->slug == array_values($subcategory)[0]){ $categoria = $pterm->name; }
				}
				?>
				<?php
				if ($subcategory){ ?>
					<span class="subcategoriaperfil">
						<?php echo $categoria;   ?>
					</span>
				<?php
				}
				?>
				<?php if( !empty( $author_profile->tagline ) ) {?>
					<span>No. colegiado: <?php echo esc_attr( $author_profile->tagline );?></span>
				<?php }?>
				<ul class="tg-likestars">
					<?php if( isset( $reviews_switch ) && $reviews_switch === 'enable' ){?>
						<li><?php docdirect_get_rating_stars($review_data,'echo');?></li>
					<?php }?>
					<li><?php docdirect_get_wishlist_button($author_profile->ID,true);?></li>
					<!-- <li><span><?php //echo intval( docdirect_get_user_views($author_profile->ID) );?>&nbsp;<?php esc_html_e('view(s)','docdirect');?></span></li> -->
				</ul>




					<?php
						/*echo "CURRENT PACKAGE tipo de paquete:";
						echo get_user_meta( $author_profile->ID, 'user_current_package', true); */
					?>
			</div>
		  </div>



		</div>
	</div>
</div>

<?php
$privacy		= docdirect_get_privacy_settings($author_profile->ID); //Privacy settings

$social_links = apply_filters('docdirect_get_social_media_icons_list',array());
$professional_statements	  	 = isset( $author_profile->professional_statements ) ? $author_profile->professional_statements : '';
$professional_statements	= !empty( $author_profile->description ) ? $author_profile->description : $professional_statements;
$is_chat = docdirect_is_chat_enabled();
$avatar = apply_filters(
				'docdirect_get_user_avatar_filter',
				 docdirect_get_user_avatar(array('width'=>365,'height'=>365), $author_profile->ID),
				 array('width'=>365,'height'=>365) //size width,height
			);
?>

<div class="tg-usercontactinfo">
	<?php
	if ( is_user_logged_in() ) {
	 ?>
  <ul class="tg-doccontactinfo">
	<?php if( !empty( $author_profile->user_address ) ) {?>
		<li> <i class="fa fa-map-marker"></i> <address><?php echo esc_attr( $author_profile->user_address );?></address> </li>
	<?php }?>
	<?php if( !empty( $author_profile->user_email )
			  &&
			  !empty( $privacy['email'] )
			  &&
			  $privacy['email'] == 'on'
	) {?>
		<!-- <li><i class="fa fa-envelope-o"></i><a href="mailto:<?php echo esc_attr( $author_profile->user_email );?>?subject:<?php esc_html_e('Hello','docdirect');?>"><?php echo esc_attr( $author_profile->user_email );?></a></li> -->
		<li><i class="fa fa-envelope-o"></i><?php echo esc_attr( $author_profile->user_email );?></li>
	<?php }?>
	<?php if( !empty( $author_profile->phone_number )
			  &&
			  !empty( $privacy['phone'] )
			  &&
			  $privacy['phone'] == 'on'
	) {?>
		<?php
		//dependiendo del paqete
		/*Premium  3245
			Avanzado 4026
			Basico 3247
			Institucional 4027*/
		if ( (get_user_meta( $author_profile->ID, 'user_current_package', true) == 3245) ||
					 (get_user_meta( $author_profile->ID, 'user_current_package', true) == 4026) )
			{
		 ?>
		<li> <i class="fa fa-phone"></i> <span><a href="tel:<?php echo esc_attr( $author_profile->phone_number );?>"><?php echo esc_attr( $author_profile->phone_number );?></a></span> </li>
		<?php
			}
		 ?>

	<?php }?>

	<?php if( !empty( $author_profile->skype ) ) {?>
		<?php
			if ( (get_user_meta( $author_profile->ID, 'user_current_package', true) == 3245) ||
					 (get_user_meta( $author_profile->ID, 'user_current_package', true) == 4026) )
			{
		 ?>
		<li><i class="fa fa-phone"></i><span><a href="tel:<?php echo esc_attr( $author_profile->skype);?>"><?php echo esc_attr( $author_profile->skype );?></a></span></li>
		<?php }?>
	<?php }?>




	<?php if( !empty( $author_profile->user_url ) ) {?>
		<li><i class="fa fa-link"></i><a href="<?php echo esc_url( $author_profile->user_url );?>" target="_blank"><?php echo docdirect_parse_url( $author_profile->user_url);?></a></li>
	<?php }?>
  </ul>




  <?php if( !empty( $social_links ) ){?>
		<?php
		//dependiendo del paqete
		if ( (get_user_meta( $author_profile->ID, 'user_current_package', true) == 3245) ||
					 (get_user_meta( $author_profile->ID, 'user_current_package', true) == 4026) )
			{
		 ?>
  <ul class="tg-socialicon-v2">
	  <?php
			foreach( $social_links as $key => $social ){
				$item 		= get_user_meta($author_profile->ID,$key,true);
				$icon		= !empty( $social['icon'] ) ? $social['icon'] : '';
				$classes	= !empty( $social['classses'] ) ? $social['classses'] : '';
				$title		= !empty( $social['title'] ) ? $social['title'] : '';
				$color		= !empty( $social['color'] ) ? $social['color'] : '#484848';
				if( $key === 'whatsapp' ){
					if ( !empty( $item ) ){
						$item	= 'https://api.whatsapp.com/send?phone='.$item;
					}
				} else if( $key === 'skype' ){
					if ( !empty( $item ) ){
						$item	= 'skype:'.$item.'?call';
					}
				}

				if(!empty($item)) {?>
					<li class="<?php echo esc_attr($classes); ?>"><a href="<?php echo esc_attr($item); ?>" style="background:<?php echo esc_attr( $color );?>"><i class="<?php echo esc_attr($icon); ?>"></i></a></li>
				<?php } ?>
		<?php } ?>
	</ul>
	<?php } //dependiendo del paquete ?>

	<?php } ?>


	<?php
	//dependiendo del paqete
	if ( (get_user_meta( $author_profile->ID, 'user_current_package', true) == 3245) ||
				 (get_user_meta( $author_profile->ID, 'user_current_package', true) == 4026) )
		{
	 ?>

		<?php
		 if( apply_filters('docdirect_is_setting_enabled',$author_profile->ID,'appointments' ) === true ){
			 if( !empty( $privacy['appointments'] )  &&  $privacy['appointments'] == 'on' ) {
				 //if( isset( $current_user->ID ) && $current_user->ID != $author_profile->ID	 &&	is_user_logged_in() )
				 if( isset( $current_user->ID ) &&	is_user_logged_in() )
				 { ?>
					<button class="tg-btn tg-btn-lg make-appointment-btn" type="button" data-toggle="modal" data-target=".tg-appointmentpopup"><?php esc_html_e('MAKE AN APPOINTMENT!','docdirect');?></button>
				<?php
				}  else if( $current_user->ID != $author_profile->ID ){?>
							<button class="tg-btn tg-btn-lg make-appointment-btn" data-redirect="<?php echo esc_url(get_author_posts_url($author_profile->ID)); ?>" type="button" data-toggle="modal" data-target=".tg-user-modal"><?php esc_html_e('MAKE AN APPOINTMENT!','docdirect');?></button>
						<?php
						}
				}
			}?>

			<?php
			if ( is_user_logged_in() ) {
		        if ($current_user->ID != $author_profile->ID) {
		            if (!empty($is_chat) && $is_chat === 'yes') {
		                ?>
											<a class="tg-btn tg-btn-lg send-message" data-toggle="modal" data-target="#chatmodal" href="javascript:;"><i class="lnr lnr-bubble"></i><?php esc_html_e('&nbsp;Send message', 'docdirect'); ?></a>
											<?php
		        	}
				}
			}
			?>

	<?php } //dependiendo del paquete?>



	<?php if( !empty( $author_profile->user_address ) ){?>
		<!-- <a class="tg-btn tg-btn-lg" href="http://maps.google.com/maps?saddr=&amp;daddr=<?php //echo esc_attr( $author_profile->user_address );?>" target="_blank"><?php //esc_html_e('get directions','docdirect');?></a> -->
	<?php }?>

	<?php if( !empty( $author_profile->user_address ) ){?>
		<?php $lat = get_user_meta( $author_profile->ID, 'latitude', true); ?>
		<?php $long = get_user_meta( $author_profile->ID, 'longitude', true); ?>
		<div class="comollegar">
		<a class="tg-btn-lg gmaps" href="https://www.google.com/maps?saddr=My+Location&daddr=<?php echo $lat; ?>,<?php echo $long; ?>" target="_blank">&nbsp;</a>
		<a class="tg-btn-lg waze" href="https://waze.com/ul?ll=<?php echo $lat; ?>,<?php echo $long; ?>&navigate=yes" target="_blank">&nbsp;<?php //esc_html_e('get directions','docdirect');?></a>

		</div>
	<?php }?>

	<?php } //fin si user is loggin ?>



	<?php
	if ( is_user_logged_in() ) { ?>
		<?php docdirect_prepare_profile_social_sharing($avatar,$author_profile->ID,$professional_statements);?>
		<?php get_template_part('directory/provider-page-link/template-author-sidebar', 'business-hours'); ?>
	<?php } else{
		echo "<span class='msjiniciar'>Debes iniciar sesión para ver los detalles del perfil.</span>";
	} ?>
	<?php //get_template_part('directory/provider-page/template-author-sidebar', 'contact-form'); ?>




</div>
