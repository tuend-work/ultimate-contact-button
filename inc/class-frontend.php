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

		if ( ! empty( $options['desktop_enabled'] ) ) {
			$this->render_desktop_button( $options );
		}

		if ( ! empty( $options['mobile_enabled'] ) ) {
			$this->render_mobile_bar( $options );
		}
	}

	private function render_desktop_button( $options ) {
		$buttons = isset( $options['desktop_buttons'] ) ? $options['desktop_buttons'] : array();
		if ( empty( $buttons ) ) {
			return;
		}

		$side   = isset( $options['position_side'] ) ? $options['position_side'] : 'right';
		$bottom = isset( $options['bottom_distance'] ) ? absint( $options['bottom_distance'] ) : 30;
		$margin = isset( $options['side_distance'] ) ? absint( $options['side_distance'] ) : 30;

		$display_mode = isset( $options['desktop_display_mode'] ) ? $options['desktop_display_mode'] : 'click';
		$container_class = "ucb-desktop-container ucb-desktop-mode-{$display_mode}";

		echo '<div class="' . esc_attr( $container_class ) . '" style="' . esc_attr( $style ) . '">';
		echo '<div class="ucb-desktop-main-btn"><span class="dashicons dashicons-phone"></span></div>';
		
		$sub_btn_style = ( 'left' === $side ) ? 'align-items: flex-start;' : 'align-items: flex-end;';
		echo '<div class="ucb-desktop-sub-buttons" style="' . esc_attr( $sub_btn_style ) . '">';
		
		foreach ( $buttons as $button ) {
			$link = $this->get_link( $button['type'], $button['link'] );
			
			// Use custom SVG if provided, otherwise get from library
			if ( ! empty( $button['icon_svg'] ) ) {
				$icon = $button['icon_svg']; // Already sanitized in Settings class or we should be careful here
			} else {
				$icon = ucb_get_svg( $button['type'] );
			}

			$btn_class = "ucb-sub-btn ucb-btn-{$button['type']}";
			if ( 'left' === $side ) {
				$btn_class .= ' ucb-btn-left';
			}
			?>
			<a href="<?php echo esc_url( $link ); ?>" class="<?php echo esc_attr( $btn_class ); ?>" target="_blank">
				<span class="ucb-icon"><?php echo $icon; // Allow SVG content ?></span>
				<span class="ucb-label"><?php echo esc_html( $button['label'] ); ?></span>
			</a>
			<?php
		}
		echo '</div>'; // End sub-buttons
		echo '</div>'; // End container
	}

	private function render_mobile_bar( $options ) {
		$container_class = "ucb-mobile-container ucb-mobile-mode-click active"; // Mobile always active/click oriented for FAB
		
		echo '<div class="' . esc_attr( $container_class ) . '">';
		
		// 1. The main trigger button (Will be at the bottom due to column-reverse)
		echo '<div class="ucb-mobile-main-btn"><span class="dashicons dashicons-phone"></span></div>';

		// 2. The sub-buttons (Will stack upwards)
		echo '<div class="ucb-mobile-sub-buttons">';
		for ( $i = 1; $i <= 5; $i++ ) {
			$slot = isset( $options["mobile_slot_$i"] ) ? $options["mobile_slot_$i"] : array();
			if ( empty( $slot['type'] ) ) {
				continue;
			}
			$link = $this->get_link( $slot['type'], $slot['link'] );
			
			// Use icon URL if provided, otherwise SVG library
			if ( ! empty( $slot['icon_url'] ) ) {
				$icon = '<img src="' . esc_url( $slot['icon_url'] ) . '" alt="' . esc_attr( $slot['label'] ) . '" />';
			} else {
				$icon = ucb_get_svg( $slot['type'] );
			}
			?>
			<a href="<?php echo esc_url( $link ); ?>" class="ucb-mobile-sub-btn ucb-btn-<?php echo esc_attr( $slot['type'] ); ?>" target="_blank">
				<span class="ucb-label"><?php echo esc_html( $slot['label'] ); ?></span>
				<span class="ucb-icon"><?php echo $icon; ?></span>
			</a>
			<?php
		}
		echo '</div>'; // End sub-buttons
		
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
