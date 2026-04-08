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
		add_menu_page(
			__( 'Ultimate Contact Button', 'ultimate-contact-button' ),
			__( 'Contact Button', 'ultimate-contact-button' ),
			'manage_options',
			'ultimate-contact-button',
			array( $this, 'render_settings_page' ),
			'dashicons-phone',
			30
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
			<h1><?php _e( 'Ultimate Contact Button Settings', 'ultimate-contact-button' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( self::OPTION_NAME );
				?>
				
				<div class="ucb-tabs">
					<nav class="ucb-tab-nav">
						<a href="#tab-main" class="active"><?php _e( 'Main Contact Button', 'ultimate-contact-button' ); ?></a>
						<a href="#tab-bottom"><?php _e( 'Bottom Mobile Menu', 'ultimate-contact-button' ); ?></a>
					</nav>
					
					<div class="ucb-tab-content active" id="tab-main">
						<?php do_settings_sections( 'ultimate-contact-button' ); ?>
						<hr/>
						<h3><?php _e( 'Main Button Items (Always visible or FAB)', 'ultimate-contact-button' ); ?></h3>
						<?php $this->render_main_manager(); ?>
					</div>
					
					<div class="ucb-tab-content" id="tab-bottom">
						<?php 
						do_settings_sections( 'ultimate-contact-button-bottom' );
						?>
						<hr/>
						<h3><?php _e( 'Bottom Menu Items', 'ultimate-contact-button' ); ?></h3>
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
									<button type="button" class="button ucb-upload-btn"><?php _e( 'Upload SVG', 'ultimate-contact-button' ); ?></button>
								</div>
								<div class="ucb-field-row ucb-svg-row">
									<textarea name="<?php echo esc_attr( self::OPTION_NAME . "[main_buttons][$index][icon_svg]" ); ?>" placeholder="Or Paste Custom SVG Code here"><?php echo isset( $button['icon_svg'] ) ? esc_textarea( $button['icon_svg'] ) : ''; ?></textarea>
								</div>
							</div>
							<button type="button" class="ucb-remove-item button-link-delete"><?php _e( 'Remove', 'ultimate-contact-button' ); ?></button>
						</li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
			<button type="button" class="button tagadd" id="ucb-add-desktop-item"><?php _e( 'Add Button', 'ultimate-contact-button' ); ?></button>
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
			<button type="button" class="button tagadd" id="ucb-add-mobile-item"><?php _e( 'Add Mobile Button', 'ultimate-contact-button' ); ?></button>
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

		$texts = array( 'main_display_mode', 'position_side' );
		foreach ( $texts as $text ) {
			if ( isset( $input[ $text ] ) ) {
				$output[ $text ] = sanitize_text_field( $input[ $text ] );
			}
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
