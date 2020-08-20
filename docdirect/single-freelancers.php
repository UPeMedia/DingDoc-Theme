<?php
/**
 *
 * The template used for displaying freelancer post style
 *
 * @package   Workreap
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

get_header();
while ( have_posts() ) {
	the_post();
	global $post;
	$post_id = $post->ID;
	
	$linked_profile = get_post_meta($post_id, '_linked_profile', true);
	?>
	<div class="wt-sectionspacetop wt-haslayout">
		<?php get_template_part('directory/front-end/templates/freelancer/single/banner'); ?>
		<div class="wt-sectionspacetop wt-haslayout">
			<?php get_template_part('directory/front-end/templates/freelancer/single/basic'); ?>
			<div class="container">
				<div class="row">
					<div id="wt-twocolumns" class="wt-twocolumns wt-haslayout">
						<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-8 float-left">
							<div class="wt-usersingle">
								<?php get_template_part('directory/front-end/templates/freelancer/single/projects'); ?>
								<?php get_template_part('directory/front-end/templates/freelancer/single/crafted-projects'); ?>
								<?php get_template_part('directory/front-end/templates/freelancer/single/experience'); ?>
								<?php get_template_part('directory/front-end/templates/freelancer/single/education'); ?>
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-4 float-left">
							<aside id="wt-sidebar" class="wt-sidebar">
								<?php get_template_part('directory/front-end/templates/freelancer/single/sidebar-skills'); ?>
								<?php get_template_part('directory/front-end/templates/freelancer/single/sidebar-awards'); ?>
								<?php  do_action('workreap_get_qr_code','freelancer',intval( $post_id ));?>
								<?php get_template_part('directory/front-end/templates/freelancer/single/similar-freelancer'); ?>
								<?php get_template_part('directory/front-end/templates/freelancer/single/share'); ?>
								<?php do_action('workreap_report_post_type_form',$post_id,'freelancer');?>
							</aside>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php do_action('workreap_chat_modal',$linked_profile);?>
	<?php 
		$script = "
				jQuery(document).ready(function () {
					var read_more      	= scripts_vars.read_more;
					var less      		= scripts_vars.less;
					var _readmore = jQuery('.wt-userdetails .wt-description');
					_readmore.readmore({
						speed: 500,
						collapsedHeight: 247,
						moreLink: '<a class=\"wt-btntext\" href=\"javascript:;\">".esc_html__('Read More','workreap')."</a>',
						lessLink: '<a class=\"wt-btntext\" href=\"javascript:;\">".esc_html__('Less','workreap')."</a>',
					});
				});
			";
			wp_add_inline_script( 'readmore', $script, 'after' );
		} 

		get_footer(); 
