<?php
/**
 *
 * Author Video Template.
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
if(function_exists('fw_get_db_settings_option')) {
	$reviews_switch    = fw_get_db_post_option($directory_type, 'reviews', true);
	$theme_type = fw_get_db_settings_option('theme_type');
	$theme_color = fw_get_db_settings_option('theme_color');
}

$review_data	= docdirect_get_everage_rating ( $author_profile->ID );

//rating star color
if ( isset( $theme_type) && $theme_type === 'custom') {
	if ( !empty( $theme_color ) ) {
		$rating_color	= $theme_color;
	} else{
		$rating_color	= '#7dbb00';
	}
} else {
	$rating_color	= '#7dbb00';
}

?>
<div role="tabpanel" class="tg-tabpane tab-pane active" id="reviews">
<?php //if( isset( $reviews_switch ) && $reviews_switch === 'enable' ){?>
  <div class="tg-userreviews">
	<?php
	if( isset( $current_user->ID )
		&&
		$current_user->ID != $author_profile->ID
	){?>
	<div class="tg-leaveyourreview">
	  <div class="tg-userheading">
		<h2>Califica nuestro servicio</h2>
		<div id="encuesta_doc"></div>
	  </div>
	  <?php if( apply_filters('docdirect_is_user_logged_in','check_user') === true ){?>
	  <div class="message_contact  theme-notification"></div>
	  <form class="tg-formleavereview form-review">
		<fieldset>
		  <div class="row">
			<div class="col-sm-12">
			  <div class="tg-stars">Servicio del médico: <div id="jRate"></div></div>
			  <script type="text/javascript">
			jQuery(function () {
				var that = this;
				jQuery('.user_rating').val(3);
				var toolitup = jQuery("#jRate").jRate({
					rating: 3,
					min: 0,
					max: 5,
					precision: 1,
					startColor: "<?php echo esc_js( $rating_color );?>",
					endColor: "<?php echo esc_js( $rating_color );?>",
					backgroundColor: "#DFDFE0",
					onChange: function(rating) {
						jQuery('.user_rating').val(rating);
					},
					onSet: function(rating) {
						jQuery('.user_rating').val(rating);
					}
				});
			});
		</script>
			</div>

			<div class="col-sm-12">
			  <div class="tg-stars">Servicio del personal de clínica: <div id="jRate2"></div></div>
			  <script type="text/javascript">
			jQuery(function () {
				var that = this;
				jQuery('.user_rating2').val(3);
				var toolitup = jQuery("#jRate2").jRate({
					rating: 3,
					min: 0,
					max: 5,
					precision: 1,
					startColor: "<?php echo esc_js( $rating_color );?>",
					endColor: "<?php echo esc_js( $rating_color );?>",
					backgroundColor: "#DFDFE0",
					onChange: function(rating) {
						jQuery('.user_rating2').val(rating);
					},
					onSet: function(rating) {
						jQuery('.user_rating2').val(rating);
					}
				});
			});
		</script>
			</div>


			<div class="col-sm-12">
			  <div class="tg-stars">Servicio de Dingdoc:<div id="jRate3"></div></div>
			  <script type="text/javascript">
			jQuery(function () {
				var that = this;
				jQuery('.user_rating3').val(3);
				var toolitup = jQuery("#jRate3").jRate({
					rating: 3,
					min: 0,
					max: 5,
					precision: 1,
					startColor: "<?php echo esc_js( $rating_color );?>",
					endColor: "<?php echo esc_js( $rating_color );?>",
					backgroundColor: "#DFDFE0",
					onChange: function(rating) {
						jQuery('.user_rating3').val(rating);

					},
					onSet: function(rating) {
						jQuery('.user_rating3').val(rating);
					}
				});
			});
		</script>
			</div>

			<div class="col-sm-12" style="text-align:center">
			  <button class="tg-btn make-review" type="submit">Calificar</button>
			  <input type="hidden" name="user_rating" class="user_rating" value="" />
				<input type="hidden" name="user_rating2" class="user_rating2" value="" />
				<input type="hidden" name="user_rating3" class="user_rating3" value="" />
			  <input type="hidden" name="user_to" class="user_to" id="encuesta_user_to" value="<?php //echo esc_attr( $author_profile->ID );?>" />
				<input type="hidden" name="user_subject" class="form-control" id="encuesta_id" >
			</div>
		  </div>
		</fieldset>
	  </form>
	  <?php } else{?>
		<span><a href="javascript:;" class="tg-btn" data-toggle="modal" data-target=".tg-user-modal"><?php esc_html_e('Please Login To add Review','docdirect');?></a></span>
  <?php }?>
	</div>
	<?php }?>
  </div>
<?php //} if enable?>
</div>
