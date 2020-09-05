<?php
/**
 * Provide admin-related functionality.
 *
 * @package    Hz_Forum
 * @subpackage Hz_Forum_Admin
 * @author     HZA
 */

defined( 'ABSPATH' ) or die( 'No direct script allowed' );

/**
 * Register class if it does not exists already.
 */
if ( ! class_exists( 'Hz_Forum_Admin' ) ) {
	// Load Tm_Timeline_View if class was not initialized yet.
	if ( ! class_exists( 'Hz_Forum_View' ) ) {
		require hz_forum_plugin_path( 'classes/class-hz-forum-view.php' );
	}
	/**
	 * Class contains admin-related functionality.
	 */
	class Hz_Forum_Admin {

		/**
		 * Determine if initialization is required.
		 *
		 * @var bool
		 */
		private static $_initialized = false;

		/**
		 * Views renderer.
		 *
		 * @var Tm_Timeline_View Timeline view instance
		 */
		private static $_view;

		/**
		 * Initialize plugin admin.
		 *
		 * @since 1.0.0
		 * @since 1.0.5 Changed js/css registration - added `Tm_Timeline_Admin::assets` method.
		 */
		public static function initialize() {

			// Initialize only if not initialized already
			if ( ! self::$_initialized ) {

				$views_path  = hz_forum_plugin_path( 'admin/views' );
				self::$_view = new Hz_Forum_View( $views_path );

				//add_action( 'init', array( __CLASS__, 'init_post_type_topic' ) );
				//add_action( 'init', array( __CLASS__, 'init_post_type_replies' ) );
				add_action( 'init', array( __CLASS__, 'hz_create_forum_page' ) );

				//add_action( 'wp_enqueue_scripts', array( __CLASS__, 'assets' ) );

				self::$_initialized = false;
			}
		}

		/**
		 * Attach admin javascript and styleshet.
		 *
		 */
		public static function assets( ) {

			wp_enqueue_script(
				'hz-forum-admin-js',
				hz_forum_plugin_url( "/admin/js/hz-forum.js" ),
				array( 'jquery' ),
				HZ_FORUM_VERSION,
				true
			);

			wp_enqueue_style(
				'hz-forum-admin-css',
				hz_forum_plugin_url( "/admin/css/hz-forum.css" ),
				array(),
				HZ_FORUM_VERSION
			);
		}


		/**
		 * Render the `post-event-date` custom field.
		 *
		 * @param WP_Post $post WordPress post.
		 * @param array   $atts Metabox options.
		 */
		public static function render_hz_topic_meta_box(WP_Post $post,	array $atts	) {
			// Gather the values.
			$id    = $atts['id'];
			$title = $atts['title'];
			$value = get_post_meta( $post->ID, $id, true );
			$nonce = wp_create_nonce( basename( __FILE__ ) );
			self::init_meta_box_assets();
			// Render & print the view
			ob_start();
			include hz_forum_plugin_path("admin/views/reply-meta-box_fields.php");
			$htnl = ob_get_clean();
			echo $htnl;
		}

		/**
		 * Initialize metabox assets.
		 */
		public static function init_meta_box_assets() {
			global $wp_scripts;

			$suffix = '';//defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			// Attach the assets for jquery's datepicker.
			wp_enqueue_script( 'jquery-ui-datepicker' );
			// Include our custom jQuery file with WordPress Color Picker dependency
        	wp_enqueue_script( 'wp-color-handler', hz_timeline_plugin_url( '/js/wpcolor_handler.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); 

			$jqui_core = $wp_scripts->query( 'jquery-ui-core' );
			$version  = '1.11.4';

			if ( property_exists( $jqui_core, 'ver' ) ) {
				$version = $jqui_core->ver;
			}

			$jqui_theme = 'http://ajax.googleapis.com/ajax/libs/jqueryui/' . $version
				. "/themes/smoothness/jquery-ui{$suffix}.css";

			wp_enqueue_style( 'jquery-ui-core', $jqui_theme, false, $version );
			// Add the color picker css file
        	wp_enqueue_style( 'wp-color-picker' );
			// Attach admin styles for the metabox.
		}
		/**
		 * Return Forum Page Template
		*/
		public static function hz_create_forum_page(){
			if(hz_page_exists('forum') == 0){
				wp_insert_post(
					array(
						'post_type'=>'page',
						'post_status'=>'publish',
						'post_title'=>'Forum',
						'post_name'=>'forum'
						)
					);
			}
		}

	}
}
