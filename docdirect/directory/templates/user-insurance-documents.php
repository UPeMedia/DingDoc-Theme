<?php
/**
 * User Invoices
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
$dir_obj	= new DocDirect_Scripts();
$user_identity	= $current_user->ID;
$url_identity	= $user_identity;

if( isset( $_GET['identity'] ) && !empty( $_GET['identity'] ) ){
	$url_identity	= $_GET['identity'];
}

$profile_brochure = get_user_meta($user_identity, 'insurance_documents', true);
?>
<div class="tg-dashboardbox tg-brochureupload">
	<div class="tg-dashboardtitle">
		<h2><?php esc_html_e('Upload Documents', 'listingo'); ?></h2>
	</div>
	<div class="tg-brochureuploadbox">
		<div class="tg-upload">
			<div class="tg-uploadhead">
				<span>
					<h3><?php esc_html_e('Upload Documents', 'listingo'); ?></h3>
					<i class="fa fa-exclamation-circle"></i>
				</span>
				<i class="fa fa-cloud-upload"></i>
			</div>
			<div class="tg-box">
				<label class="tg-fileuploadlabel" for="tg-photogallery">
					<div id="plupload-brochure-container">
						<a href="javascript:;" id="upload-brochure" class="tg-fileinput sp-upload-container">
							<i class="fa fa-cloud-upload"></i>
							<span><?php esc_html_e('Or Drag Your Files Here To Upload', 'listingo'); ?></span>
						</a>
					</div>
				</label>
				<div class="tg-brochure">
					<ul class="tg-tagdashboardlist sp-profile-brochure" data-file_type="<?php echo isset($profile_brochure['file_type']) ? esc_attr($profile_brochure['file_type']) : esc_attr('profile_brochure'); ?>">
						<?php
						if (!empty($profile_brochure['file_data'])) {
							foreach ($profile_brochure['file_data'] as $key => $value) {
								?>
								<?php if (!empty($value['file_title'])) { ?>
									<li class="brochure-item brochure-thumb-item item-<?php echo intval($value['file_id']); ?>"  data-type="insurance_documents" data-brochure_id="<?php echo intval($value['file_id']); ?>">
										<span class="tg-tagdashboard">
											<i class="fa fa-close delete_brochure_file"></i>
											<em><?php echo esc_attr($value['file_title']); ?></em>
										</span>
										<a download class="tg-btndownload" href="<?php echo esc_url($value['file_relpath']); ?>"><i class="fa fa-download"></i></a>
										<i class="<?php echo esc_attr($value['file_icon']); ?> file_icon"></i>
									</li>
								<?php } ?>
								<?php
							}
						}
						?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<?php
		$inline_script = 'jQuery(document).on("ready", function() { init_file_uploader("insurance_documents"); });';
		wp_add_inline_script( 'docdirect_user_profile', $inline_script, 'after' );
	?>
	<script type="text/template" id="tmpl-load-profile-brochure">
		<li class="brochure-item brochure-thumb-item item-{{data.attachment_id}}" data-brochure_id="{{data.attachment_id}}">
		<span class="tg-tagdashboard">
		<i class="fa fa-close delete_brochure_file"></i>
		<em>{{data.file_title}}</em>
		</span>
		<a download class="tg-btndownload" href="{{data.file_url}}"><i class="fa fa-download"></i></a>
		<i class="{{data.file_icon}} file_icon"></i>
		</li>
	</script>
</div>