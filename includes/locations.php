<?php
/**
 * Location Functions
 *
 * @package    Church_Theme_Framework
 * @subpackage Functions
 * @copyright  Copyright (c) 2013, churchthemes.com
 * @link       https://github.com/churchthemes/church-theme-framework
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @since      0.9
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;

/**********************************
 * DATA
 **********************************/

/**
 * Get location data
 *
 * @since 0.9
 * @param int $post_id Post ID to get data for; null for current post
 * @return array Location data
 */
function ctfw_location_data( $post_id = null ) {

	// Get meta values
	$data = ctfw_get_meta_data( array(
		'address',
		'show_directions_link',
		'phone',
		'times',
		'map_lat',
		'map_lng',
		'map_type',
		'map_zoom'
	), $post_id );

	// Add directions URL (empty if show_directions_link not set)
	$data['directions_url'] = $data['show_directions_link'] ? ctfw_directions_url( $data['address'] ) : '';

	// Return filtered
	return apply_filters( 'ctfw_location_data', $data, $post_id );

}
