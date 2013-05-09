<?php/** * Theme Activation *//** * Front Page Setup * * Find or add page using specific template then set as Front Page or Posts Page. * Run once on for Front Page (Homepage) then again for Posts Page (Blog). * This should be run only on first activation, so user's changes are kept. * * $page_type is page_on_front or page_for_posts */function ctc_set_front_page( $option, $template, $post_name, $title, $content = '' ) {	// Validate option	if ( ! in_array( $option, array( 'page_on_front', 'page_for_posts' ) ) ) {		return;	}	// Use page templates directory	$template = CTC_PAGE_TPL_DIR . '/' . $template;	// Does page using template already exist?	$pages = get_pages( array( // only gets published pages		'meta_key' => '_wp_page_template',		'meta_value' => $template,		'sort_column' => 'ID',		'sort_order' => 'DESC', // if more than one, gets the newest		'number' => 1	) );	$page_id = ! empty( $pages[0]->ID ) ? $pages[0]->ID : false; // get ID so can set the page	// No page using template found so add one with sample content	if ( ! $page_id ) {		// Insert the post into the database		$current_user = wp_get_current_user();		$page_id = wp_insert_post( array(			'post_name'			=> $post_name, // if already exists, WordPress will append number			'post_type'			=> 'page',			'post_status'		=> 'publish',			'post_author'		=> $current_user->ID,			'comment_status'	=> 'closed',			'ping_status'		=> 'closed',			'post_title'		=> $title,			'post_content'		=> $content		) );		// Set page template		if ( $page_id ) {			update_post_meta( $page_id, '_wp_page_template', $template );		}			}	// Set options	if ( $page_id ) { // page found or added successfully		update_option( 'show_on_front', 'page' );		update_option( $option, $page_id );	// page_on_front or page_for_posts	} else { // if no page found or made, revert to show posts on homepage		update_option( 'show_on_front', 'posts' );		update_option( 'page_on_front', '' );			update_option( 'page_for_posts', '' );		}	}/** * Flush Rewrite Rules * * Flush rewrite rules after other things are done, such as registering custom post types, taxonomies, etc. */// Run this on activation in themefunction ctc_activate_flush_rewrite_rules() {	add_action( 'init', 'ctc_flush_rewrite_rules', 11 ); // after standard init at 10}// Do the actual flush called for abovefunction ctc_flush_rewrite_rules() {	flush_rewrite_rules();}