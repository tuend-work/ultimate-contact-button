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

		// General Section
		add_settings_section( 'ucb_general_section', __( 'General Settings', 'ultimate-contact-button' ), null, 'ultimate-contact-button' );

		add_settings_field(
			'desktop_enabled',
			__( 'Enable Desktop Button', 'ultimate-contact-button' ),
			array( $this, 'render_checkbox_field' ),
			'ultimate-contact-button',
			'ucb_general_section',
			array( 'label_for' => 'desktop_enabled' )
		);

		add_settings_field(
			'mobile_enabled',
			__( 'Enable Mobile Bar', 'ultimate-contact-button' ),
			array( $this, 'render_checkbox_field' ),
			'ultimate-contact-button',
			'ucb_general_section',
			array( 'label_for' => 'mobile_enabled' )
		);

		// Position Section
		add_settings_section( 'ucb_position_section', __( 'Position Settings (Desktop)', 'ultimate-contact-button' ), null, 'ultimate-contact-button' );

		add_settings_field(
			'position_side',
			__( 'Button Side', 'ultimate-contact-button' ),
			array( $this, 'render_select_field' ),
			'ultimate-contact-button',
			'ucb_position_section',
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
			'ucb_position_section',
			array( 'label_for' => 'bottom_distance', 'default' => 30 )
		);

		add_settings_field(
			'side_distance',
			__( 'Side Distance (px)', 'ultimate-contact-button' ),
			array( $this, 'render_number_field' ),
			'ultimate-contact-button',
			'ucb_position_section',
			array( 'label_for' => 'side_distance', 'default' => 30 )
		);

		add_settings_field(
			'desktop_display_mode',
			__( 'Display Mode', 'ultimate-contact-button' ),
			array( $this, 'render_select_field' ),
			'ultimate-contact-button',
			'ucb_position_section',
			array( 
				'label_for' => 'desktop_display_mode',
				'options' => array(
					'always' => 'Always Show',
					'click' => 'When Clicking/Hover'
				)
			)
		);

		// Mobile Section
		add_settings_section( 'ucb_mobile_section', __( 'Mobile Settings', 'ultimate-contact-button' ), null, 'ultimate-contact-button' );
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
				// do_settings_sections( 'ultimate-contact-button' );
				?>
				
				<div class="ucb-tabs">
					<nav class="ucb-tab-nav">
						<a href="#tab-general" class="active"><?php _e( 'General', 'ultimate-contact-button' ); ?></a>
						<a href="#tab-desktop"><?php _e( 'Desktop Button', 'ultimate-contact-button' ); ?></a>
						<a href="#tab-mobile"><?php _e( 'Mobile Bar', 'ultimate-contact-button' ); ?></a>
					</nav>
					
					<div class="ucb-tab-content active" id="tab-general">
						<?php do_settings_sections( 'ultimate-contact-button' ); ?>
					</div>
					
					<div class="ucb-tab-content" id="tab-desktop">
						<h3><?php _e( 'Desktop Contact Buttons', 'ultimate-contact-button' ); ?></h3>
						<?php $this->render_desktop_manager(); ?>
					</div>
					
					<div class="ucb-tab-content" id="tab-mobile">
						<h3><?php _e( 'Mobile Contact Bar', 'ultimate-contact-button' ); ?></h3>
						<?php $this->render_mobile_manager(); ?>
					</div>
				</div>

				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	private function render_desktop_manager() {
		$options = get_option( self::OPTION_NAME );
		$buttons = isset( $options['desktop_buttons'] ) ? $options['desktop_buttons'] : array();
		?>
		<div id="ucb-desktop-repeater">
			<ul class="ucb-sortable-list" id="ucb-desktop-list">
				<?php if ( ! empty( $buttons ) ) : ?>
					<?php foreach ( $buttons as $index => $button ) : ?>
						<li class="ucb-list-item">
							<span class="dashicons dashicons-move handle"></span>
							<div class="ucb-item-fields">
								<div class="ucb-field-row">
									<select name="<?php echo esc_attr( self::OPTION_NAME . "[desktop_buttons][$index][type]" ); ?>">
										<option value="phone" <?php selected( $button['type'], 'phone' ); ?>>Phone</option>
										<option value="zalo" <?php selected( $button['type'], 'zalo' ); ?>>Zalo</option>
										<option value="messenger" <?php selected( $button['type'], 'messenger' ); ?>>Messenger</option>
										<option value="whatsapp" <?php selected( $button['type'], 'whatsapp' ); ?>>WhatsApp</option>
										<option value="telegram" <?php selected( $button['type'], 'telegram' ); ?>>Telegram</option>
										<option value="mail" <?php selected( $button['type'], 'mail' ); ?>>Email</option>
										<option value="custom" <?php selected( $button['type'], 'custom' ); ?>>Custom</option>
									</select>
									<input type="text" name="<?php echo esc_attr( self::OPTION_NAME . "[desktop_buttons][$index][label]" ); ?>" value="<?php echo esc_attr( $button['label'] ); ?>" placeholder="Label" />
									<input type="text" name="<?php echo esc_attr( self::OPTION_NAME . "[desktop_buttons][$index][link]" ); ?>" value="<?php echo esc_attr( $button['link'] ); ?>" placeholder="Link/ID" />
								</div>
								<div class="ucb-field-row ucb-upload-row">
									<input type="text" class="ucb-img-url" name="<?php echo esc_attr( self::OPTION_NAME . "[desktop_buttons][$index][icon_url]" ); ?>" value="<?php echo isset( $button['icon_url'] ) ? esc_attr( $button['icon_url'] ) : ''; ?>" placeholder="Custom SVG URL" />
									<button type="button" class="button ucb-upload-btn"><?php _e( 'Upload SVG', 'ultimate-contact-button' ); ?></button>
								</div>
								<div class="ucb-field-row ucb-svg-row">
									<textarea name="<?php echo esc_attr( self::OPTION_NAME . "[desktop_buttons][$index][icon_svg]" ); ?>" placeholder="Or Paste Custom SVG Code here"><?php echo isset( $button['icon_svg'] ) ? esc_textarea( $button['icon_svg'] ) : ''; ?></textarea>
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

	private function render_mobile_manager() {
		$options = get_option( self::OPTION_NAME );
		$buttons = isset( $options['mobile_buttons'] ) ? $options['mobile_buttons'] : array();
		?>
		<div id="ucb-mobile-repeater">
			<ul class="ucb-sortable-list" id="ucb-mobile-list">
				<?php if ( ! empty( $buttons ) ) : ?>
					<?php foreach ( $buttons as $index => $button ) : ?>
						<li class="ucb-list-item">
							<span class="dashicons dashicons-move handle"></span>
							<div class="ucb-item-fields">
								<div class="ucb-field-row">
									<select name="<?php echo esc_attr( self::OPTION_NAME . "[mobile_buttons][$index][type]" ); ?>">
										<option value="phone" <?php selected( $button['type'], 'phone' ); ?>>Phone</option>
										<option value="zalo" <?php selected( $button['type'], 'zalo' ); ?>>Zalo</option>
										<option value="messenger" <?php selected( $button['type'], 'messenger' ); ?>>Messenger</option>
										<option value="whatsapp" <?php selected( $button['type'], 'whatsapp' ); ?>>WhatsApp</option>
										<option value="telegram" <?php selected( $button['type'], 'telegram' ); ?>>Telegram</option>
										<option value="mail" <?php selected( $button['type'], 'mail' ); ?>>Email</option>
										<option value="custom" <?php selected( $button['type'], 'custom' ); ?>>Custom</option>
									</select>
									<input type="text" name="<?php echo esc_attr( self::OPTION_NAME . "[mobile_buttons][$index][label]" ); ?>" value="<?php echo esc_attr( $button['label'] ); ?>" placeholder="Label" />
									<input type="text" name="<?php echo esc_attr( self::OPTION_NAME . "[mobile_buttons][$index][link]" ); ?>" value="<?php echo esc_attr( $button['link'] ); ?>" placeholder="Link/ID" />
								</div>
								<div class="ucb-field-row ucb-upload-row">
									<input type="text" class="ucb-img-url" name="<?php echo esc_attr( self::OPTION_NAME . "[mobile_buttons][$index][icon_url]" ); ?>" value="<?php echo isset( $button['icon_url'] ) ? esc_attr( $button['icon_url'] ) : ''; ?>" placeholder="Custom SVG URL" />
									<button type="button" class="button ucb-upload-btn"><?php _e( 'Upload SVG', 'ultimate-contact-button' ); ?></button>
								</div>
								<div class="ucb-field-row ucb-svg-row">
									<textarea name="<?php echo esc_attr( self::OPTION_NAME . "[mobile_buttons][$index][icon_svg]" ); ?>" placeholder="Or Paste Custom SVG Code here"><?php echo isset( $button['icon_svg'] ) ? esc_textarea( $button['icon_svg'] ) : ''; ?></textarea>
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
		
		if ( isset( $input['desktop_enabled'] ) ) {
			$output['desktop_enabled'] = 1;
		}
		
		if ( isset( $input['mobile_enabled'] ) ) {
			$output['mobile_enabled'] = 1;
		}

		if ( isset( $input['desktop_display_mode'] ) ) {
			$output['desktop_display_mode'] = sanitize_text_field( $input['desktop_display_mode'] );
		}

		if ( isset( $input['position_side'] ) ) {
			$output['position_side'] = sanitize_text_field( $input['position_side'] );
		}

		if ( isset( $input['bottom_distance'] ) ) {
			$output['bottom_distance'] = absint( $input['bottom_distance'] );
		}

		if ( isset( $input['side_distance'] ) ) {
			$output['side_distance'] = absint( $input['side_distance'] );
		}

		if ( isset( $input['desktop_buttons'] ) ) {
			$output['desktop_buttons'] = ucb_sanitize_array( $input['desktop_buttons'] );
		}

		if ( isset( $input['mobile_buttons'] ) ) {
			$output['mobile_buttons'] = ucb_sanitize_array( $input['mobile_buttons'] );
		}

		return $output;
	}
}

new UCB_Settings();
