<?php
/**
 * Font Functions
 *
 * These functions help setup and integrate custom fonts such as Google Fonts.
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

/*******************************************
 * FONT STACKS
 *******************************************/

/**
 * Define font stacks
 *
 * Default font stacks for each type of font
 *
 * @since 0.9
 * @return array Default font stacks
 */
function ctfw_default_font_stacks() {

	// These fonts in the given order when available will be used for each type if for whatever reason the browser cannot load the custom font
	$default_font_stacks = array(
		'serif'			=> "Georgia, 'Bitstream Vera Serif', 'Times New Roman', Times, serif",
		'sans-serif'	=> "Arial, Helvetica, sans-serif",
		'display'		=> "Arial, Helvetica, sans-serif",
		'handwriting'	=> "Georgia, 'Bitstream Vera Serif', 'Times New Roman', Times, cursive"
	);

	// Enable filtering to change default font stacks
	$default_font_stacks = apply_filters( 'ctfw_default_font_stacks', $default_font_stacks );

	return $default_font_stacks;

}

/**
 * Font stack based on font's type
 *
 * Build a font stack based on font and its type -- use in CSS
 *
 * @since 0.9
 * @param string $font Font in $available_fonts
 * @param array $available_fonts Fonts available for use
 * @return array Font stack for font
 */
function ctfw_font_stack( $font, $available_fonts ) {

	// Get the default font stack for each type
	$default_font_stacks = ctfw_default_font_stacks();

	// Build font stack with custom font as primary
	if ( ! empty( $available_fonts[$font] ) && ! empty( $default_font_stacks[$available_fonts[$font]['type']] ) ) {
		$default_font_stack = $default_font_stacks[$available_fonts[$font]['type']];
	} else { // if invalid, type use first in list (should be serif)
		$default_font_stack = current( $default_font_stacks );
	}
	$font_stack = "'" . $font . "', " . $default_font_stack;

	// Filterable
	$font_stack = apply_filters( 'ctfw_font_stack', $font_stack, $font, $available_fonts );
	
	return $font_stack;

}

/*******************************************
 * GOOGLE FONTS
 *******************************************/

/**
 * Google Fonts stylesheet URL for enqueuing
 *
 * @since 0.9
 * @param array $fonts Fonts to load from Google Fonts
 * @param array $available_fonts Fonts available for use
 * @param string $font_subsets Optional character sets to load
 * @return string Google Fonts stylesheet URL
 */
function ctfw_google_fonts_style_url( $fonts, $available_fonts, $font_subsets = false ) {
	
	$url = '';
	
	// No duplicates
	$fonts = array_unique( $fonts );

	// Build array of fonts
	$font_array = array();
	foreach ( $fonts as $font ) {
		if ( ! empty( $available_fonts[$font] ) ) { // font is valid
			$font_array[] = urlencode( $font ) . ( ! empty( $available_fonts[$font]['sizes'] ) ? ':' . $available_fonts[$font]['sizes'] : '' );
		}	
	}
	
	// Have font(s)...
	if ( ! empty( $font_array ) ) {
	
		// Build list from array
		$font_list = implode( '|', $font_array );
		
		// Subset passed in? Format it
		$subset_attr = '';
		if ( ! empty( $font_subsets ) ) {
			$font_subsets = str_replace( ' ', '', $font_subsets ); // in case spaces between commas
			if ( ! empty( $font_subsets ) && 'latin' != $font_subsets ) {
				$subset_attr = '&subset=' . $font_subsets;
			}
		}

		// Build URL
		$url = ctfw_current_protocol() . '://fonts.googleapis.com/css?family=' . $font_list . $subset_attr;
		
	}
	
	// Return filtered
	return apply_filters( 'ctfw_google_fonts_style_url', $url, $fonts, $available_fonts, $font_subsets );
	
}
