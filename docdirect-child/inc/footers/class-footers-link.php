<?php
/**
 * @Class footers
 *
 */
if (!class_exists('docdirect_footers_link')) {

    class docdirect_footers_link {

        function __construct() {
            add_action('docdirect_prepare_footers_link', array(&$this, 'docdirect_prepare_footer_link'));
        }

        /**
         * @Prepare Top Strip
         * @return {}
         */
        public function docdirect_prepare_footer_link() {
			$footer_copyright		= '&copy;'.date('Y').esc_html__(' | All Rights Reserved ','docdirect').get_bloginfo();
			$enable_widget_area	= '';
			$enable_registration		= '';
			$enable_login		= '';

			if(function_exists('fw_get_db_settings_option')) {
				$footer_type	= fw_get_db_settings_option('footer_type', $default_value = null);
				$enable_widget_area	= fw_get_db_settings_option('enable_widget_area', $default_value = null);
				$footer_copyright 	= fw_get_db_settings_option('footer_copyright', $default_value = null);
				$enable_registration = fw_get_db_settings_option('registration', $default_value = null);
				$enable_login = fw_get_db_settings_option('enable_login', $default_value = null);
			}
			?>

            <?php
				if( isset( $footer_type['gadget'] ) && $footer_type['gadget'] === 'footer_v2' ){
					$this->docdirect_prepare_footer_v2_link();

					//Forgot password
					if (!is_user_logged_in()) {
						do_shortcode('[user_lostpassword]');
					}

					//Check if Auth Key is not empty then call the hook
					if(!empty($_GET['activation_key'])){
						$verify_key = $_GET['activation_key'];
						do_action('docdirect_welcome_page', $verify_key);
					}

					//Reset Model
					if ( !empty($_GET['key'])
						&&
						( isset( $_GET['action'] ) && $_GET['action'] == "reset_pwd" )
						&&
						( !empty( $_GET['login'] ) )
					) {
					   do_action('docdirect_reset_password_form');
					}

				} else {?>
                <footer id="footer" class="tg-haslayout footer-v1">


                </footer>
                <?php
				//Account Verifications Model
				if ( !empty($_GET['key']) && !empty($_GET['verifyemail']) ) {
					do_action('docdirect_verify_user_account');
				}

				//Registration modal
				if( ( isset( $enable_login ) && $enable_login === 'enable' )
                            ||  ( isset( $enable_registration ) && $enable_registration === 'enable' )
                      ) {
                    do_shortcode('[user_authentication]'); //Code Moved To due to plugin territory
                }

				//Forgot password
				if (!is_user_logged_in()) {
					do_shortcode('[user_lostpassword]');
				}

				//Check if Auth Key is not empty then call the hook
				if(!empty($_GET['activation_key'])){
					$verify_key = $_GET['activation_key'];
					do_action('docdirect_welcome_page', $verify_key);
				}

				//Reset Model
				if ( !empty($_GET['key'])
					&&
					( isset( $_GET['action'] ) && $_GET['action'] == "reset_pwd" )
					&&
					( !empty( $_GET['login'] ) )
				) {
				   do_action('docdirect_reset_password_form');
				}
			}?>
		</div>
        <?php
		}

		/**
         * @Prepare Top Strip
         * @return {}
         */
        public function docdirect_prepare_footer_v2_link() {
			$footer_copyright		= '&copy;'.date('Y').esc_html__(' | All Rights Reserved ','docdirect').get_bloginfo();
			$enable_widget_area	= '';
			$enable_registration		= '';
			$enable_login		= '';
			$footer_type		= '';

			if(function_exists('fw_get_db_settings_option')) {
				$footer_type	= fw_get_db_settings_option('footer_type', $default_value = null);
				$enable_widget_area	= fw_get_db_settings_option('enable_widget_area', $default_value = null);
				$footer_copyright 	= fw_get_db_settings_option('footer_copyright', $default_value = null);
				$enable_registration = fw_get_db_settings_option('registration', $default_value = null);
				$enable_login = fw_get_db_settings_option('enable_login', $default_value = null);
			}

			?>
    </div> <!-- android-content -->
  </div> <!-- pagina -->


  <div class="modal fade modal-encuesta" tabindex="-1" role="dialog">
      <div class="tg-modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
          <div class="panel-lostps">
              <div class="tg-form-modal divformencuesta">
                      <div class="form-group">
                          <?php
                          get_template_part('directory/provider-page/template-author', 'reviews');
                          ?>
                      </div>
              </div>
          </div>
      </div>
  </div>


        <?php
        //echo '<script src="'.get_template_directory_uri().'-child/js/material.min.js"></script>';
        ?>
            <?php
				//Account Verifications Model
				if ( !empty($_GET['key']) && !empty($_GET['verifyemail']) ) {
					do_action('docdirect_verify_user_account');
				}

				//Registration modal
				if( ( isset( $enable_login ) && $enable_login === 'enable' )
                            ||  ( isset( $enable_registration ) && $enable_registration === 'enable' )
                      ) {
                    do_shortcode('[user_authentication_link]'); //Code Moved To due to plugin territory
                }
		}

    }

    new docdirect_footers_link();
}
