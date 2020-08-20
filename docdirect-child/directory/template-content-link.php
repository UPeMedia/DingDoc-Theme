<?php
/**
 *  Template Name: LinkContent Page
 *
 */

global $paged,$wp_query,$current_user,$post;

require_once ( get_template_directory() . '-child/inc/headers/class-headers-link.php');
require_once ( get_template_directory() . '-child/inc/footers/class-footers-link.php');
//cambio
require_once ( get_template_directory() . '-child/inc/subheaders/class-subheaders-link.php');
require_once ( get_template_directory() . '-child/directory/class-functions-link.php'); //menu

$section_width	 = 'col-lg-9 col-md-9 col-sm-12 col-xs-12';
get_header('link');
?>

<div class="container">
  <div class="row">
	<div class="tg-inner-content haslayout">
		<div class="<?php echo esc_attr( $section_width );?> page-section">
				<?php
				while ( have_posts() ) : the_post();?>
					<div class="container">
						<div class="row">
							<?php
								do_action('docdirect_prepare_section_wrapper_before');
									the_content();								
								do_action('docdirect_prepare_section_wrapper_after');
							?>
						</div>
					</div>
				<?php
				endwhile;
				?>
			</div>
		</div>
	</div>
</div>

<?php
get_footer('link'); ?>
