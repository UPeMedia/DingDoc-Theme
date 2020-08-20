<?php
/**
 *
 * Author Gallery Template.
 *
 * @package   Docdirect
 * @author    themographics
 * @link      https://themeforest.net/user/themographics/portfolio
 * @since 1.0
 */
/**
 * Get User Queried Object Data
 */
global $wp_query, $current_user;
$author_profile = $wp_query->get_queried_object();
$user_gallery	  = $author_profile->user_gallery;

?>


  <?php
    if ( (get_user_meta( $author_profile->ID, 'user_current_package', true) == 3245) ||
         (get_user_meta( $author_profile->ID, 'user_current_package', true) == 4026)||
         (get_user_meta( $author_profile->ID, 'user_current_package', true) == 4027) ||
         (get_user_meta( $author_profile->ID, 'user_current_package', true) == 4534) )
    {
   ?>

<?php

if( !empty( $user_gallery ) ){?>
  <div class="tg-userphotogallery">
	<div class="tg-userheading">
	  <h2><?php esc_html_e('Photo Gallery','docdirect');?> <i class="fad fa-plus-circle"></i></h2>
	</div>
	<ul>
	<?php
	  foreach( $user_gallery as $key => $value ){
		  $thumbnail	= docdirect_get_image_source($value['id'],150,150);
		  $orignal	  	= docdirect_get_image_source($value['id'],0,0);
		  if( !empty( $thumbnail ) ){
		?>
		<li>
			<figure>
			   <a href="<?php echo esc_url( $orignal );?>" data-rel="prettyPhoto[iframe]"><img src="<?php echo esc_url( $thumbnail );?>" alt="<?php echo esc_attr( get_the_title( $value['id'] ) );?>">
				<figcaption><span class="icon-add"></span></figcaption>
			   </a>
			</figure>
		</li>
	 <?php }}?>
	</ul>
  </div>
<?php } //  fin si tiene paquete  ?>

<?php }
