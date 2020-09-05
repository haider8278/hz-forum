<?php
/**
 * Plugin Name: HZ Forum
 * Plugin URI: https://tcawglobal.com
 * Description: This plugin allows users to build forum topics and post replies.
 * Author: TCAW Global
 * Author URI: https://tcawglobal.com
 * Text Domain: hz-forum
 * Version:     1.1.0
 * Domain Path: /languages
 *
 */

defined( 'ABSPATH' ) or die( 'No direct script allowed' );

define( 'HZ_FORUM_VERSION', '1.1.0' );
add_action( 'plugins_loaded', 'hz_forum_lang', 1 );
add_action( 'plugins_loaded', 'hz_forum_init_admin', 2 );
add_action( 'plugins_loaded', 'hz_forum_init', 3 );


register_activation_hook( __FILE__, 'hz_forum_activate' );
register_deactivation_hook( __FILE__, 'hz_forum_deactivate' );


add_action('init', 'hz_forum_scripts');
function hz_forum_scripts() {
	wp_enqueue_style( 'hz-magnificpop-css', hz_forum_plugin_url('/js/magnific/magnific-popup.css'));
	wp_enqueue_style( 'hz-tagsinput-css', hz_forum_plugin_url('/js/tagsinput/bootstrap-tagsinput.css'));
	wp_enqueue_style( 'hz-style', hz_forum_plugin_url('/css/hz-forum.css'));

	wp_enqueue_script('jquery');
	wp_enqueue_script('hz-tinymce',site_url()."/wp-includes/js/tinymce/tinymce.min.js",array('jquery'),TRUE);
	wp_enqueue_script('magnific-popup',hz_forum_plugin_url('/js/magnific/jquery.magnific-popup.min.js'),array('jquery'),TRUE);
	wp_enqueue_script('tagsinput-js',hz_forum_plugin_url('/js/tagsinput/bootstrap-tagsinput.js'),array('jquery'),TRUE);

}

/**
 * Timeline plugin main file.
 */
require dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'functions.php';


if ( ! function_exists( 'hz_forum_lang' ) ) {
	/**
	 * Load translations.
	 */
	function hz_forum_lang() {
		load_plugin_textdomain( 'hz-forum', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
}

if ( ! function_exists( 'hz_forum_init_admin' ) ) {
	/**
	 * Plugin admin initialization callback.
	 */
	function hz_forum_init_admin() {

		// Prevent non admin access.
		if ( ! is_admin() ) {
			return;
		}

		// Load class if it's not initialized yet.
		if ( ! class_exists( 'Hz_Forum_Admin' ) ) {
			require hz_forum_plugin_path( 'admin/classes/class-hz-forum-admin.php' );
		}

		// Initialize plugin admin.
		Hz_Forum_Admin::initialize();
	}
}

if ( ! function_exists( 'hz_forum_init' ) ) {
	/**
	 * Plugin initialization callback.
	 */
	function hz_forum_init() {

		// Load class if it's not initialized yet.
		if ( ! class_exists( 'Hz_Forum' ) ) {
			require hz_forum_plugin_path( 'classes/class-hz-forum.php' );
		}

		// Initialize plugin frontend.
		Hz_Forum::initialize();
	}
}

if ( ! function_exists( 'hz_forum_activate' ) ) {
	/**
	 * Plugin activation callback.
	 */
	function hz_forum_activate() {
		hz_forum_init_admin();
		flush_rewrite_rules();
	}
}


if ( ! function_exists( 'hz_forum_deactivate' ) ) {
	/**
	 * Plugin deactivation callback.
	 */
	function hz_forum_deactivate() {
		flush_rewrite_rules();
	}
}