<?php
/**
 * Map Search Postion Top
 * return html
 */

global $current_user, $wp_roles,$userdata,$post;
get_header('mobile');

/*global $wpdb;
$wpdb_backup = $wpdb;*/
//www
//$wpdb = new wpdb( 'dingdocc_20wpusr', 'cU=9!7GaBk(5XfW', 'dingdocc_20wp', 'localhost' );
//www3
//$wpdb = new wpdb( 'dingdocc_20wpusr', 'cU=9!7GaBk(5XfW', 'dingdocc_20wp3', 'localhost' );


?>


<?php

  if (function_exists('fw_get_db_settings_option')) {
    $search_page_map 		= fw_get_db_settings_option('search_page_map');
    $dir_map_marker_default = fw_get_db_settings_option('dir_map_marker');
    $google_key = fw_get_db_settings_option('google_key');
  } else{
    $google_key = '';
    $dir_map_marker_default = '';
    $google_key = '';
  }


  docdirect_init_dir_map();//init Map
  docdirect_enque_map_library();//init Map

  //Search center point
  $direction	= docdirect_get_location_lat_long();


  //query
  $user_query  = new WP_User_Query($query_args);
  /*echo "<pre>";
  print_r($user_query);
  echo "</pre>";*/

  $total_users = !empty( $user_query->total_users ) ? $user_query->total_users : 0;
  $found_title = docdirect_get_found_title($total_users,$directory_type);

  if( isset( $search_page_map ) && $search_page_map === 'enable' ){?>
    <!-- <div class="map-top">
      <div class="row tg-divheight">
        <div class="tg-mapbox">
          <div id="map_canvas" class="tg-location-map tg-haslayout"></div>
          <?php do_action('docdirect_map_controls');?>
          <div id="gmap-noresult"></div>
        </div>
      </div>
    </div> -->
  <?php }?>

  <div class="container">
      <div id="doc-twocolumns" class="doc-twocolumns doc-listview">
        <?php if( !empty( $found_title ) ) {?>
          <!-- <span class="doc-searchresult"><?php echo force_balance_tags( $found_title );?></span> -->
        <?php }?>
        <form class="doc-formtheme1 doc-formsearchwidget1 search-result-form1">
            <!-- <div class="row"> -->


              <!-- <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 pull-left" style="z-index: 999;">
                <aside id="doc-sidebar" class="doc-sidebar">
                  <?php //do_action( 'docdirect_search_filters' );?>
                  <?php //if (is_active_sidebar('search-page-sidebar')) {?>
                    <div class="tg-doctors-list tg-haslayout">
                      <?php //dynamic_sidebar('search-page-sidebar'); ?>
                    </div>
                  <?php //}?>
                </aside> -->


                <script>
                  function openModal(tipo){
                      $('#filterModal').modal('show');
                      $('#filterModal .directory_type option[value="'+tipo+'"]').attr('selected', 'selected');
                      $('#filterModal .titulomodal').text(tipo);

                  }
                </script>
              <!-- </div> -->

                <?php
                $home = 1;
                if (!isset($_GET['directory_type'])) {   $home= 1; ?>

                    <style>
                    .tg-pagebar{display: none!important}
                    </style>
                    <!-- Actual search box -->
                    <!-- <div class="form-group has-feedback has-search"> -->
                      <!-- <span class="glyphicon glyphicon-search form-control-feedback"></span> -->
                      <!-- <i class="fa fa-search form-control-feedback"></i> -->
                      <!-- <input type="text" class="form-control" placeholder="Busca por nombre, apellido o especialidad." onclick="openModal()"> -->
                      <!-- <h1 class="titulopage h1naranja">¿Qué estás buscando?</h1>
                    </div> -->

                    <div class="col-sm-12 " >
                        <h1 class="titulopage h1naranja">¿Qué estás buscando?</h1>
                    </div>
                      <div class="col-sm-12">
                          <div style="text-align:center;" class="ajustehome"> <?php echo $_GET['directory_type'];    ?>
                              <!-- <a href="https://dingdoc.com.gt/wp1/wp3/dir-search/?directory_type=especialistas"> -->
                              <a href="#" onclick="openModal('especialistas')">
                                  <img src="https://dingdoc.com.gt/wp-content/uploads/2020/02/1-icono1a.png" /></a>
                          </div>
                          <div style="text-align:center;" class="ajustehome filacentro">
                              <a href="#" onclick="openModal('hospitales')">
                                  <img src="https://dingdoc.com.gt/wp-content/uploads/2020/02/1-icono2a.png" /></a>
                          </div>
                          <div style="text-align:center;" class="ajustehome">
                              <a href="#" onclick="openModal('farmacias')">
                                  <img src="https://dingdoc.com.gt/wp-content/uploads/2020/02/1-icono3a.png" /></a>
                          </div>
                      </div>

                      <script>
                        console.log(window.innerHeight);
                        var winH = window.innerHeight;
                        if (winH < 500){
                          var ajustehome = Math.ceil(winH/5.7);
                        }else if (winH < 600){
                          var ajustehome = Math.ceil(winH/5.2);
                        }else {
                          var ajustehome = Math.ceil(winH/5);
                        }
                        console.log(ajustehome);
                        var x = document.getElementsByClassName("ajustehome");
                        var i;
                        for (i = 0; i < x.length; i++) {
                          x[i].style.height = ajustehome+"px";
                          console.log('div');
                        }
                      </script>


              <?php }else {$home = 0; ?>
                <!-- Actual search box -->
                <div class="form-group has-feedback has-search">
                  <!-- <span class="glyphicon glyphicon-search form-control-feedback"></span> -->
                  <i class="fa fa-search form-control-feedback"></i>
                  <input type="text" class="form-control" placeholder="Buscar" onclick="openModal()">
                </div>

              <?php  } ?>



              <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12 pull-right">
                <div id="doc-content" class="doc-content">
                  <div class="doc-doctorlisting">
                    <div class="clearfix"></div>
                    <div class="row">
                      <div class="col-sm-12">
                      <?php


                      $directories	=  array();
            $directories['status']	= 'none';
            $directories['lat']  = floatval ( $direction['lat'] );
            $directories['long'] = floatval ( $direction['long'] );

                      if ( ! empty( $user_query->results ) ) {
                          $directories['status']	= 'found';

                          if( isset( $directory_type ) && !empty( $directory_type ) ) {
                              $title = get_the_title($directory_type);
                              $postdata = get_post($directory_type);
                              $slug 	 = $postdata->post_name;
                          } else{
                              $title = '';
                              $slug = '';
                          }

                          foreach ( $user_query->results as $user ) {

                              if ($home == 1){
                                  break;
                              }

                              $subcategory = get_user_meta( $user->ID, 'doc_sub_categories', true);
                              $latitude	   = get_user_meta( $user->ID, 'latitude', true);
                              $longitude	   = get_user_meta( $user->ID, 'longitude', true);
                              $directory_type = get_user_meta( $user->ID, 'directory_type', true);
                              $dir_map_marker = fw_get_db_post_option($directory_type, 'dir_map_marker', true);
                              $reviews_switch    = fw_get_db_post_option($directory_type, 'reviews', true);
                              $featured_date  = get_user_meta($user->ID, 'user_featured', true);
                              $current_date   = date('Y-m-d H:i:s');
                              $avatar = apply_filters(
                                      'docdirect_get_user_avatar_filter',
                                       docdirect_get_user_avatar(array('width'=>270,'height'=>270), $user->ID),
                                       array('width'=>270,'height'=>270) //size width,height
                                  );

                              $provider_category = docdirect_get_provider_category($user->ID);
                              if (function_exists('fw_get_db_settings_option')) {
                          			$bg_color = fw_get_db_post_option($provider_category, 'category_color', true);
                          			if (!empty($bg_color)) {
                          				$bg_color = 'style=background:' . $bg_color;
                          			}
                          		}

                              $privacy		= docdirect_get_privacy_settings($user->ID); //Privacy settin

                $directories_array['latitude']	 = $latitude;
                $directories_array['longitude']	 = $longitude;
                $directories_array['fax']		 = $user->fax;
                $directories_array['description']  = $user->description;
                $directories_array['title']		= $user->display_name;
                $directories_array['name']	 	 = $user->first_name.' '.$user->last_name;
                $directories_array['email']	 	= $user->user_email;
                $directories_array['phone_number'] = $user->phone_number;
                $directories_array['address']	  = $user->user_address;
                $directories_array['group']		= $slug;
                $featured_string   = $featured_date;
                $current_string	= strtotime( $current_date );
                $review_data	= docdirect_get_everage_rating ( $user->ID );
                $get_username	= docdirect_get_username( $user->ID );
                $get_username	= docdirect_get_username( $user->ID );

                if( isset( $dir_map_marker['url'] ) && !empty( $dir_map_marker['url'] ) ){
                  $directories_array['icon']	 = $dir_map_marker['url'];
                } else{
                  if( !empty( $dir_map_marker_default['url'] ) ){
                    $directories_array['icon']	 = $dir_map_marker_default['url'];
                  } else{
                    $directories_array['icon']	 	   = get_template_directory_uri().'/images/map-marker.png';
                  }
                }

                $infoBox	= '<div class="tg-map-marker">';
                $infoBox	.= '<figure class="tg-docimg"><a class="userlink" target="_blank" href="'.get_author_posts_url($user->ID).'"><img src="'.esc_url( $avatar ).'" alt="'.esc_attr( $directories_array['name'] ).'"></a>';
                $infoBox	.= docdirect_get_wishlist_button($user->ID,false);

                if( isset( $featured_string ) && $featured_string > $current_string ){
                  $infoBox	.= docdirect_get_featured_tag(false);
                }

                $infoBox	.= docdirect_get_verified_tag(false,$user->ID);

                if( isset( $reviews_switch ) && $reviews_switch === 'enable' ){
                  $infoBox	.= docdirect_get_rating_stars($review_data,'return');
                }

                $infoBox	.= '</figure>';

                $infoBox	.= '<div class="tg-mapmarker-content">';
                $infoBox	.= '<div class="tg-heading-border tg-small">';
                $infoBox	.= '<h3><a class="userlink" target="_blank" href="'.get_author_posts_url($user->ID).'">'.$directories_array['name'].'</a></h3>';
                $infoBox	.= '</div>';
                $infoBox	.= '<ul class="tg-info">';



                if( !empty( $directories_array['email'] )
                  &&
                    !empty( $privacy['email'] )
                  &&
                    $privacy['email'] == 'on'
                ) {
                  $infoBox	.= '<li> <i class="fa fa-envelope"></i> <em><a href="mailto:'.$directories_array['email'].'?Subject='.esc_html__('hello','docdirect').'"  target="_top">'.$directories_array['email'].'</a></em> </li>';
                }

                if( !empty( $directories_array['phone_number'] )
                  &&
                    !empty( $privacy['phone'] )
                  &&
                    $privacy['phone'] == 'on'
                ) {
                  $infoBox	.= '<li> <i class="fa fa-phone"></i> <em><a href="javascript:;">'.$directories_array['phone_number'].'</a></em> </li>';
                }

                if( !empty( $directories_array['address'] ) ) {
                  $infoBox	.= '<li> <i class="fa fa-home"></i> <address>'.$directories_array['address'].'</address> </li>';
                }

                $infoBox	.= '</ul>';
                $infoBox	.= '</div>';
                $infoBox	.= '</div>';

                $directories_array['html']['content']	= $infoBox;
                $directories['users_list'][]	= $directories_array;

                              ?>
                              <div class="doc-featurelist mobile" class="user-<?php echo intval( $user->ID );?>">
                                  <figure class="doc-featureimg">
                                      <?php if( isset( $featured_string ) && $featured_string > $current_string ){?>
                                          <?php //docdirect_get_featured_tag(true,'v2');?>
                                      <?php }?>
                                      <?php //docdirect_get_verified_tag(true,$user->ID,'','v2');?>
                                      <a href="<?php echo get_author_posts_url($user->ID); ?>" class="list-avatar">
                                        <img src="<?php echo esc_attr( $avatar );?>" alt="<?php echo esc_attr( $directories_array['name'] );?>">
                                      </a>
                                      <?php //do_action('docdirect_display_provider_category',$user->ID);?>
                                      <?php
                                      if ($subcategory){ ?>
                                        <!-- <a class="doc-themetag tg-categorytag sp-av-link" <?php echo esc_attr($bg_color); ?>   href="#">
                                  				<?php //echo array_values($subcategory)[0]; ?>
                                  			</a> -->
                                      <?php
                                      }
                                      ?>

                                      <?php docdirect_get_wishlist_button($user->ID,true,'v2');?>
                                  </figure>
                                  <div class="doc-featurecontent">
                                    <div class="doc-featurehead">
                                      <?php //docdirect_get_wishlist_button($user->ID,true,'v2');?>
                                      <h2><a href="<?php echo get_author_posts_url($user->ID); ?>" class="list-avatar"><?php echo ( $get_username );?></a></h2>
                                      <?php if( !empty( $user->tagline ) ) {?>
                                          <span>No. colegiado: <?php echo esc_attr( $user->tagline );?></span>
                                      <?php }?>
                                      <ul class="doc-matadata">

                                        <li><?php //docdirect_get_likes_button($user->ID);?></li>
                                        <?php
                                           if( isset( $reviews_switch ) && $reviews_switch === 'enable' ){
                                              //docdirect_get_rating_stars_v2($review_data,'echo');
                                           }
                                          ?>
                                      </ul>
                                    </div>


                                    <?php if( !empty( $directories_array['description'] ) ){?>
                                        <!-- <div class="doc-description">
                                          <p><?php echo substr($directories_array['description'], 0, 147);?></p>
                                        </div> -->
                                    <?php }?>
                                    <ul class="doc-addressinfo" style="display:none">
                                      <?php if( !empty( $directories_array['address'] ) ) {?>
                                      <!-- <li> <i class="fa fa-map-marker"></i>
                                        <address><?php echo esc_attr( $directories_array['address'] );?></address>
                                      </li> -->
                                      <?php }?>
                                      <?php if( !empty( $directories_array['phone_number'] )
                                                &&
                                                  !empty( $privacy['phone'] )
                                                &&
                                                  $privacy['phone'] == 'on'
                                          ) {?>
                                          <!-- <li><i class="fa fa-phone"></i><span><?php echo esc_attr( $directories_array['phone_number'] );?></span></li> -->
                                      <?php }?>
                                      <?php if( !empty( $directories_array['email'] )
                                                &&
                                                  !empty( $privacy['email'] )
                                                &&
                                                  $privacy['email'] == 'on'
                                          ) {?>
                                          <!-- <li><i class="fa fa-envelope-o"></i><a href="mailto:<?php echo esc_attr( $directories_array['email']);?>?subject:<?php esc_html_e('Hello','docdirect');?>"><?php echo esc_attr( $directories_array['email']);?></a></li> -->
                                      <?php }?>
                                      <?php if( !empty( $directories_array['fax'] ) ) {?>
                                          <!-- <li><i class="fa fa-fax"></i><span><?php echo esc_attr( $directories_array['fax']);?></span></li> -->
                                      <?php }?>

                                      <?php
                    if( !empty( $user->latitude ) && !empty( $user->longitude ) ){
                      $unit_type	= docdirect_get_distance_scale();
                      $unit 		= !empty( $unit_type ) && $unit_type === 'Mi' ? 'M' : 'K';
                      $unit_2 	= !empty( $unit_type ) && $unit_type === 'mi' ? 'Mi' : 'Km';

                      if( !empty( $_GET['geo_location'] ) ) {
                        $args = array(
                          'timeout'     => 15,
                          'headers' => array('Accept-Encoding' => ''),
                          'sslverify' => false
                        );

                        $address	 = !empty($_GET['geo_location']) ? $_GET['geo_location'] : '';
                        $prepAddr	 = str_replace(' ','+',$address);

                        $url	 = 'https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&key='.$google_key;
                        $response   = wp_remote_get( $url, $args );
                        $geocode	= wp_remote_retrieve_body($response);
                        $output	  = json_decode($geocode);

                        if( isset( $output->results ) && !empty( $output->results ) ) {
                          $Latitude	= $output->results[0]->geometry->location->lat;
                          $Longitude  = $output->results[0]->geometry->location->lng;
                          $distance	= docdirectGetDistanceBetweenPoints($Latitude,$Longitude,$user->latitude,$user->longitude,$unit_2);
                        }
                      }
                      ?>
                      <?php if( !empty( $distance ) ) {?>
                        <li class="dynamic-locations"><i class='fa fa-globe'></i><span><?php esc_html_e('within','docdirect');?>&nbsp;<?php echo esc_attr($distance);?></span></li>
                      <?php } else{?>
                        <li class="dynamic-location-<?php echo intval($user->ID);?>"></li>
                        <?php
                            wp_add_inline_script( 'docdirect_functions', 'if ( window.navigator.geolocation ) {
                              window.navigator.geolocation.getCurrentPosition(
                                function(pos) {
                                  jQuery.cookie("geo_location", pos.coords.latitude+"|"+pos.coords.longitude, { expires : 365 });
                                  var with_in	= _get_distance(pos.coords.latitude, pos.coords.longitude, '.esc_js($user->latitude).','. esc_js($user->longitude).',"'.$unit.'");
                                  jQuery(".dynamic-location-'.intval($user->ID).'").html("<i class=\'fa fa-globe\'></i><span>"+scripts_vars.with_in+_get_round(with_in, 2)+scripts_vars.kilometer+"</i></span>");

                                }
                              );
                            }
                          ' );
                          }
                        ?>
                    <?php }?>
                                    </ul>
                                  </div>
                              </div>
                          <?php
                          }
                       } else{?>
                          <?php DoctorDirectory_NotificationsHelper::informations(esc_html__('No Result Found.','docdirect'));?>
                      <?php }?>
                      <?php if( isset( $search_page_map ) && $search_page_map === 'enable' ){?>
                      <script>
                          jQuery(document).ready(function() {
                               /* Init Markers */
                              docdirect_init_map_script(<?php echo json_encode( $directories );?>);
                          });
                      </script>
                      <?php }?>
                      </div>
                    </div>
                  </div>
                  <?php
                  //Pagination
                  if ($home == 1){
                    //es home
                  }else{
                      //es pagina de busqueda
                              if( $total_users > $limit ) {?>
                                  <?php docdirect_prepare_pagination($total_users,$limit);?>
                              <?php }?>
                  <?php }?>
                </div>
              </div>

            </div>
        </form>
      </div>
  </div>


</div>


<form class="doc-formtheme doc-formsearchwidget search-result-form">
<!-- Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <h5 class="modal-title" id="exampleModalLabel">Buscar</h5> -->
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php do_action( 'docdirect_search_filters' );?>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

      </div> -->
    </div>
  </div>
</div>
</form>



<?php
get_footer('mobile'); ?>
