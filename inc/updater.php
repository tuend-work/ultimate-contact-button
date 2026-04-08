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

function ucb_render_update_button() {
	$screen = get_current_screen();
	
	// Only show on our plugin settings page
	if ( 'toplevel_page_ultimate-contact-button' !== $screen->id ) {
		return;
	}

	$github_url = 'https://github.com/tuend-work/ultimate-contact-button/archive/refs/heads/main.zip';
	?>
	<div class="notice notice-warning is-dismissible" style="border-left-color: #1e73be; display: flex; align-items: center; justify-content: space-between; padding: 10px 20px;">
		<div style="font-weight: 600;">
			🚀 Ultimate Contact Button Update Tool
		</div>
		<div>
			<a href="<?php echo esc_url( $github_url ); ?>" class="button button-primary" style="background: #1e73be; border-color: #1e73be;">
				<span class="dashicons dashicons-cloud-download" style="margin-top: 4px;"></span> 
				UPDATE NEW VERSION FROM GITHUB
			</a>
		</div>
	</div>
	<?php
}
