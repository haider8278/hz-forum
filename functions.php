<?php
/**
 * Utilities
 *
 * @package    Hz_Timeline
 * @subpackage Hz_Timeline_Admin
 * @author     HZA
 */

defined( 'ABSPATH' ) or die( 'No direct script allowed' );

/**
 * Useful utilities
 */

if ( ! function_exists( 'hz_forum_plugin_url' ) ) {
	/**
	 * Get plugin file/folder url
	 *
	 * @param  string $path Relative path.
	 * @return string
	 */
	function hz_forum_plugin_url( $path ) {
		return untrailingslashit( plugin_dir_url( __FILE__ ) ) . $path;
	}
}

if ( ! function_exists( 'hz_forum_plugin_path' ) ) {
	/**
	 * Get plugin file/folder absolute path.
	 *
	 * @param  string $path Relative path.
	 * @return string
	 */
	function hz_forum_plugin_path( $path ) {
		return trailingslashit( plugin_dir_path( __FILE__ ) ) . $path;
	}
}

if(! function_exists('hz_moveElement')){
	/**
	 * Get array and index to change its position
	 *
	 * @param  array.
	 * $param  old index.
	 * $param  new index.
	 * @return array
	 */
	function hz_moveElement(&$array, $a, $b) {
		$p1 = array_splice($array, $a, 1);
		$p2 = array_splice($array, 0, $b);
		$array = array_merge($p2,$p1,$array);
	}

}

// retrieves the attachment ID from the file URL
function hz_get_image_id_from_url($image_url) {
	global $wpdb;
	$pattern = '/\-*(\d+)x(\d+)\.(.*)$/';
	$replacement = '.$3';
	$image_url =  preg_replace($pattern, $replacement, $image_url);
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE 	guid='%s';", $image_url ));
	return $attachment[0];
}

function hz_page_exists($slug) {
    global $wpdb;
    $post_slug = wp_unslash( sanitize_post_field( 'post_name', $slug, 0, 'db' ) );
    $query = "SELECT ID FROM $wpdb->posts WHERE 1=1";
    $args = array();
    if ( !empty ( $slug ) ) {
        $query .= ' AND post_name = %s';
        $args[] = $post_slug;
    }
    if ( !empty ( $args ) )
        return (int) $wpdb->get_var( $wpdb->prepare($query, $args) );
    return 0;
}
function days_from_now($date){
	$now = time(); // or your date as well
	$your_date = strtotime($date);
	$datediff = $now - $your_date;
	return round($datediff / (60 * 60 * 24));
}
