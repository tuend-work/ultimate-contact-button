<?php
/**
 * Settings management class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UCB_Settings {

	/**
	 * Options name
	 */
	const OPTION_NAME = 'ucb_settings';

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	public function add_menu() {
		// Add Parent Menu "Ultimate WP" if it does not exist
		add_menu_page(
			'Ultimate WP Dashboard',
			'Ultimate WP',
			'manage_options',
			'ultimate-wp',
			'ultimate_wp_render_dashboard', // Call global function
			'dashicons-superhero',
			2.1
		);

		// Add Submenu for Dashboard
		add_submenu_page(
			'ultimate-wp',
			'Ultimate WP Dashboard',
			'Dashboard',
			'manage_options',
			'ultimate-wp',
			'ultimate_wp_render_dashboard'
		);

		// Add Submenu for Contact Button settings
		add_submenu_page(
			'ultimate-wp',
			__( 'Ultimate Contact Button Settings', 'ultimate-contact-button' ),
			__( 'Contact Button', 'ultimate-contact-button' ),
			'manage_options',
			'ultimate-contact-button',
			array( $this, 'render_settings_page' )
		);
	}

	public function register_settings() {
		register_setting( self::OPTION_NAME, self::OPTION_NAME, array( 'sanitize_callback' => array( $this, 'sanitize' ) ) );

		// Tab 1: Main Contact Button (FAB)
		add_settings_section( 'ucb_main_section', __( 'Main Contact Button Configuration', 'ultimate-contact-button' ), null, 'ultimate-contact-button' );

		add_settings_field(
			'main_enabled',
			__( 'Enable Main Button', 'ultimate-contact-button' ),
			array( $this, 'render_checkbox_field' ),
			'ultimate-contact-button',
			'ucb_main_section',
			array( 'label_for' => 'main_enabled' )
		);

		add_settings_field(
			'main_mobile_enabled',
			__( 'Enable on Mobile', 'ultimate-contact-button' ),
			array( $this, 'render_checkbox_field' ),
			'ultimate-contact-button',
			'ucb_main_section',
			array( 'label_for' => 'main_mobile_enabled' )
		);

		add_settings_field(
			'position_side',
			__( 'Button Side', 'ultimate-contact-button' ),
			array( $this, 'render_select_field' ),
			'ultimate-contact-button',
			'ucb_main_section',
			array( 
				'label_for' => 'position_side',
				'options' => array(
					'right' => 'Right',
					'left' => 'Left'
				)
			)
		);

		add_settings_field(
			'bottom_distance',
			__( 'Bottom Distance (px)', 'ultimate-contact-button' ),
			array( $this, 'render_number_field' ),
			'ultimate-contact-button',
			'ucb_main_section',
			array( 'label_for' => 'bottom_distance', 'default' => 30 )
		);

		add_settings_field(
			'side_distance',
			__( 'Side Distance (px)', 'ultimate-contact-button' ),
			array( $this, 'render_number_field' ),
			'ultimate-contact-button',
			'ucb_main_section',
			array( 'label_for' => 'side_distance', 'default' => 30 )
		);

		add_settings_field(
			'main_button_color',
			__( 'Main Button Color', 'ultimate-contact-button' ),
			array( $this, 'render_color_field' ),
			'ultimate-contact-button',
			'ucb_main_section',
			array( 'label_for' => 'main_button_color', 'default' => '#1e73be' )
		);

		add_settings_field(
			'main_display_mode',
			__( 'Display Mode', 'ultimate-contact-button' ),
			array( $this, 'render_select_field' ),
			'ultimate-contact-button',
			'ucb_main_section',
			array( 
				'label_for' => 'main_display_mode',
				'options' => array(
					'always' => 'Always Show',
					'click' => 'When Clicking/Hover'
				)
			)
		);

		// Tab 2: Bottom Mobile Menu
		add_settings_section( 'ucb_bottom_section', __( 'Bottom Mobile Menu Configuration', 'ultimate-contact-button' ), null, 'ultimate-contact-button-bottom' );
		
		add_settings_field(
			'bottom_menu_enabled',
			__( 'Enable Bottom Menu', 'ultimate-contact-button' ),
			array( $this, 'render_checkbox_field' ),
			'ultimate-contact-button-bottom',
			'ucb_bottom_section',
			array( 'label_for' => 'bottom_menu_enabled' )
		);
	}

	public function render_select_field( $args ) {
		$options = get_option( self::OPTION_NAME );
		$id      = $args['label_for'];
		$current = isset( $options[ $id ] ) ? $options[ $id ] : '';
		?>
		<select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( self::OPTION_NAME . '[' . $id . ']' ); ?>">
			<?php foreach ( $args['options'] as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $current, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	public function render_number_field( $args ) {
		$options = get_option( self::OPTION_NAME );
		$id      = $args['label_for'];
		$value   = isset( $options[ $id ] ) ? $options[ $id ] : $args['default'];
		?>
		<input type="number" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( self::OPTION_NAME . '[' . $id . ']' ); ?>" value="<?php echo esc_attr( $value ); ?>" class="small-text" />
		<?php
	}

	public function render_color_field( $args ) {
		$options = get_option( self::OPTION_NAME );
		$id      = $args['label_for'];
		$value   = isset( $options[ $id ] ) ? $options[ $id ] : $args['default'];
		?>
		<input type="text" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( self::OPTION_NAME . '[' . $id . ']' ); ?>" value="<?php echo esc_attr( $value ); ?>" class="ucb-color-picker" />
		<?php
	}

	public function render_checkbox_field( $args ) {
		$options = get_option( self::OPTION_NAME );
		$id      = $args['label_for'];
		$value   = isset( $options[ $id ] ) ? $options[ $id ] : 0;
		?>
		<input type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( self::OPTION_NAME . '[' . $id . ']' ); ?>" value="1" <?php checked( 1, $value ); ?> />
		<?php
	}

	public function render_settings_page() {
		?>
		<div class="wrap ucb-admin-wrap">
			<h1><?php esc_html_e( 'Ultimate Contact Button Settings', 'ultimate-contact-button' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( self::OPTION_NAME );
				?>
				
				<div class="ucb-tabs">
					<nav class="ucb-tab-nav">
						<a href="#tab-main" class="active"><?php esc_html_e( 'Main Contact Button', 'ultimate-contact-button' ); ?></a>
						<a href="#tab-bottom"><?php esc_html_e( 'Bottom Mobile Menu', 'ultimate-contact-button' ); ?></a>
					</nav>
					
					<div class="ucb-tab-content active" id="tab-main">
						<?php do_settings_sections( 'ultimate-contact-button' ); ?>
						<hr/>
						<h3><?php esc_html_e( 'Main Button Items (Always visible or FAB)', 'ultimate-contact-button' ); ?></h3>
						<?php $this->render_main_manager(); ?>
					</div>
					
					<div class="ucb-tab-content" id="tab-bottom">
						<?php 
						do_settings_sections( 'ultimate-contact-button-bottom' );
						?>
						<hr/>
						<h3><?php esc_html_e( 'Bottom Menu Items', 'ultimate-contact-button' ); ?></h3>
						<?php $this->render_bottom_menu_manager(); ?>
					</div>
				</div>

				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	private function render_main_manager() {
		$options = get_option( self::OPTION_NAME );
		// Fallback to desktop_buttons for migration
		$buttons = isset( $options['main_buttons'] ) ? $options['main_buttons'] : (isset($options['desktop_buttons']) ? $options['desktop_buttons'] : array());
		?>
		<div id="ucb-desktop-repeater">
			<ul class="ucb-sortable-list" id="ucb-desktop-list">
				<?php if ( ! empty( $buttons ) ) : ?>
					<?php foreach ( $buttons as $index => $button ) : ?>
						<li class="ucb-list-item">
							<span class="dashicons dashicons-move handle"></span>
							<div class="ucb-item-fields">
								<div class="ucb-field-row">
									<select name="<?php echo esc_attr( self::OPTION_NAME . "[main_buttons][$index][type]" ); ?>">
										<option value="phone" <?php selected( $button['type'], 'phone' ); ?>>Phone</option>
										<option value="zalo" <?php selected( $button['type'], 'zalo' ); ?>>Zalo</option>
										<option value="messenger" <?php selected( $button['type'], 'messenger' ); ?>>Messenger</option>
										<option value="whatsapp" <?php selected( $button['type'], 'whatsapp' ); ?>>WhatsApp</option>
										<option value="telegram" <?php selected( $button['type'], 'telegram' ); ?>>Telegram</option>
										<option value="mail" <?php selected( $button['type'], 'mail' ); ?>>Email</option>
										<option value="custom" <?php selected( $button['type'], 'custom' ); ?>>Custom</option>
									</select>
									<input type="text" name="<?php echo esc_attr( self::OPTION_NAME . "[main_buttons][$index][label]" ); ?>" value="<?php echo esc_attr( $button['label'] ); ?>" placeholder="Label" />
									<input type="text" name="<?php echo esc_attr( self::OPTION_NAME . "[main_buttons][$index][link]" ); ?>" value="<?php echo esc_attr( $button['link'] ); ?>" placeholder="Link/ID" />
								</div>
								<div class="ucb-field-row ucb-upload-row">
									<input type="text" class="ucb-img-url" name="<?php echo esc_attr( self::OPTION_NAME . "[main_buttons][$index][icon_url]" ); ?>" value="<?php echo isset( $button['icon_url'] ) ? esc_attr( $button['icon_url'] ) : ''; ?>" placeholder="Custom SVG URL" />
									<button type="button" class="button ucb-upload-btn"><?php esc_html_e( 'Upload SVG', 'ultimate-contact-button' ); ?></button>
								</div>
								<div class="ucb-field-row ucb-svg-row">
									<textarea name="<?php echo esc_attr( self::OPTION_NAME . "[main_buttons][$index][icon_svg]" ); ?>" placeholder="Or Paste Custom SVG Code here"><?php echo isset( $button['icon_svg'] ) ? esc_textarea( $button['icon_svg'] ) : ''; ?></textarea>
								</div>
							</div>
							<button type="button" class="ucb-remove-item button-link-delete"><?php esc_html_e( 'Remove', 'ultimate-contact-button' ); ?></button>
						</li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
			<button type="button" class="button tagadd" id="ucb-add-desktop-item"><?php esc_html_e( 'Add Button', 'ultimate-contact-button' ); ?></button>
		</div>
		<?php
	}

	private function render_bottom_menu_manager() {
		$options = get_option( self::OPTION_NAME );
		// Fallback to mobile_buttons for migration
		$buttons = isset( $options['bottom_menu_buttons'] ) ? $options['bottom_menu_buttons'] : (isset($options['mobile_buttons']) ? $options['mobile_buttons'] : array());
		?>
		<div id="ucb-mobile-repeater">
			<ul class="ucb-sortable-list" id="ucb-mobile-list">
				<?php if ( ! empty( $buttons ) ) : ?>
					<?php foreach ( $buttons as $index => $button ) : ?>
						<li class="ucb-list-item">
							<span class="dashicons dashicons-move handle"></span>
							<div class="ucb-item-fields">
								<div class="ucb-field-row">
									<select name="<?php echo esc_attr( self::OPTION_NAME . "[bottom_menu_buttons][$index][type]" ); ?>">
										<option value="phone" <?php selected( $button['type'], 'phone' ); ?>>Phone</option>
										<option value="zalo" <?php selected( $button['type'], 'zalo' ); ?>>Zalo</option>
										<option value="messenger" <?php selected( $button['type'], 'messenger' ); ?>>Messenger</option>
										<option value="whatsapp" <?php selected( $button['type'], 'whatsapp' ); ?>>WhatsApp</option>
										<option value="telegram" <?php selected( $button['type'], 'telegram' ); ?>>Telegram</option>
										<option value="mail" <?php selected( $button['type'], 'mail' ); ?>>Email</option>
										<option value="custom" <?php selected( $button['type'], 'custom' ); ?>>Custom</option>
									</select>
									<input type="text" name="<?php echo esc_attr( self::OPTION_NAME . "[bottom_menu_buttons][$index][label]" ); ?>" value="<?php echo esc_attr( $button['label'] ); ?>" placeholder="Label" />
									<input type="text" name="<?php echo esc_attr( self::OPTION_NAME . "[bottom_menu_buttons][$index][link]" ); ?>" value="<?php echo esc_attr( $button['link'] ); ?>" placeholder="Link/ID" />
								</div>
								<div class="ucb-field-row ucb-upload-row">
									<input type="text" class="ucb-img-url" name="<?php echo esc_attr( self::OPTION_NAME . "[bottom_menu_buttons][$index][icon_url]" ); ?>" value="<?php echo isset( $button['icon_url'] ) ? esc_attr( $button['icon_url'] ) : ''; ?>" placeholder="Custom SVG URL" />
									<button type="button" class="button ucb-upload-btn"><?php _e( 'Upload SVG', 'ultimate-contact-button' ); ?></button>
								</div>
								<div class="ucb-field-row ucb-svg-row">
									<textarea name="<?php echo esc_attr( self::OPTION_NAME . "[bottom_menu_buttons][$index][icon_svg]" ); ?>" placeholder="Or Paste Custom SVG Code here"><?php echo isset( $button['icon_svg'] ) ? esc_textarea( $button['icon_svg'] ) : ''; ?></textarea>
								</div>
							</div>
							<button type="button" class="ucb-remove-item button-link-delete"><?php _e( 'Remove', 'ultimate-contact-button' ); ?></button>
						</li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
			<button type="button" class="button tagadd" id="ucb-add-mobile-item"><?php esc_html_e( 'Add Mobile Button', 'ultimate-contact-button' ); ?></button>
		</div>
		<?php
	}

	public function sanitize( $input ) {
		$output = array();
		
		$flags = array( 'main_enabled', 'main_mobile_enabled', 'bottom_menu_enabled' );
		foreach ( $flags as $flag ) {
			if ( isset( $input[ $flag ] ) ) {
				$output[ $flag ] = 1;
			}
		}

		// Whitelist-validate select fields
		$allowed_sides = array( 'left', 'right' );
		if ( isset( $input['position_side'] ) ) {
			$output['position_side'] = in_array( $input['position_side'], $allowed_sides, true ) ? $input['position_side'] : 'right';
		}

		$allowed_modes = array( 'always', 'click' );
		if ( isset( $input['main_display_mode'] ) ) {
			$output['main_display_mode'] = in_array( $input['main_display_mode'], $allowed_modes, true ) ? $input['main_display_mode'] : 'click';
		}

		// Validate color as hex
		if ( isset( $input['main_button_color'] ) ) {
			$color = sanitize_hex_color( $input['main_button_color'] );
			$output['main_button_color'] = $color ? $color : '#1e73be';
		}

		$ints = array( 'bottom_distance', 'side_distance' );
		foreach ( $ints as $int ) {
			if ( isset( $input[ $int ] ) ) {
				$output[ $int ] = absint( $input[ $int ] );
			}
		}

		if ( isset( $input['main_buttons'] ) ) {
			$output['main_buttons'] = ucb_sanitize_array( $input['main_buttons'] );
		}

		if ( isset( $input['bottom_menu_buttons'] ) ) {
			$output['bottom_menu_buttons'] = ucb_sanitize_array( $input['bottom_menu_buttons'] );
		}

		return $output;
	}
}

new UCB_Settings();

/**
 * Global function to render the unified Ultimate WP Ecosystem Dashboard page.
 * Wrapped in function_exists to prevent conflicts if multiple ecosystem plugins define it.
 */
if ( ! function_exists( 'ultimate_wp_render_dashboard' ) ) {
    function ultimate_wp_render_dashboard() {
        global $_wp_admin_css_colors;
        $color_scheme = get_user_option( 'admin_color' );
        if ( empty( $color_scheme ) ) {
            $color_scheme = 'fresh';
        }

        $primary_color = '#6366f1';
        $primary_dark = '#4f46e5';
        $header_bg_start = '#1d2327';
        $header_bg_end = '#2c3338';

        if ( ! empty( $_wp_admin_css_colors ) && isset( $_wp_admin_css_colors[ $color_scheme ] ) ) {
            $colors = $_wp_admin_css_colors[ $color_scheme ]->colors;
            if ( isset( $colors[0] ) ) {
                $header_bg_start = $colors[0];
            }
            if ( isset( $colors[1] ) ) {
                $header_bg_end = $colors[1];
            }
            if ( isset( $colors[2] ) ) {
                $primary_color = $colors[2];
            }
            if ( isset( $colors[3] ) ) {
                $primary_dark = $colors[3];
            } else if ( isset( $colors[2] ) ) {
                $primary_dark = $colors[2];
            }
        }

        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        $ecosystem_plugins = array(
            'ultimate-wp-booster' => array(
                'name'         => 'Ultimate WP Booster',
                'description'  => 'Tối ưu hóa tốc độ tải trang toàn diện, dọn dẹp và tối ưu hóa cơ sở dữ liệu, nén ảnh, gộp và nén CSS/JS, tích hợp Redis Cache.',
                'path'         => 'ultimate-wp-booster/ultimate-wp-booster.php',
                'settings_url' => admin_url( 'options-general.php?page=ultimate-wp-booster' ),
            ),
            'ultimate-wp-flatsome' => array(
                'name'         => 'Ultimate WP Flatsome',
                'description'  => 'Mở rộng khả năng thiết kế của Flatsome. Cho phép sử dụng UX Builder kéo thả layout trực tiếp cho taxonomy và single page của custom post types.',
                'path'         => 'ultimate-wp-flatsome/ultimate-wp-flatsome.php',
                'settings_url' => admin_url( 'admin.php?page=ultimate-wp-flatsome' ),
            ),
            'ultimate-wp-smtp-queue' => array(
                'name'         => 'Ultimate WP SMTP Queue',
                'description'  => 'Cấu hình gửi email qua giao thức SMTP chuyên nghiệp kết hợp hệ thống hàng đợi gửi ngầm chạy nền (Queue) hiệu năng cao, giảm tải máy chủ.',
                'path'         => 'ultimate-wp-smtp-queue/ultimate-wp-smtp-queue.php',
                'settings_url' => admin_url( 'options-general.php?page=ultimate-wp-smtp-queue' ),
            ),
        );
        ?>
        <style>
            :root {
                --uwp-primary: <?php echo esc_attr( $primary_color ); ?>;
                --uwp-primary-dark: <?php echo esc_attr( $primary_dark ); ?>;
                --uwp-success: #10b981;
                --uwp-warning: #f59e0b;
                --uwp-danger: #ef4444;
                --uwp-bg: #f8fafc;
                --uwp-card-bg: #ffffff;
                --uwp-text: #1e293b;
                --uwp-text-muted: #64748b;
                --uwp-border: #e2e8f0;
            }

            .uwp-dashboard-wrap {
                margin: 20px 20px 0 0;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
                color: var(--uwp-text);
            }

            /* Header Section */
            .uwp-header {
                background: linear-gradient(135deg, <?php echo esc_attr( $header_bg_start ); ?>, <?php echo esc_attr( $header_bg_end ); ?>);
                color: #ffffff;
                padding: 40px;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
                margin-bottom: 30px;
                position: relative;
                overflow: hidden;
            }

            .uwp-header::after {
                content: '';
                position: absolute;
                top: -50%;
                right: -20%;
                width: 300px;
                height: 300px;
                background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
                border-radius: 50%;
            }

            .uwp-header h1 {
                margin: 0 0 10px 0;
                font-size: 2.2rem;
                font-weight: 700;
                letter-spacing: -0.5px;
                color: #ffffff;
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .uwp-header h1 span {
                background: linear-gradient(to right, #a5b4fc, #818cf8);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .uwp-header p {
                margin: 0;
                font-size: 1.1rem;
                color: #e2e8f0;
                max-width: 600px;
                line-height: 1.6;
            }

            /* Grid Layout */
            .uwp-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 24px;
                margin-bottom: 40px;
            }

            /* Card Style */
            .uwp-card {
                background: var(--uwp-card-bg);
                border: 1px solid var(--uwp-border);
                border-radius: 12px;
                padding: 30px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                position: relative;
            }

            .uwp-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 12px 20px rgba(0, 0, 0, 0.05);
                border-color: var(--uwp-primary);
            }

            .uwp-card-title {
                font-size: 1.4rem;
                font-weight: 600;
                margin: 0 0 15px 0;
                color: var(--uwp-text);
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .uwp-card-desc {
                font-size: 0.95rem;
                color: var(--uwp-text-muted);
                line-height: 1.6;
                margin-bottom: 25px;
                flex-grow: 1;
            }

            /* Badges */
            .uwp-status {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-size: 0.8rem;
                font-weight: 600;
                padding: 4px 12px;
                border-radius: 9999px;
            }

            .uwp-status-active {
                background-color: #d1fae5;
                color: #065f46;
            }

            .uwp-status-inactive {
                background-color: #fef3c7;
                color: #92400e;
            }

            .uwp-status-notinstalled {
                background-color: #f1f5f9;
                color: #475569;
            }

            .uwp-status-dot {
                width: 6px;
                height: 6px;
                border-radius: 50%;
                background-color: currentColor;
            }

            /* Buttons */
            .uwp-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                font-weight: 500;
                font-size: 0.9rem;
                padding: 10px 20px;
                border-radius: 8px;
                text-decoration: none;
                transition: all 0.2s ease;
                cursor: pointer;
                border: none;
                width: 100%;
                text-align: center;
            }

            .uwp-btn-primary {
                background-color: var(--uwp-primary);
                color: #ffffff;
            }

            .uwp-btn-primary:hover {
                background-color: var(--uwp-primary-dark);
                color: #ffffff;
            }

            .uwp-btn-secondary {
                background-color: #f1f5f9;
                color: #0f172a;
            }

            .uwp-btn-secondary:hover {
                background-color: #e2e8f0;
                color: #0f172a;
            }

            .uwp-btn-disabled {
                background-color: #f8fafc;
                color: #94a3b8;
                cursor: not-allowed;
                border: 1px dashed var(--uwp-border);
            }

            /* Info Box */
            .uwp-info-box {
                background-color: #f8fafc;
                border: 1px solid var(--uwp-border);
                border-radius: 12px;
                padding: 30px;
                margin-top: 40px;
            }

            .uwp-info-box h3 {
                margin-top: 0;
                font-size: 1.2rem;
                font-weight: 600;
            }

            .uwp-info-box p {
                color: var(--uwp-text-muted);
                line-height: 1.6;
                margin-bottom: 0;
            }
        </style>

        <div class="uwp-dashboard-wrap">
            <!-- Header -->
            <div class="uwp-header">
                <h1><span>Ultimate WP</span> Ecosystem</h1>
                <p>Hệ sinh thái các plugin tối ưu hóa và mở rộng tính năng chuyên nghiệp dành cho WordPress và theme Flatsome của bạn.</p>
            </div>

            <!-- Grid Plugins -->
            <div class="uwp-grid">
                <?php
                foreach ( $ecosystem_plugins as $slug => $data ) {
                    $is_installed = file_exists( WP_PLUGIN_DIR . '/' . $data['path'] );
                    $is_active = $is_installed && is_plugin_active( $data['path'] );

                    if ( $slug === 'ultimate-wp-flatsome' ) {
                        $settings_url = admin_url( 'admin.php?page=ultimate-wp-flatsome' );
                    } else if ( $slug === 'ultimate-wp-booster' && $is_active ) {
                        // Check if booster is updated to submenus
                        $settings_url = admin_url( 'options-general.php?page=ultimate-wp-booster' );
                    } else {
                        $settings_url = $data['settings_url'];
                    }
                    ?>
                    <div class="uwp-card">
                        <div>
                            <div class="uwp-card-title">
                                <?php echo esc_html( $data['name'] ); ?>
                                <?php if ( $is_active ) : ?>
                                    <span class="uwp-status uwp-status-active">
                                        <span class="uwp-status-dot"></span> Đang hoạt động
                                    </span>
                                <?php elseif ( $is_installed ) : ?>
                                    <span class="uwp-status uwp-status-inactive">
                                        <span class="uwp-status-dot"></span> Chưa kích hoạt
                                    </span>
                                <?php else : ?>
                                    <span class="uwp-status uwp-status-notinstalled">
                                        Chưa cài đặt
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="uwp-card-desc">
                                <?php echo esc_html( $data['description'] ); ?>
                            </div>
                        </div>

                        <div class="uwp-card-actions">
                            <?php if ( $is_active ) : ?>
                                <a href="<?php echo esc_url( $settings_url ); ?>" class="uwp-btn uwp-btn-primary">
                                    <span class="dashicons dashicons-admin-settings" style="font-size:17px; line-height:22px; margin-right:4px;"></span> Cấu hình ngay
                                </a>
                            <?php elseif ( $is_installed ) : ?>
                                <?php
                                $activate_url = wp_nonce_url( admin_url( 'plugins.php?action=activate&plugin=' . $data['path'] ), 'activate-plugin_' . $data['path'] );
                                ?>
                                <a href="<?php echo esc_url( $activate_url ); ?>" class="uwp-btn uwp-btn-secondary" style="background-color: #fef3c7; color: #d97706;">
                                    <span class="dashicons dashicons-admin-plugins" style="font-size:17px; line-height:22px; margin-right:4px;"></span> Kích hoạt Plugin
                                </a>
                            <?php else : ?>
                                <button class="uwp-btn uwp-btn-disabled" disabled>
                                    Chưa cài đặt Plugin
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <!-- Ecosystem Info -->
            <div class="uwp-info-box">
                <h3>Về hệ sinh thái Ultimate WP Plugins</h3>
                <p>Hệ sinh thái Ultimate WP được xây dựng với mục tiêu mang lại hiệu năng cao nhất, giao diện trực quan thân thiện và khả năng tương thích tuyệt vời cho các website chạy mã nguồn WordPress và Flatsome. Toàn bộ các plugin đều được tối ưu hóa sâu ở mức mã nguồn để đảm bảo tốc độ tải trang nhanh nhất.</p>
            </div>
        </div>
        <?php
    }
}
