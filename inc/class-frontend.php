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

		$style = "bottom: {$bottom}px; {$side}: {$margin}px;";
		$style .= ( 'left' === $side ) ? 'align-items: flex-start;' : 'align-items: flex-end;';

		$first_icon = ! empty( $buttons[0]['icon_svg'] ) ? $buttons[0]['icon_svg'] : ucb_get_svg( $buttons[0]['type'] );

		echo '<div class="' . esc_attr( $container_class ) . '" style="' . esc_attr( $style ) . '">';
		echo '<div class="ucb-desktop-main-btn"><span>' . $first_icon . '</span></div>';
		
		$sub_btn_style = ( 'left' === $side ) ? 'align-items: flex-start;' : 'align-items: flex-end;';
		echo '<div class="ucb-desktop-sub-buttons" style="' . esc_attr( $sub_btn_style ) . '">';
		
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
				<span class="ucb-icon"><?php echo $icon; // Allow SVG content ?></span>
				<span class="ucb-label"><?php echo esc_html( $button['label'] ); ?></span>
			</a>
			<?php
		}
		echo '</div>'; // End sub-buttons
		echo '</div>'; // End container
	}

	private function render_mobile_bar( $options ) {
		$container_class = "ucb-mobile-container ucb-mobile-mode-click active"; 
		$buttons = isset( $options['mobile_buttons'] ) ? $options['mobile_buttons'] : array();

		echo '<div class="' . esc_attr( $container_class ) . '">';
		
		// 1. The main trigger button (Will be at the bottom due to column-reverse)
		$main_icon = '<span class="dashicons dashicons-phone"></span>';
		if ( ! empty( $buttons ) ) {
			$first = $buttons[0];
			if ( ! empty( $first['icon_url'] ) ) {
				$main_icon = '<img src="' . esc_url( $first['icon_url'] ) . '" />';
			} elseif ( ! empty( $first['icon_svg'] ) ) {
				$main_icon = $first['icon_svg'];
			} else {
				$main_icon = ucb_get_svg( $first['type'] );
			}
		}
		
		echo '<div class="ucb-mobile-main-btn"><span>' . $main_icon . '</span></div>';

		// 2. The sub-buttons (Will stack upwards)
		echo '<div class="ucb-mobile-sub-buttons">';
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
			?>
			<a href="<?php echo esc_url( $link ); ?>" class="ucb-mobile-sub-btn ucb-btn-<?php echo esc_attr( $button['type'] ); ?>" target="_blank">
				<span class="ucb-label"><?php echo esc_html( $button['label'] ); ?></span>
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
