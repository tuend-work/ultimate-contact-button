<?php
/**
 * Frontend rendering class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UCB_Frontend {

	public function __construct() {
		add_action( 'wp_footer', array( $this, 'render_elements' ) );
	}

	public function render_elements() {
		$options = get_option( 'ucb_settings' );
		if ( ! $options ) {
			return;
		}

		// 1. Main Contact Button (FAB) - Desktop & Mobile
		if ( ! empty( $options['main_enabled'] ) ) {
			$this->render_main_button( $options );
		}

		// 2. Bottom Mobile Menu - Mobile Only
		if ( ! empty( $options['bottom_menu_enabled'] ) ) {
			$this->render_bottom_menu( $options );
		}
	}

	private function render_main_button( $options ) {
		// Migration fallback
		$buttons = isset( $options['main_buttons'] ) ? $options['main_buttons'] : (isset($options['desktop_buttons']) ? $options['desktop_buttons'] : array());
		if ( empty( $buttons ) ) {
			return;
		}

		$side   = isset( $options['position_side'] ) ? $options['position_side'] : 'right';
		$bottom = isset( $options['bottom_distance'] ) ? absint( $options['bottom_distance'] ) : 30;
		$margin = isset( $options['side_distance'] ) ? absint( $options['side_distance'] ) : 30;
		$main_color = isset( $options['main_button_color'] ) ? $options['main_button_color'] : '#1e73be';

		$display_mode = isset( $options['main_display_mode'] ) ? $options['main_display_mode'] : 'click';
		// Using a generic class ucb-main-container so we can style it for both PC/Mobile
		$container_class = "ucb-main-container ucb-mode-{$display_mode}";
		if ( empty( $options['main_mobile_enabled'] ) ) {
			$container_class .= " ucb-hide-mobile";
		}

		$style = "bottom: {$bottom}px; {$side}: {$margin}px;";
		$style .= ( 'left' === $side ) ? 'align-items: flex-start;' : 'align-items: flex-end;';

		$first_icon = ! empty( $buttons[0]['icon_url'] ) ? '<img src="' . esc_url( $buttons[0]['icon_url'] ) . '" />' : (!empty($buttons[0]['icon_svg']) ? $buttons[0]['icon_svg'] : ucb_get_svg( $buttons[0]['type'] ));

		echo '<div class="' . esc_attr( $container_class ) . '" style="' . esc_attr( $style ) . '">';
		echo '<div class="ucb-main-trigger" style="background-color: ' . esc_attr( $main_color ) . ';"><span>' . $first_icon . '</span></div>';
		
		$sub_btn_style = ( 'left' === $side ) ? 'align-items: flex-start;' : 'align-items: flex-end;';
		echo '<div class="ucb-sub-buttons-list" style="' . esc_attr( $sub_btn_style ) . '">';
		
		foreach ( $buttons as $button ) {
			$link = $this->get_link( $button['type'], $button['link'] );
			
			// Icon priority: URL > Code > Library
			if ( ! empty( $button['icon_url'] ) ) {
				$icon = '<img src="' . esc_url( $button['icon_url'] ) . '" alt="' . esc_attr( $button['label'] ) . '" />';
			} elseif ( ! empty( $button['icon_svg'] ) ) {
				$icon = $button['icon_svg'];
			} else {
				$icon = ucb_get_svg( $button['type'] );
			}

			$btn_class = "ucb-sub-btn ucb-btn-{$button['type']}";
			if ( 'left' === $side ) {
				$btn_class .= ' ucb-btn-left';
			}
			?>
			<a href="<?php echo esc_url( $link ); ?>" class="<?php echo esc_attr( $btn_class ); ?>" target="_blank">
				<span class="ucb-icon"><?php echo $icon; ?></span>
				<span class="ucb-label"><?php echo esc_html( $button['label'] ); ?></span>
			</a>
			<?php
		}
		echo '</div>'; // End sub-buttons
		echo '</div>'; // End container
	}

	private function render_bottom_menu( $options ) {
		// Migration fallback
		$buttons = isset( $options['bottom_menu_buttons'] ) ? $options['bottom_menu_buttons'] : (isset($options['mobile_buttons']) ? $options['mobile_buttons'] : array());
		if ( empty( $buttons ) ) {
			return;
		}

		echo '<div class="ucb-bottom-menu-container">';
		
		foreach ( $buttons as $button ) {
			$link = $this->get_link( $button['type'], $button['link'] );
			
			if ( ! empty( $button['icon_url'] ) ) {
				$icon = '<img src="' . esc_url( $button['icon_url'] ) . '" alt="' . esc_attr( $button['label'] ) . '" />';
			} elseif ( ! empty( $button['icon_svg'] ) ) {
				$icon = $button['icon_svg'];
			} else {
				$icon = ucb_get_svg( $button['type'] );
			}
			?>
			<a href="<?php echo esc_url( $link ); ?>" class="ucb-bottom-menu-item" target="_blank">
				<span class="ucb-menu-icon"><?php echo $icon; ?></span>
				<span class="ucb-menu-label"><?php echo esc_html( $button['label'] ); ?></span>
			</a>
			<?php
		}
		
		echo '</div>'; // End container
	}

	private function get_link( $type, $value ) {
		switch ( $type ) {
			case 'phone':
				return 'tel:' . $value;
			case 'mail':
				return 'mailto:' . $value;
			case 'messenger':
				return 'https://m.me/' . $value;
			case 'zalo':
				return 'https://zalo.me/' . $value;
			case 'whatsapp':
				return 'https://wa.me/' . $value;
			case 'telegram':
				return 'https://t.me/' . $value;
			default:
				return $value;
		}
	}
}

new UCB_Frontend();
