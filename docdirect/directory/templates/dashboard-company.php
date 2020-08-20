<?php
/**
 * Copany Profile
 * return html
 */
global $current_user, $wp_roles,$userdata,$post;
//cambio para unir favoritos
global $paged;

$reference = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : $reference	= '';
$user_identity	= $current_user->ID;



 if (function_exists('fw_get_db_settings_option')) {
	$company_profile = fw_get_db_settings_option('company_profile');
	$support_box = fw_get_db_settings_option('support_box');
	$com_desscription = fw_get_db_settings_option('com_description');
	$com_logo = fw_get_db_settings_option('com_logo');
	$support_heading = fw_get_db_settings_option('support_heading');
	$support_address = fw_get_db_settings_option('support_address');
	$support_phone  = fw_get_db_settings_option('support_phone');
	$support_email  = fw_get_db_settings_option('support_email');
	$support_fax 	= fw_get_db_settings_option('support_fax');
}

$mainWrap	= 'col-xs-12 tg-findheatlhwidth';
$supportWrap	= 'col-xs-12 tg-expectwidth';

if( isset( $support_box ) && $support_box === 'enable' ){
	$mainWrap	= 'col-lg-8 col-md-7 col-sm-12 col-xs-12 tg-findheatlhwidth';
	$supportWrap	= 'col-lg-4 col-md-5 col-sm-12 col-xs-12 tg-expectwidth';
}


if( isset( $company_profile ) && $company_profile === 'enable' ){
?>
<div class="tg-dashboard tg-haslayout">


	<div class="tg-docprofilechart tg-haslayout">
		<div class="homecol1 col-md-12 <?php //echo esc_attr( $mainWrap );?>">
			<div class="row">
				<div class="tg-docinfo tg-haslayout">
					<div class="tg-box">
						<div class="tg-heading-border tg-small">
							<h1><?php esc_html_e('welcome, ','docdirect');?><?php echo get_the_author_meta('display_name',$user_identity );?></h1>
						</div>
            <div class="tg-description" style="display:block !important;">

							<p><?php //echo esc_attr( $com_desscription );?></p>

              <p style="margin-top:20px;">Encuentra la solución médica que buscas en un solo lugar:</p>
              <p style="text-align: center; margin-top:30px; ">
                <?php
                if ( $_GET['page_id'] == 4423){
                  $npage_id = 4398;
                }else{
                  $npage_id = 262;
                }
                ?>
                <a class="tg-btn tg-btn-lg send-message btnround" style="margin-left:0;" href="<?php echo get_home_url(); ?>/?page_id=<?php echo $npage_id; ?>" ><i class="fa fa-search"></i>&nbsp;Empezar</a>
              </div>



					</div>
				</div>
			</div>
		</div>

	</div>
</div>
<?php
}

?>



<?php
  //cambio se agrego favoritos

  $user_identity	= $current_user->ID;
  $directory_type	   = get_user_meta( $user_identity, 'directory_type', true);
  if ($directory_type > 0){
    echo "";
  }else{

?>
<div class="tg-listingarea doc-favourites-wraper">
  <div class="tg-listing">
    <div class="tg-listing-head">
      <div class="tg-titlebox">
        <h3><?php esc_html_e('My Favorites','docdirect'); ?></h3>
      </div>
      <div class="tg-titlebox">
        <h3><?php esc_html_e('Category','docdirect'); ?></h3>
      </div>
      <div class="tg-titlebox">
        <h3><?php esc_html_e('Action','docdirect'); ?></h3>
      </div>
    </div>
    <div class="tg-lists tg-favorites">
     <?php

		$limit = get_option('posts_per_page');
		if (empty($paged)) $paged = 1;
		$offset = ($paged - 1) * $limit;
		$wishlist    = get_user_meta($current_user->ID,'wishlist', true);
		$wishlist    = !empty($wishlist) && is_array( $wishlist ) ? $wishlist : array();

		$total_users = (int)count($wishlist); //Total Users


		$query_args	= array(
								'role'  => 'professional',
								'order' => 'DESC',
								'orderby' => 'ID',
								'include' => $wishlist
							 );

		$query_args['number']	= $limit;
		$query_args['offset']	= $offset;

		$user_query  = new WP_User_Query($query_args);
		if ( ! empty( $wishlist ) ) {
			if ( ! empty( $user_query->results ) ) {
			  foreach ( $user_query->results as $user ) {

			  $directories_array['name']	 	 = $user->first_name.' '.$user->last_name;
			  $directory_type	= $user->directory_type;
			  $avatar = apply_filters(
									'docdirect_get_user_avatar_filter',
									 docdirect_get_user_avatar(array('width'=>150,'height'=>150), $user->ID),
									 array('width'=>150,'height'=>150) //size width,height
								);
			  ?>
			  <div class="tg-list" id="wishlist-<?php echo intval($user->ID); ?>">
				<div class="tg-listbox" data-title="my favorites">
				  <figure><a href="<?php echo get_author_posts_url($user->ID); ?>" class="list-avatar"><img src="<?php echo esc_attr( $avatar );?>" alt="<?php echo esc_attr( $directories_array['name'] );?>"></a></figure>
				  <div class="tg-listdata">
					<h4><a href="<?php echo get_author_posts_url($user->ID); ?>"><?php echo esc_attr( $directories_array['name'] );?></a></h4>
				  </div>
				</div>
				<div class="tg-listbox" data-insights="catagory"><span><?php echo esc_attr( ucwords( get_the_title( $directory_type ) ) );?></span></div>
				<div class="tg-listbox" data-action="action">
					<a class="tg-btn-list" href="<?php echo get_author_posts_url($user->ID); ?>"><i class="fa fa-eye"></i></a>
					<a class="tg-btn-list remove-wishlist" href="javascript:;" data-wl_id="<?php echo intval($user->ID); ?>"><i class="fa fa-trash-o"></i></a>
				</div>
			  </div>
		   <?php }} else{?>
				<div class="tg-list"><p><?php esc_html_e('Nothing found.','docdirect'); ?></p></div>
		  <?php }?>
         <?php } else{?>
            <div class="tg-list"><p><?php esc_html_e('Nothing found.','docdirect'); ?></p></div>
      <?php }?>
    </div>
    <?php
	//Pagination
	if( $total_users > $limit ) {?>
	  <div class="tg-btnarea">
			<?php docdirect_prepare_pagination($total_users,$limit);?>
	  </div>
	<?php }?>
  </div>
</div>
<?php } // fin if no tiene paquete?>
