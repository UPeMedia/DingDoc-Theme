<?php
/**
 *@Class headers
 *@return html
 */
if (!class_exists('docdirect_headers_link')) {

    class docdirect_headers_link {

        function __construct() {
            add_action('docdirect_init_headers_link', array(&$this, 'docdirect_init_headers_link'));
			add_action('docdirect_prepare_headers_link', array(&$this, 'docdirect_prepare_header_link'));
        }

        /**
         * @Init Header
         * @return {}
         */
        public function docdirect_init_headers_link() {
        			$post_name	= docdirect_get_post_name();
        			if(function_exists('fw_get_db_settings_option')){
        				$maintenance = fw_get_db_settings_option('maintenance');
        				$preloader = fw_get_db_settings_option('preloader');
        				$header_type = fw_get_db_settings_option('header_type');
        			} else {
        				$maintenance = '';
        				$preloader = '';
        				$header_type = '';
        			}

        			if( isset( $header_type['gadget'] ) && $header_type['gadget'] === 'header_v2' ){
        				$header_classes	= 'doc-header doc-haslayout';
        			} else{
        				$header_classes	= 'tg-haslayout tg-inner-header doc-header';
        			}


        			if ( isset($maintenance) && $maintenance == 'disable' ){
        				if( isset( $preloader['gadget'] ) && $preloader['gadget'] === 'enable' ){
        					if( isset( $preloader['enable']['preloader']['gadget'] ) && $preloader['enable']['preloader']['gadget'] === 'default' ){
        						?>
        						 <div class="preloader-outer uno">
        							  <div class="pin"></div>
        							  <div class="pulse"></div>
        						 </div>
        					<?php
        					} elseif( isset( $preloader['enable']['preloader']['gadget'] )
        							 && $preloader['enable']['preloader']['gadget'] === 'custom'
        							 && !empty( $preloader['enable']['preloader']['custom']['loader']['url'] )
        					){
        						?>
        							<div class="preloader-outer dos">
        								<div class="preloader-inner">
        									<img width="100" src="<?php echo esc_url($preloader['enable']['preloader']['custom']['loader']['url']);?>" alt="<?php esc_html_e('loader','docdirect');?>" />
        								</div>
        							</div>
        						<?php
        					}
        				}
        			}
        			?>

        			<?php get_template_part('template-parts/template','comingsoon'); ?>
                    <div id="wrapper" class="tg-haslayout ">
                        <header id="header" class="mobile <?php echo esc_attr( $header_classes );?>">
                             <?php do_action('docdirect_prepare_headers_link');?>
                        </header>
                        <?php do_action('docdirect_prepare_subheaders_link');?>
                     	<main id="main" class="tg-page-wrapper tg-haslayout">
                    <?php
		}

	    /**
         * @Prepare Header Data
         * @return {}
         */
        public function docdirect_prepare_header_link() {
            global $post, $woocommerce;
            global $current_user, $wp_roles,$userdata;

			$main_logo		= '';
			$shoping_cart	= '';
			$lang			= '';
			$res_table_title	= '';
			$res_link			= '';

            if (function_exists('fw_get_db_settings_option')) {
				$header_type = fw_get_db_settings_option('header_type');
				$main_logo = fw_get_db_settings_option('main_logo');
				$inner_logo = fw_get_db_settings_option('inner_logo');
				$shoping_cart = fw_get_db_settings_option('shoping_cart');
				$lang = fw_get_db_settings_option('lang');
				$registration = fw_get_db_settings_option('registration');
            }

			ob_start();

			if (isset($main_logo['url']) && !empty($main_logo['url'])) {
				$logo = $main_logo['url'];
			} else {
				$logo = get_template_directory_uri() . '/images/logo.png';
			}
			if( isset( $header_type['gadget'] ) && $header_type['gadget'] === 'header_v2' ){
				if( !empty( $header_type['header_v2']['topbar'] ) && $header_type['header_v2']['topbar'] === 'enable' ){?>
				<div class="doc-topbar doc-haslayout">
				  <div class="container">
					<div class="row">
					  <div class="col-sm-12">
						<?php if( !empty( $header_type['header_v2']['contact_info'] ) ){?>
							<span class="doc-contactweb"><?php echo do_shortcode( $header_type['header_v2']['contact_info'] );?></span>
						<?php }?>
						<?php if( !empty( $header_type['header_v2']['social_icons'] ) ){?>
							<div class="doc-languages">
								<?php if( !empty( $header_type['header_v2']['social_icons'] ) ){?>
									<ul class="tg-socialicon">
										<?php
											$social_icons	= $header_type['header_v2']['social_icons'];
											if(isset($social_icons) && !empty($social_icons)){
												foreach($social_icons as $social){
													$url = '';
													if(isset($social['social_url']) && !empty($social['social_url'])){
														$url = 'href="'.esc_url( $social['social_url'] ).'"';
													}else{
														$url = 'href="#"';
													}
													?>
													<li>
														<a <?php echo ($url); ?>>
															<?php if(isset($social['social_icons_list']) && !empty($social['social_icons_list'])) { ?>
															<i class="<?php echo esc_attr($social['social_icons_list']); ?>"></i>
															<?php } ?>
														</a>
													</li>
													<?php
												}
											}
										?>
									 </ul>
								<?php }?>
							</div>
						<?php }?>
					  </div>
					</div>
				  </div>
				</div>
            <?php }?>
			<div class="container">
              <div class="row">
                <div class="col-xs-12">
                  <strong class="doc-logo"><?php $this->docdirect_prepare_logo($logo,'','');?></strong>
                  <div class="doc-navigationarea">
                    <nav id="doc-nav" class="doc-nav">
                      <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#doc-navigation" aria-expanded="false">
                            <span class="sr-only"><?php esc_html_e('Toggle navigation','docdirect');?></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                      </div>
                      <div class="doc-navigation collapse navbar-collapse theme-main-navigation" id="doc-navigation">
                         <?php $this->docdirect_prepare_navigation('main-menu', '', '', '0'); ?>
                      </div>
                    </nav>
                    <div class="doc-admin"><?php $this->docdirect_prepare_registration_v2();?></div>
                  </div>
                </div>
              </div>
            </div>
            <?php
			} else{
			?>
			<?php if( !empty( $header_type['header_v1']['contact_info'] ) && !empty( $header_type['header_v1']['social_icons'] ) ){?>
            <div class="doc-topbar doc-topbar-v1 doc-haslayout">
              <div class="container">
                <div class="row">
                  <div class="col-sm-12">
                    <?php if( !empty( $header_type['header_v1']['contact_info'] ) ){?>
                    	<span class="doc-contactweb"><?php echo do_shortcode( $header_type['header_v1']['contact_info'] );?></span>
                    <?php }?>
                    <?php if( !empty( $header_type['header_v1']['social_icons'] ) ){?>
                         <div class="doc-languages">
                         	<ul class="tg-socialicon">
								<?php
									$social_icons	= $header_type['header_v1']['social_icons'];
									if(isset($social_icons) && !empty($social_icons)){
										foreach($social_icons as $social){
											?>
											<li>
												<?php
												$url = '';
												if(isset($social['social_url']) && !empty($social['social_url'])){
													$url = 'href="'.esc_url( $social['social_url'] ).'"';
												}else{
													$url = 'href="#"';
												}
												?>
												<a target="_blank" <?php echo ($url); ?>>
													<?php if(isset($social['social_icons_list']) && !empty($social['social_icons_list'])) { ?>
													<i class="<?php echo esc_attr($social['social_icons_list']); ?>"></i>
													<?php } ?>
												</a>
											</li>
											<?php
										}
									}
								?>
							 </ul>
                         </div>
                    <?php }?>
                  </div>
                </div>
              </div>
            </div>
            <?php }?>

        <div class="backgroundall"></div>
        <div class="footermenubar">
          <table>
            <tr>
                <td>
                <?php
                if( is_user_logged_in() ) {
        						$id	= $current_user->ID; ?>
                    <a href="<?php echo get_home_url(); ?>/dashboard/?ref=private-chat&identity=<?php echo $id; ?>" ><i class="fa fa-inbox fa-2x"></i></a>
                <?php } ?>
                </td>
                <td>
                  <?php
                  /*if( is_user_logged_in() ) {
          						$id	= $current_user->ID; */?>
                      <a href="<?php echo get_home_url(); ?>/cupones" ><i class="fa fa-gift fa-2x"></i></a>
                  <?php //} ?>
                </td>
                <td><a href="<?php echo get_home_url(); ?>/?page_id=4398"><i class="fa fa-home fa-2x" aria-hidden="true"></i></a></td>
                <td>
                  <?php
                  if( is_user_logged_in() ) {
          						$id	= $current_user->ID; ?>
                      <a href="<?php echo get_home_url(); ?>/dashboard/?ref=bookings&identity=<?php echo $id; ?>" ><i class="fa fa-calendar-check-o fa-2x"></i></a>
                  <?php }else{ ?>
                      <a href="<?php echo get_home_url(); ?>/faq" ><i class="fa fa-question-circle fa-2x"></i></a>
                  <?php } ?>
                </td>
                <td>
                  <?php
                  /*if( is_user_logged_in() ) {
          						$id	= $current_user->ID;*/ ?>
                      <!-- <a href="<?php echo get_home_url(); ?>/wp-login.php?action=logout&redirect_to=https%3A%2F%2Fdingdoc.com.gt%2Fwp1%2Fwp5%2Fdir-search%2F&_wpnonce=0a39c3e573" ><i class="fa fa-sign-out fa-2x"></i></a> -->
                  <?php //} ?>
                  <?php
                  if( is_user_logged_in() ) {
          						$id	= $current_user->ID; ?>
                      <a href="<?php echo get_home_url(); ?>/dashboard/?ref=wishlist&identity=<?php echo $id; ?>" ><i class="fa fa-heart fa-2x"></i></a>
                  <?php } ?>
                </td>
             </tr>
           </table>
        </div>

        <!-- <link rel="stylesheet" href="<?php echo get_home_url(); ?>/wp-content/themes/docdirect-child/slider/owl.carousel.min.css">
        <link rel="stylesheet" href="<?php echo get_home_url(); ?>/wp-content/themes/docdirect-child/slider/owlcarousel/owl.theme.default.min.css"> -->

		  <div class="container">
				<div class="row backgroundtopmenu">
					<div class="col-xs-12">
						<div class="tg-navigationarea">

                      <div class="outer-menu">

                          <input class="checkbox-toggle" type="checkbox" />
                          <div class="hamburger">
                            <div></div>
                          </div>


                        <div class="menu">
                          <div class="menuback">
                            <div class="menucuadrado">
                              <!-- <strong class="logo logomobile"><?php //$this->docdirect_prepare_logo($logo,'','');?></strong> -->
                              <!-- <strong class="logo logomobile">
                                <a class="" href="<?php echo get_home_url(); ?>/dir-search/">
                                  <img class="" src="<?php echo get_home_url(); ?>/wp-content/uploads/2020/01/logo1.png" alt="Dingdoc">
                                </a>
			                        </strong> -->
                              <?php //$this->docdirect_prepare_navigation('main-menu', '', '', '0'); ?>
                              <div class="doc-menu">
                                      <?php $this->docdirect_prepare_registration();?>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>


            </div>
					</div>
				</div>
			</div>
            <?php
			}

            echo ob_get_clean();
        }

		/**
         * @Main Logo
         * @return {}
         */
        public function docdirect_prepare_registration() {
			global $current_user, $wp_roles,$userdata,$post;

			$enable_resgistration = '';
			$enable_login = '';

			if (function_exists('fw_get_db_settings_option')) {
				$enable_resgistration = fw_get_db_settings_option('registration');
				$enable_login = fw_get_db_settings_option('enable_login');
			}
			ob_start();
			$dir_obj	= new DocDirect_Scripts_link();

			if( $enable_login === 'enable' || $enable_resgistration === 'enable' ) {
			?>
			<ul class="tg-login-logout">
				<?php if( is_user_logged_in() ) {
						$user_identity	= $current_user->ID;
						$avatar = apply_filters(
							'docdirect_get_user_avatar_filter',
							 docdirect_get_user_avatar(array('width'=>150,'height'=>150), $user_identity) //size width,height
						);

						$first_name   		   = get_user_meta( $user_identity, 'first_name', true);
						$last_name   		    = get_user_meta( $user_identity, 'last_name', true);
						$display_name   		 = get_user_meta( $user_identity, 'display_name', true);

						if( !empty( $first_name ) ){
							$username	= $first_name;
						} else if( !empty( $last_name ) ){
							$username	= $last_name;
						} else{
							$username	= $display_name;
						}
					?>
					<li class="session-user-info">
            <!-- <a href="javascript:;"> -->
              <span class="s-user">
                <?php echo esc_attr( $username );?></span>
                <img alt="<?php esc_html_e('Welcome','docdirect');?>" src="<?php echo esc_url( $avatar );?>">
              <!-- </a> -->
                <?php $dir_obj->docdirect_profile_menu('dashboard');?>
          </li>
					<?php } else {?>
                    <!-- <li class="session-user-info"><a href="<?php echo get_home_url(); ?>/dir-search/">Buscar</a></li> -->
                    <li class="session-user-info">
                          <a href="javascript:;" data-toggle="modal" data-target=".tg-user-modal">
                          <span class="s-user get-user-modal">
                            <?php esc_html_e('Login/Register','docdirect');?>
                          </span>
                          <img alt="<?php esc_html_e('Login','docdirect');?>" src="<?php echo get_template_directory_uri() . '/images/singin_icon.png';?>">
                        </a>
                        <span>
                          <a href="javascript:;" data-toggle="modal" data-target=".tg-user-modal"></a>
                        </span>
                        <?php if( isset( $_GET['invitation'] ) && $_GET['invitation'] === 'signup' ) {?>
							<script>
                                jQuery(window).load(function() {
								  setTimeout(function(){
									jQuery('.get-user-modal').trigger('click');
									jQuery('.trigger-signup-formarea').trigger('click');
								  }, 2000);
								});
                            </script>
                        <?php }?>
                     </li>
				<?php }?>
			</ul>
			<?php
			}
			echo ob_get_clean();
		}

		/**
         * @Main Logo
         * @return {}
         */
        public function docdirect_prepare_registration_v2() {
			global $current_user, $wp_roles,$userdata,$post;

			$enable_resgistration = '';
			$enable_login = '';

			if (function_exists('fw_get_db_settings_option')) {
				$enable_resgistration = fw_get_db_settings_option('registration');
				$enable_login = fw_get_db_settings_option('enable_login');
			}
			ob_start();
			$dir_obj	= new DocDirect_Scripts_link();

			if( $enable_login === 'enable' || $enable_resgistration === 'enable' ) {
					 if( is_user_logged_in() ) {
						$user_identity	= $current_user->ID;
						$avatar = apply_filters(
							'docdirect_get_user_avatar_filter',
							 docdirect_get_user_avatar(array('width'=>150,'height'=>150), $user_identity) //size width,height
						);

						$first_name   		   = get_user_meta( $user_identity, 'first_name', true);
						$last_name   		    = get_user_meta( $user_identity, 'last_name', true);
						$display_name   		 = get_user_meta( $user_identity, 'display_name', true);

						if( !empty( $first_name ) ){
							$username	= $first_name;
						} else if( !empty( $last_name ) ){
							$username	= $last_name;
						} else{
							$username	= $display_name;
						}
					?>
                    <div class="doc-user">
                        <div class="doc-dropdown">
                            <figure class="doc-adminpic">
                                <a href="javascript:;"><img src="<?php echo esc_url( $avatar );?>" alt="<?php esc_html_e('Welcome','docdirect');?>"></a>
                            </figure>
                            <a href="javascript:;" class="doc-usermenu doc-btndropdown">
                                <em><?php echo esc_attr( $username );?></em>
                            </a>
                            <div class="dropdown-menu doc-dropdownbox doc-usermenu">
                                <?php $dir_obj->docdirect_profile_menu('menu');?>
                            </div>
                       </div>
                   </div>
                 <?php } else {?>
                    	<a class="doc-btn get-user-modal" href="javascript:;" data-toggle="modal" data-target=".tg-user-modal"><?php esc_html_e('Become A Member','docdirect');?></a>
                        <?php if( isset( $_GET['invitation'] ) && $_GET['invitation'] === 'signup' ) {?>
							<script>
                                jQuery(window).load(function() {
								  setTimeout(function(){
									jQuery('.get-user-modal').trigger('click');
									jQuery('.trigger-signup-formarea').trigger('click');
								  }, 2000);
								});
                            </script>
                        <?php }?>
				<?php }?>
			<?php
			}
			echo ob_get_clean();
		}

		/**
         * @Main Logo
         * @return {}
         */
        public function docdirect_prepare_logo($logo_url='',$image_classes='',$link_classes='') {
			ob_start();
			?>
			<a class="<?php echo esc_attr( $link_classes );?>" href="<?php echo esc_url(home_url('/')); ?>"><img class="<?php echo esc_attr( $image_classes );?>" src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo()); ?>"></a>
			<?php
			echo ob_get_clean();
		}

        /**
         * @Main Navigation
         * @return {}
         */
        public static function docdirect_prepare_navigation($location = '', $id = 'menus', $class = '', $depth = '0') {

		   if ( has_nav_menu($location) ) {
                $defaults = array(
                    'theme_location' => "$location",
                    'menu' => '',
                    'container' => '',
                    'container_class' => '',
                    'container_id' => '',
                    'menu_class' => "$class",
                    'menu_id' => "$id",
                    'echo' => false,
                    'fallback_cb' => 'wp_page_menu',
                    'before' => '',
                    'after' => '',
                    'link_before' => '',
                    'link_after' => '',
                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth' => "$depth",
                    //'walker' => new Doctor Directory_Menu_Walker
				);
                echo do_shortcode(wp_nav_menu($defaults));
            } else {
                $defaults = array(
                    'theme_location' => "",
                    'menu' => '',
                    'container' => '',
                    'container_class' => '',
                    'container_id' => '',
                    'menu_class' => "$class",
                    'menu_id' => "$id",
                    'echo' => false,
                    'fallback_cb' => 'wp_page_menu',
                    'before' => '',
                    'after' => '',
                    'link_before' => '',
                    'link_after' => '',
                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth' => "$depth",
                    'walker' => '');
                echo do_shortcode(wp_nav_menu($defaults));
            }
        }

    }

    new docdirect_headers_link();
}
