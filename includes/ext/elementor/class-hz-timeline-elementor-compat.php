<?php
/**
 * Init elementor compatibility.
 *
 * @package    Tm_Timeline
 * @subpackage Public
 * @author     Template Monster
 * @license    GPL-3.0+
 * @copyright  2002-2017, Template Monster
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Hz_Timeline_Elementor_Compat' ) ) {

	/**
	 * Define TM_Timeline_Elementor_Compat class
	 */
	class Hz_Timeline_Elementor_Compat {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Registered shortcodes array
		 *
		 * @var array
		 */
		public $shortcodes = array();

		/**
		 * Constructor for the class
		 */
		function __construct( $shortcodes = array() ) {

			$this->shortcodes = $shortcodes;

			add_action( 'elementor/init', array( $this, 'register_category' ) );
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ) );

		}

		/**
		 * Register elementor widget
		 *
		 * @return void
		 */
		public function register_widgets( $widgets_manager ) {

			foreach ( $this->shortcodes as $data ) {
				require $data['file'];
				unset( $data['file'] );
				$widgets_manager->register_widget_type( call_user_func( array( $data['class'], 'get_instance' ) ) );
			}

		}

		/**
		 * Register cherry category for elementor if not exists
		 *
		 * @return void
		 */
		public function register_category() {

			$elements_manager = Elementor\Plugin::instance()->elements_manager;
			$existing         = $elements_manager->get_categories();
			$cherry_cat       = 'cherry';

			if ( array_key_exists( $cherry_cat, $existing ) ) {
				return;
			}

			$elements_manager->add_category(
				$cherry_cat,
				array(
					'title' => esc_html__( 'Cherry Addons', 'hz-timeline' ),
					'icon'  => 'font',
				),
				1
			);
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance( $shortcodes = array() ) {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self( $shortcodes );
			}
			return self::$instance;
		}
	}

}

/**
 * Returns instance of TM_Timeline_Elementor_Compat
 *
 * @return object
 */
function hz_timeline_elementor_compat( $shortcodes = array() ) {
	return Hz_Timeline_Elementor_Compat::get_instance( $shortcodes );
}
