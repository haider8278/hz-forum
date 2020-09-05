<?php
/**
 * Provide front-end related functionality.
 *
 * @package    Hz_Timeline
 * @subpackage Hz_Timeline
 * @author     HZA
 */

defined( 'ABSPATH' ) or die( 'No direct script allowed' );

/**
 * Register class if it does not exists already.
 */
if ( ! class_exists( 'Hz_Forum' ) ) {

	// Load Tm_Timeline_View if class was not initialized yet.
	if ( ! class_exists( 'Hz_Forum_View' ) ) {
		require hz_forum_plugin_path( 'classes/class-hz-forum-view.php' );
	}

	/**
	 * Class contains front-end related functionality.
	 */
	class Hz_Forum {

		/**
		 * Determine if initialization is required
		 *
		 * @var bool Initialized flag
		 */
		private static $_initialized = false;

		/**
		 * Views renderer.
		 *
		 * @var Tm_Timeline_View View renderer instance
		 */
		private static $_view;

		/**
		 * Shortcode tag.
		 *
		 * @since 1.1.0
		 * @var string
		 */
		private static $shortcode_tag = 'hz-timeline_old';

		/**
		 * Initialize plugin frontend.
		 *
		 */
		public static function initialize() {

			// Initialize only if not initialized already
			if ( ! self::$_initialized ) {

				$views_path  = hz_forum_plugin_path( 'views' );
				self::$_view = new Hz_Forum_View( $views_path );

				self::init_filters();

				add_action( 'init', array( __CLASS__, 'init_post_type' ) );
				add_action( 'wp_enqueue_scripts', array( __CLASS__, 'init_shortcode_assets' ) );
				add_filter( 'page_template', array( __CLASS__, 'hz_forum_page_template') );
				add_filter('archive_template', array( __CLASS__, 'hz_topics_archive_page_template') );
				add_filter('single_template', array( __CLASS__, 'hz_topics_single_page_template') );
				add_action('wp_ajax_hz_post_reply',array(__CLASS__,'hz_post_new_reply'));
				add_action('wp_ajax_hz_post_topic',array(__CLASS__,'hz_post_new_topic'));

				self::$_initialized = false;
			}
		}

		/**
		 * Initialize custom post type topic.
		 *
		 */
		public static function init_post_type() {
			$post_labels = array(
				'name'      => esc_html__( 'Topics', 'hz-forum' ),
				'singular'  => esc_html__( 'Topic', 'hz-forum' ),
				'menu_name' => esc_html__( 'Community', 'hz-forum' ),
				'add_new'	=> esc_html__( 'Add New Topic', 'hz-forum' ),
				'add_new_item'	=> esc_html__( 'Add New Topic', 'hz-forum' ),
				'edit_item'	=> esc_html__( 'Edit Topic', 'hz-forum' ),
			);

			register_post_type(
				'topic',
				apply_filters( 'hz_forum_register_post_type_args', array(
					'labels'              => $post_labels,
					'capability_type'     => 'post',
					'description'         => esc_html__( 'Topic item', 'hz-forum' ),
					'public'             => true,
					'publicly_queryable' => true,
					'show_ui'            => true,
					'show_in_menu'       => true,
					'query_var'          => true,
					'has_archive'        => true,
					'menu_position'       => 25,
					'menu_icon'           => 'dashicons-admin-comments',
					'rewrite'             => array('slug' => 'topics'),
					'supports'  		  => array('title','editor','custom_fields','thumbnail'),
					'taxonomies' 		  => array('topic_categories','topic_tags'),
				) )
			);

			$tax_labels = array(
				'name'     => esc_html__( 'Topic Categories', 'hz-timeline' ),
				'singular' => esc_html__( 'Topic Category', 'hz-timeline' ),
				'add_new_item'	=> esc_html__( 'Add Topic Category', 'hz-timeline' ),
				'edit_item'		=> esc_html__( 'Edit Topic Category', 'hz-timeline' ),
				'search_items'	=> esc_html__( 'Search Topic Categories', 'hz-timeline' ),
			);

			register_taxonomy(
				'topic_categories',
				'topic',
				apply_filters( 'hz_forum_register_taxonomy_args', array(
					'labels'              => $tax_labels,
					'description'         => esc_html__( 'Topic Categories', 'hz-forum' ),
					'exclude_from_search' => false,
					'publicly_queryable'  => true,
					'show_tagcloud'       => true,
					'show_ui'             => true,
					'show_admin_column'   => true,
					'hierarchical'		  => true,
					'rewrite'             => array(
						'slug' => 'topic_categories',
					),
				) )
			);

			$tag_labels = array(
				'name'     => esc_html__( 'Topic Tags', 'hz-timeline' ),
				'singular' => esc_html__( 'Topic Tag', 'hz-timeline' ),
				'add_new_item'	=> esc_html__( 'Add Topic Tag', 'hz-timeline' ),
				'edit_item'		=> esc_html__( 'Edit Topic Tag', 'hz-timeline' ),
				'search_items'	=> esc_html__( 'Search Topic Tags', 'hz-timeline' ),
			);
			register_taxonomy(
				'topic_tags',
				'topic',
				apply_filters( 'hz_forum_register_taxonomy_args', array(
					'labels'              => $tag_labels,
					'description'         => esc_html__( 'Topic Tags', 'hz-forum' ),
					'exclude_from_search' => false,
					'publicly_queryable'  => true,
					'show_tagcloud'       => true,
					'show_ui'             => true,
					'show_admin_column'   => true,
					'hierarchical'		  => false,
					'rewrite'             => array(
						'slug' => 'topic_tags',
					),
				) )
			);

			$post_labels = array(
				'name'      => esc_html__( 'Replies', 'hz-forum' ),
				'singular'  => esc_html__( 'Reply', 'hz-forum' ),
				'menu_name' => esc_html__( 'Replies', 'hz-forum' ),
				'add_new'	=> esc_html__( 'Add New Reply', 'hz-forum' ),
				'add_new_item'	=> esc_html__( 'Add New Reply', 'hz-forum' ),
				'edit_item'	=> esc_html__( 'Edit Reply', 'hz-forum' ),
			);

			register_post_type(
				'reply',
				apply_filters( 'hz_forum_register_post_type_args', array(
					'labels'              => $post_labels,
					'capability_type'     => 'post',
					'description'         => esc_html__( 'Reply item', 'hz-forum' ),
					'exclude_from_search' => false,
					'public'              => true,
					'publicly_queryable'  => true,
					'show_ui'             => true,
					'menu_position'       => 25,
					'show_in_menu' => 'edit.php?post_type=topic',
					//'menu_icon'           => 'dashicons-admin-comments',
					'rewrite'             => array(
						'slug' => 'reply',
					),
				) )
			);

			add_action( 'add_meta_boxes', array(__CLASS__,'hz_reply_register_meta_boxes') );
		}


		// public static function hz_rewrite_rules_for_topics($wp_rewrite) {
		// 	$rules = array();
		// 	$terms = get_terms( array(
		// 		'taxonomy' => 'topic_categories',
		// 		'hide_empty' => false,
		// 	) );

		// 	$post_type = 'topic';
		// 	foreach ($terms as $term) {
		// 		$rules['topics/' . $term->slug . '/([^/]*)$'] = 'index.php?post_type=' . $post_type. '&topic=$matches[1]&name=$matches[1]';
		// 	}
		// 	echo '<pre>';
		// 	print_r($rules);
		// 	echo '</pre>';exit();
		// 	// merge with global rules
		// 	$wp_rewrite->rules = $rules + $wp_rewrite->rules;
		// }


		/**
		 * Register `post-event-date` custom field. Used to store date value
		 */
		public static function hz_reply_register_meta_boxes() {
			add_meta_box(
				'hz-topic-replies-metabox',
				esc_html__( 'Replies Attributes', 'hz-forum' ),
				array(
					'Hz_Forum_Admin',
					'render_hz_topic_meta_box',
				),
				'topic-reply',
				'side',
				'high'
			);
		}


		/**
		 * Retrieve a shortcode tag.
		 *
		 * @return string
		 */
		public static function get_shortcode_tag() {
			return apply_filters( 'hz_timeline_shortcode_tag', self::$shortcode_tag );
		}

		/**
		 * Retrieve a shortcode attributes.
		 *
		 * @return array
		 */
		public static function get_shortcode_atts() {
			return apply_filters( 'hz_timeline_get_shortcode_atts', array(
				'layout' => array(
					'type'        => 'select',
					'title'       => esc_html__( 'Layout', 'hz-timeline' ),
					'description' => esc_html__( 'Layout type', 'hz-timeline' ),
					'options'     => wp_list_pluck( self::get_supported_layouts(), 'title' ),
					'value'       => '0',
					'default'     => '0',
				),
				'visible-items' => array(
					'type'        => 'slider',
					'title'       => esc_html__( 'Visible items', 'hz-timeline' ),
					'description' => esc_html__( 'Timeline number to show (only for "Horizontal" layout)', 'hz-timeline' ),
					'value'       => 3,
					'max_value'   => 5,
					'min_value'   => 1,
					'condition' => array(
						'layout' => '0',
					),
				),
				'date-format' => array(
					'type'    => 'select',
					'title'   => esc_html__( 'Date format', 'hz-timeline' ),
					'options' => wp_list_pluck( self::get_supported_date_formats(), 'title' ),
					'value'   => '0',
					'default' => '0',
				),
				'tag' => array(
					'type'        => 'select',
					'title'       => esc_html__( 'Tag', 'hz-timeline' ),
					'description' => esc_html__( 'Tag slug empty value mean that no filtering will be performed', 'hz-timeline' ),
					'class'       => 'cherry-multi-select',
					'multiple'    => true,
					'options'     => false,
					'options_cb'  => array( __CLASS__, 'get_tags' ),
					'value'       => '',
				),
				'anchors' => array(
					'type'        => 'switcher',
					'title'       => esc_html__( 'Anchors', 'hz-timeline' ),
					'description' => esc_html__( 'Post title as anchor to the post', 'hz-timeline' ),
					'toggle'      => array(
						'true_toggle'  => esc_html__( 'Yes', 'hz-timeline' ),
						'false_toggle' => esc_html__( 'No', 'hz-timeline' ),
					),
					'value'   => 'off',
					'default' => 'off',
				),
				'order' => array(
					'type'        => 'select',
					'title'       => esc_html__( 'Order', 'hz-timeline' ),
					'description' => esc_html__( 'Sort order', 'hz-timeline' ),
					'options'     => array(
						'ASC'  => esc_html__( 'Ascending', 'hz-timeline' ),
						'DESC' => esc_html__( 'Descending', 'hz-timeline' ),
					),
					'value'   => 'DESC',
					'default' => 'DESC',
				),
			) );
		}

		/**
		 * Get default shortcode configuration.
		 *
		 * @return array
		 */
		public static function get_default_attrs() {
			return apply_filters( 'hz_timeline_shortcode_default_attrs', array(
				'layout'        => 1, // Horizontal layout
				'visible-items' => 5, // 5 visible items
				'date-format'   => 2, // `Y.m.d` date format
				'tag'           => '', // Tag slug, empty value mean that no filtering will be performed
				'anchors'       => true, // Post title as anchor to the post
				'order'         => 'DESC', // Sort order (ASC|DESC)
			) );
		}

		/**
		 * Get supported layouts list.
		 *
		 * @return array
		 */
		public static function get_supported_layouts() {
			return apply_filters( 'hz_timeline_shortcode_supported_layouts', array(
				0 => array(
					'title' => esc_html__( 'Horizontal', 'hz-timeline' ),
					'view'  => 'horizontal',
				),
				1 => array(
					'title' => esc_html__( 'Vertical', 'hz-timeline' ),
					'view'  => 'vertical',
				),
				2 => array(
					'title' => esc_html__( 'Vertical (chess order)', 'hz-timeline' ),
					'view'  => 'vertical-chessorder',
				),
			) );
		}

		/**
		 * Get supported date formats.
		 *
		 * @return array
		 */
		public static function get_supported_date_formats() {
			return apply_filters( 'hz_timeline_shortcode_supported_date_formats', array(
				array(
					'title'  => esc_html__( 'YYYY - MM - DD', 'hz-timeline' ),
					'format' => 'Y-m-d',
				),
				array(
					'title'  => esc_html__( 'YYYY / MM / DD', 'hz-timeline' ),
					'format' => 'Y/m/d',
				),
				array(
					'title'  => esc_html__( 'YYYY . MM . DD', 'hz-timeline' ),
					'format' => 'Y.m.d',
				),
				array(
					'title'  => esc_html__( 'DD - MM - YYYY', 'hz-timeline' ),
					'format' => 'd-m-Y',
				),
				array(
					'title'  => esc_html__( 'DD / MM / YYYY', 'hz-timeline' ),
					'format' => 'd/m/Y',
				),
				array(
					'title'  => esc_html__( 'DD . MM . YYYY', 'hz-timeline' ),
					'format' => 'd.m.Y',
				),
				array(
					'title'  => esc_html__( 'MM', 'hz-timeline' ),
					'format' => 'm',
				),
				array(
					'title'  => esc_html__( 'YYYY', 'hz-timeline' ),
					'format' => 'Y',
				),
			) );
		}

		/**
		 * Shortcode rendering function.
		 *
		 * @param  array $atts Shortcode attributes.
		 * @return string
		 */
		public static function shortcode_frontend( $atts ) {
			$defaults = wp_list_pluck( self::get_shortcode_atts(), 'value' );
			$args     = shortcode_atts( $defaults, $atts, self::get_shortcode_tag() );

			$args['anchors']   = filter_var( $args['anchors'], FILTER_VALIDATE_BOOLEAN );
			$supported_layouts = self::get_supported_layouts();
			$view              = $supported_layouts[ $defaults['layout'] ]['view'];
			$layout            = intval( $args['layout'] );
			$pages             = array();

			if ( isset( $supported_layouts[ $layout ] ) &&
				isset( $supported_layouts[ $layout ]['view'] )
			) {
				$view = $supported_layouts[ $layout ]['view'];
			}

			$qargs = array(
				'post_type'      => 'hza_slide',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				//'meta_key'       => 'post-event-date',
				//'orderby'        => 'meta_value_num',
				'order'          => 'DESC',
			);

			// If tag is defined, add `tax_query` into the `$qargs`
			if ( false === empty( $args['tag'] ) ) {
				$tag = get_term_by(
					'slug',
					$args['tag'],
					'timeline_tax'
				);

				if ( $tag ) {
					$qargs['tax_query'] = array(
						array(
							'taxonomy' => 'timeline_tax',
							'field'    => 'term_id',
							'terms'    => array(
								$tag->term_id,
							),
						),
					);
				}
			}

			if ( false === empty( $args['order'] ) ) {
				$qargs['order'] = in_array( $args['order'], array( 'ASC', 'DESC' ) ) ? $args['order'] : 'DESC';
			}

			$qargs = apply_filters( 'hz_timeline_query_args', $qargs, $atts );

			// Get posts.
			$query = new WP_Query( $qargs );

			if ( 0 === $layout ) {
				$pages = self::get_pages( $query->posts, $args['visible-items'] );

				/**
				 * Filter a flag that control enqueue for shortcode script.
				 *
				 * @since 1.0.5
				 */
				if ( false === apply_filters( 'hz_timeline_remove_shortcode_script', false ) ) {
					wp_enqueue_script( 'hz-timeline-js' );
				}
			}

			// Return the rendered shortcode.
			return self::$_view->render(
				$view,
				array(
					'config'          => $args,
					'pages'           => $pages,
					'timeline_events' => $query->posts,
				)
			);
		}

		/**
		 * Calculate pages based on visible_items count.
		 *
		 * @param  array $timeline_events Collection of all timeline posts.
		 * @param  int   $visible_items   Limit the visible items (only for horizontal layout).
		 * @return array
		 */
		private static function get_pages( array $timeline_events, $visible_items = -1 ) {
			$pages = array();
			$total = sizeof( $timeline_events );

			// If no visible items, show all.
			if ( 0 >= $visible_items ) {
				$visible_items = $total;
			}

			if ( $total === $visible_items ) {

				// We got only one page.
				$pages = array(
					$timeline_events
				);

			} else {
				$pages = array_chunk( $timeline_events, $visible_items, true );
			}

			return $pages;
		}

		/**
		 * Retrieve the terms in a taxonomy.
		 *
		 * @param  string $tax The taxonomies to retrieve terms from.
		 * @param  string $key Key for array - `id` or `slug`.
		 * @return array       Array with term names.
		 */
		public static function get_tags( $tax = 'timeline_tax' ) {
			$terms = array( esc_html__( 'From All', 'hza-timeline' ) );

			foreach ( (array) get_terms( $tax, array( 'hide_empty' => false ) ) as $term ) {
				$terms[ $term->slug ] = $term->name;
			}

			return $terms;
		}

		/**
		 * Add shortcode js/css into the queue.
		 *
		 */
		public static function init_shortcode_assets() {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_register_script(
				'hz-timeline-js',
				hz_forum_plugin_url( "/js/hz-timeline{$suffix}.js" ),
				array( 'jquery' ),
				HZ_FORUM_VERSION,
				true
			);

			wp_enqueue_style(
				'font-awesome',
				hz_forum_plugin_url( '/css/font-awesome.min.css' ),
				array(),
				'4.6.3'
			);

			wp_enqueue_style(
				'hz-timeline-css',
				hz_forum_plugin_url( '/css/hz-forum.css' ),
				array(),
				HZ_FORUM_VERSION
			);
		}

		/**
		 * Setup date and content filters.
		 *
		 */
		public static function init_filters() {
			add_filter( 'hz_timeline_format_content', array( __CLASS__, 'timeline_content_filter' ), 11, 1 );
		}

		/**
		 * Default timeline content filter.
		 *
		 * @param  string $content The content that should filter be applied to.
		 * @return string
		 */
		public static function hz_forum_content_filter( $content = '' ) {
			return apply_filters( 'the_content', $content );
		}

		public static function hz_forum_page_template( $page_template )
		{
			if ( is_page('community') ) {
				$page_template = hz_forum_plugin_path('/views/template-forum.php');
			}
			return $page_template;
		}

		public static function hz_topics_archive_page_template( $template )
		{
			if ( is_single() || is_archive() ) {
				$template_basename = basename( $template );
				$post_type = get_post_type();
				$taxonomy = get_query_var( 'taxonomy' );
				$slug = is_archive() ? 'archive' : 'single';
				if($post_type == 'topic' || $taxonomy == "topic_categories"){
					if($slug == "archive"){
						$template = hz_forum_plugin_path('/views/template-topics-archive.php');
					}else if($slug == "single"){
						$template = hz_forum_plugin_path('/views/template-single-topic.php');
					}
				}
			}
			return $template;
		}

		public static function hz_topics_single_page_template( $template )
		{
			if ( is_single()) {
				$template_basename = basename( $template );
				$post_type = get_post_type();
				if($post_type == 'topic'){
				$template = hz_forum_plugin_path('/views/template-single-topic.php');
				}
			}
			return $template;
		}

		public function hz_post_new_reply(){
			//parse_str($_POST['data'],$post);
			global $wpdb;
			$topic_id = sanitize_text_field($_POST['topic_id']);
			$parent_reply_id = sanitize_text_field($_POST['parent_reply_id']);
			$reply = wp_kses_post($_POST['reply']);
			$reply = str_replace('<br data-mce-bogus="1">','',$reply);
			// Create post object
			$my_reply = array(
			  'post_title'    => $topic_id."_reply_".time(),
			  'post_content'  => $reply,
			  'post_status'   => 'publish',
			  'post_author'  => get_current_user_id(),
			  'post_type'	=> 'reply'
			);
			 
			// Insert the post into the database
			$reply_id = wp_insert_post( $my_reply );
			if(!is_wp_error($reply_id)){
			  //the post is valid
			  //echo $reply_id;
			  update_post_meta($reply_id,'topic_id',$topic_id);
			  update_post_meta($reply_id,'parent_reply_id',$parent_reply_id);
			  echo 'success';
			  $_SESSION['notif'] = array('type'=>'success','message'=>'Your answer submitted successfully.');
			}else{
			  //there was an error in the post insertion, 
			  echo $reply_id->get_error_message();
			}
			die();
		}
		
		public function hz_post_new_topic(){
			global $wpdb,$current_user;
			$title = wp_strip_all_tags( $_POST['title'] );
			$category_id = sanitize_text_field($_POST['cat_id']);
			$content = wp_kses_post($_POST['content']);
			$content = str_replace('<br data-mce-bogus="1">','',$content);
			$tags = sanitize_text_field($_POST['tags']);
			$source = sanitize_text_field($_POST['source']);
			$school_id = sanitize_text_field($_POST['sc_id']);
			global $wpdb,$current_user;
			get_currentuserinfo();
			//d($_POST);
			// Create post object
			$my_topic = array(
			  'post_title'    => $title,
			  'post_content'  => $content,
			  'post_status'   => 'publish',
			  'post_author'  => get_current_user_id(),
			  'post_type'	=> 'topic'
			);
			// Insert the post into the database
			$topic_id = wp_insert_post( $my_topic );
			if(!is_wp_error($topic_id)){
			  //the post is valid
			  //echo $reply_id;
			  //update_post_meta($topic_id,'topic_id',$topic_id);
			  //update_post_meta($topic_id,'parent_reply_id',$parent_reply_id);
			  wp_set_post_terms( $topic_id, $category_id, "topic_categories");
			  wp_set_post_terms( $topic_id, $tags, "topic_tags");
			  if(!empty($source) && $source=='question'){
				$post = get_post($school_id);
				$permalink = get_permalink($school_id);
				$user_email = $current_user->user_email;
				$notification_post = get_posts(array(
												'name' => 'admin-question-submitted',
												'post_type' => 'notifications',
												'post_status' => 'publish',
												'numberposts' => 1
													)
											   );
				$subject = get_field('subject',$notification_post[0]->ID);
				$to = get_field('to',$notification_post[0]->ID);
				$from= get_field('from',$notification_post[0]->ID);
				$from_email= get_field('from_email',$notification_post[0]->ID);
				$from_smtp = get_option( 'wp_email_smtp_option_name' );				
				
				$school_post = get_post($school_id);
				$author_obj = get_user_by('id', $school_post->post_author);
				$school_admin_nicename = $author_obj->display_name;
				
				$owner = get_field('owner_info',$school_id);
				if(isset($owner) && !empty($owner)){
						$school_user_email = $owner['user_email'];						
				}else{
					$school_user_email = get_field('email',$school_id);
					
				}
				
				$user_name = $current_user->display_name;
			
				$shortcodes_tags = array('#logged_user_name#','#question-asked#','#school_admin_email#','#school_admin_name#','#school_name#','#school_link#','#logged_user_email#','#current_date#');
				$shortcodes_data = array($user_name,$content,$school_user_email,$school_admin_nicename,$post->post_title,$permalink,$user_email,date("Y-m-d"));
				$subject = str_ireplace($shortcodes_tags,$shortcodes_data,$subject);
				$message = str_ireplace($shortcodes_tags,$shortcodes_data,$notification_post[0]->post_content);
				
				if($from && $from_email && !empty($from) && !empty($from_email)){
					$headers[] = 'From: '.$from.' <'.$admin_email.'>';
				}else{
					$headers[] = 'From: '.$from_smtp['from_name'].' <'.$from_smtp['from_email'].'>';
				}
				if(isset($to) && !empty($to)){
					$email = $to;
				}else{
					$owner = get_field('owner_info',$post->ID);
					if(isset($owner) && !empty($owner)){
						$email = $owner;
					}else{
						//$author_obj = get_user_by('id', $post->post_author);
						//$email = $author_obj->user_email;
						$school_email = get_field('email',$post->ID);
						$email = $school_email;
					}	
				}
				// send mail
				global $msg;
				$msg = $message;
				ob_start(); // start capturing output		
				include(get_stylesheet_directory().'/emails/email-content.php'); // execute the file
				$body = ob_get_contents(); // get the contents from the buffer
				ob_end_clean();
				wp_mail($email,$subject,$body,$headers);	  
				$_SESSION['notif'] = array('type'=>'success','message'=>'Your question submitted successfully.');
			  }
			  echo 'success';
			}else{
			  //there was an error in the post insertion, 
			  echo $topic_id->get_error_message();
			}
			die();
		}
		
		
		/**
		 * Plugin uninstall handler.
		 *
		 * @return bool
		 */
		public static function uninstall() {

			if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
				exit();
			}

			return true;
		}
	}
	
	// Add the custom columns to the topic post type:
	add_filter( 'manage_topic_posts_columns', 'set_custom_edit_topic_columns' );
	function set_custom_edit_topic_columns($columns) {
		$defaults = $columns;
		unset($columns);
		$columns = array();
		$n=1;
		foreach($defaults as $key=>$value) {		
			if($n==3){
				$columns['replies'] = __( 'Replies', '' );
				$columns[$key]=$value;
			}else{
				$columns[$key]=$value;
			}
			
    	$n++;
		}
		return $columns;
	}
	// Add the custom columns to the reply post type:
	add_filter( 'manage_reply_posts_columns', 'set_custom_edit_reply_columns' );
	function set_custom_edit_reply_columns($columns) {
		$defaults = $columns;
		unset($columns);
		$columns = array();
		$n=1;
		foreach($defaults as $key=>$value) {		
			if($n==3){
			 	$columns['topic'] = __( 'Topic', '' );
				$columns[$key]=$value;
			}else{
				$columns[$key]=$value;
			}
			
    	$n++;
		}
		return $columns;
	}
	
	// Add the data to the custom columns for the TOPIC post type:
	add_action( 'manage_topic_posts_custom_column' , 'custom_topic_column', 10, 2 );
	function custom_topic_column( $column, $post_id ) {
		switch ( $column ) {	
			case 'replies' :
				$n = 1;
				$replies_args = array(
					'post_type'     =>  'reply',
					'post_status'   =>  'publish',
					'posts_per_page'=>  -1,
					'order'         =>  'ASC',
					'meta_query'    =>  array(
						array(
							'key'   =>  'topic_id',
							'value' =>  $post_id,
						),
					),
				);
				$replies = get_posts($replies_args);
				$replies_count =  count($replies);
				if ( $replies_count !=0 ){
					$topic = get_post( $post_id );
					$topic_name = $topic->post_name;					
					echo '<a href="'.site_url().'/wp-admin/edit.php?post_type=reply&topic_filter='.$post_id.'" target="_blank">'.$replies_count.'</a>';				
					//echo '<a href="'.site_url('topics').'/'.$topic_name.'/" target="_blank">'.$replies_count.'</a>';
				}else
					_e( '' );
				break;			
	
		}
	}
	// Add the data to the custom columns for the REPLY post type:
	add_action( 'manage_reply_posts_custom_column' , 'custom_reply_column', 10, 2 );
	function custom_reply_column( $column, $post_id ) {
		$get_topic = get_post_meta( $post_id, 'topic_id', true );		
		switch ( $column ) {	
			case 'topic' :
				$topic = get_post( $get_topic );
				$topic_title = $topic->post_title;
				echo '<a href="'.site_url().'/wp-admin/post.php?post='.$get_topic.'&action=edit'.'" target="_blank">'.$topic_title.'</a>';				
				break;			
	
		}
	}
	
	// test query
	function user_id_filter( $query ) {
		
    if ( ! is_admin() && !$query->is_main_query() )
         return;

    if( isset($_GET['topic_filter']) && !empty($_GET['topic_filter'])) {
       $topic = $_GET['topic_filter'];		   
       $query->set('meta_key', 'topic_id');
	   $query->set('meta_compare', '=');
	   $query->set('meta_value', $topic);
    }
    //Debug if necessary
   // d($query);
	}
	add_action( 'pre_get_posts', 'user_id_filter', 500000000 );
}
