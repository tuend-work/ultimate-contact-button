<?php
/**
 * Asset management class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UCB_Assets {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
	}

	public function enqueue_admin_assets( $hook ) {
		// Only load on our settings page
		if ( 'toplevel_page_ultimate-contact-button' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'ucb-admin-css', UCB_URL . 'assets/css/admin.css', array(), UCB_VERSION );
		wp_enqueue_script( 'ucb-admin-js', UCB_URL . 'assets/js/admin.js', array( 'jquery', 'jquery-ui-sortable' ), UCB_VERSION, true );
		
		// WordPress Media Uploader
		wp_enqueue_media();

		// WordPress Color Picker for styling options
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
	}

	public function enqueue_frontend_assets() {
		wp_enqueue_style( 'ucb-frontend-css', UCB_URL . 'assets/css/frontend.css', array(), UCB_VERSION );
		wp_enqueue_script( 'ucb-frontend-js', UCB_URL . 'assets/js/frontend.js', array( 'jquery' ), UCB_VERSION, true );
	}
}

new UCB_Assets();
