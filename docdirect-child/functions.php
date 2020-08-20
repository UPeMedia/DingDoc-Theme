<?php
/**
 * Theme functions file
 */

/**
 * Enqueue parent theme styles first
 * Replaces previous method using @import
 * <http://codex.wordpress.org/Child_Themes>
 */



function docdirect_child_theme_enqueue_styles() {

		//cambio se agrego especificos para template link
		if ( (get_page_template_slug() == 'directory/template-link.php') || (get_page_template_slug() == 'directory/template-content-link.php') || ( $_GET["tipo"] == 'link') || ($_GET["page_id"] == '4423')   ) {
        //  global $wp_session;
        // $wp_session['link'] = '2';

				wp_enqueue_style('bootstrap.min', get_template_directory_uri() . '/css/bootstrap.min.css');

				wp_enqueue_style( 'style-link' , get_template_directory_uri() . '/css/style-link.css' );
			  wp_enqueue_style( 'stylechild-link', get_template_directory_uri() . '/css/stylechild-link.css' );


		}else{ //website
			wp_enqueue_style('bootstrap.min', get_template_directory_uri() . '/css/bootstrap.min.css');

			$parent_theme_version = wp_get_theme('docdirect');
			$child_theme_version  = wp_get_theme('docdirect-child');
				$parent_style  = 'docdirect_theme_style';
			wp_enqueue_style( 'docdirect_child_style', get_stylesheet_directory_uri() . '/style.css',
			array( $parent_style ),		$child_theme_version->get('Version')  );

				wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css',
				array('bootstrap.min','choosen'),$parent_theme_version->get( 'Version' ));


		}


}

add_action( 'wp_enqueue_scripts', 'docdirect_child_theme_enqueue_styles' );
