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
if (function_exists('docdirect_init_owl_script')) {

  docdirect_init_owl_script();

}

?>


  <?php
    if ( (get_user_meta( $author_profile->ID, 'user_current_package', true) == 3245) ||
         (get_user_meta( $author_profile->ID, 'user_current_package', true) == 4026) ||
         (get_user_meta( $author_profile->ID, 'user_current_package', true) == 4027) ||
         (get_user_meta( $author_profile->ID, 'user_current_package', true) == 4534) )
    {
   ?>

<?php

if( !empty( $user_gallery ) ){?>
  <div class="tg-userphotogallery">
	<div class="tg-userheading">
	  <h2><?php esc_html_e('Photo Gallery','docdirect');?></h2>
	</div>
	<ul>

    <div id="tg-post-slider-<?php echo esc_attr($uniq_flag); ?>" class="post-slider owl-carousel">
      <?php

      foreach( $user_gallery as $key => $value ){
  		  $thumbnail	= docdirect_get_image_source($value['id'],150,150);
  		  $orignal	  	= docdirect_get_image_source($value['id'],0,0);
      ?>
      <div class="item">
      <img src="<?php echo esc_url( $orignal); ?>" alt="galeria">
      </div>
      <?php
      }
      ?>
      </div>
      <script>
      jQuery(document).ready(function () {
      jQuery("#tg-post-slider-<?php echo esc_js($uniq_flag); ?>").owlCarousel({
      items:1,
      rtl: <?php docdirect_owl_rtl_check();?>,
      nav: false,
      dots: false,
      autoplay: true,
      loop: true,
      navText : ['<i class="doc-btnprev icon-arrows-1"></i>','<i class="doc-btnnext icon-arrows"></i>'],
      });
      });
      </script>



	<?php
  //galeria de antes
	  /*foreach( $user_gallery as $key => $value ){
		  $thumbnail	= docdirect_get_image_source($value['id'],150,150);
		  $orignal	  	= docdirect_get_image_source($value['id'],0,0);
		  if( !empty( $thumbnail ) ){*/
		?>
		<!-- <li>
			<figure>
			   <a href="<?php echo esc_url( $orignal );?>" data-rel="prettyPhoto[iframe]"><img src="<?php echo esc_url( $thumbnail );?>" alt="<?php echo esc_attr( get_the_title( $value['id'] ) );?>">
				<figcaption><span class="icon-add"></span></figcaption>
			   </a>
			</figure>
		</li> -->
	 <?php //}}?>

	</ul>
  </div>
<?php }  ?>

<?php } // FIN SI TIENE PAQUETE
