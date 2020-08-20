<?php
/**
 *
 * Author Sidebar Template.
 *
 * @package   Docdirect
 * @author    themographics
 * @link      https://themeforest.net/user/themographics/portfolio
 * @since 1.0
 */
global $wp_query, $current_user;
/**
 * Get User Queried Object Data
 */
$author_profile = $wp_query->get_queried_object();
$directory_type	= $author_profile->directory_type;
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
  <h3><?php esc_html_e('Contact Details','docdirect');?></h3>
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
		<li><i class="fa fa-envelope-o"></i><a href="mailto:<?php echo esc_attr( $author_profile->user_email );?>?subject:<?php esc_html_e('Hello','docdirect');?>"><?php echo esc_attr( $author_profile->user_email );?></a></li>
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


	<?php if( !empty( $author_profile->fax ) ) {?>

		<?php
		//dependiendo del paqete
		if ( (get_user_meta( $author_profile->ID, 'user_current_package', true) == 3245) ||
					 (get_user_meta( $author_profile->ID, 'user_current_package', true) == 4026) )
			{
		 ?>
		<li><i class="fa fa-fax"></i> <span><?php echo esc_attr( $author_profile->fax );?></span> </li>
		<?php } ?>

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
	<?php if( !empty( $author_profile->user_address ) ){?>
		<a class="tg-btn tg-btn-lg" href="http://maps.google.com/maps?saddr=&amp;daddr=<?php echo esc_attr( $author_profile->user_address );?>" target="_blank"><?php esc_html_e('get directions','docdirect');?></a>
	<?php }?>

	<?php } //fin si user is loggin ?>

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

	<?php
	if ( is_user_logged_in() ) { ?>
		<?php get_template_part('directory/provider-page/template-author-sidebar', 'business-hours'); ?>
		<?php docdirect_prepare_profile_social_sharing($avatar,$author_profile->ID,$professional_statements);?>
	<?php } else{
		echo "<span class='msjiniciar'>Debes iniciar sesión para ver los detalles del perfil.</span>";
	} ?>
	<?php //get_template_part('directory/provider-page/template-author-sidebar', 'contact-form'); ?>
</div>
