<?php
/**
 * Temporary Updater Section for Ultimate Contact Button
 * You can delete this file anytime to remove the Update button.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add an Update button to the settings page header or notice area
 */
add_action( 'admin_notices', 'ucb_render_update_button' );
add_action( 'admin_init', 'ucb_handle_update' );

function ucb_render_update_button() {
	$screen = get_current_screen();
	if ( 'toplevel_page_ultimate-contact-button' !== $screen->id ) {
		return;
	}

	$update_url = wp_nonce_url( admin_url( 'admin.php?page=ultimate-contact-button&ucb_action=update' ), 'ucb_update_nonce' );
	?>
	<div class="notice notice-warning is-dismissible" style="border-left-color: #1e73be; display: flex; align-items: center; justify-content: space-between; padding: 10px 20px;">
		<div style="font-weight: 600;">
			🚀 Ultimate Contact Button Auto-Updater
		</div>
		<div>
			<a href="<?php echo esc_url( $update_url ); ?>" class="button button-primary" style="background: #1e73be; border-color: #1e73be;">
				<span class="dashicons dashicons-cloud-download" style="margin-top: 4px;"></span> 
				AUTO-UPDATE FROM GITHUB NOW
			</a>
		</div>
	</div>
	<?php
}

function ucb_handle_update() {
	if ( ! isset( $_GET['ucb_action'] ) || 'update' !== $_GET['ucb_action'] ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'ucb_update_nonce' ) ) {
		wp_die( 'Unauthorized action.' );
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	
	$github_zip_url = 'https://github.com/tuend-work/ultimate-contact-button/archive/refs/heads/main.zip';
	$temp_file = download_url( $github_zip_url );

	if ( is_wp_error( $temp_file ) ) {
		wp_die( 'Download failed: ' . $temp_file->get_error_message() );
	}

	// Initialize Filesystem
	WP_Filesystem();
	global $wp_filesystem;

	$destination = UCB_PATH;
	$temp_dir = UCB_PATH . 'temp_update/';
	
	// Ensure temp dir exists and is clean
	$wp_filesystem->delete( $temp_dir, true );
	$wp_filesystem->mkdir( $temp_dir );

	// Unzip to temp folder
	$unzipped = unzip_file( $temp_file, $temp_dir );
	unlink( $temp_file );

	if ( is_wp_error( $unzipped ) ) {
		$wp_filesystem->delete( $temp_dir, true );
		wp_die( 'Unzip failed: ' . $unzipped->get_error_message() );
	}

	// GitHub zips have a subfolder like 'ultimate-contact-button-main'
	$contents = $wp_filesystem->dirlist( $temp_dir );
	if ( ! empty( $contents ) ) {
		$inner_dir_name = key( $contents );
		$inner_dir_path = $temp_dir . $inner_dir_name . '/';
		
		// Move contents from inner dir to our plugin root
		copy_dir( $inner_dir_path, $destination );
	}

	// Cleanup
	$wp_filesystem->delete( $temp_dir, true );

	// Redirect back with success flag
	wp_safe_redirect( admin_url( 'admin.php?page=ultimate-contact-button&ucb_updated=1' ) );
	exit;
}

// Success Notice
add_action( 'admin_notices', 'ucb_update_success_notice' );
function ucb_update_success_notice() {
	if ( isset( $_GET['ucb_updated'] ) ) {
		echo '<div class="notice notice-success is-dismissible"><p>Plugin updated successfully from GitHub!</p></div>';
	}
}
