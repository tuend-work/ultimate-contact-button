<?php
/**
 * Helper functions and SVG library
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get SVG icon from library
 *
 * @param string $icon_name Name of the icon.
 * @return string SVG code.
 */
function ucb_get_svg( $icon_name ) {
	$icons = array(
		'phone'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>',
		'messenger' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>',
		'mail'      => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>',
		'whatsapp'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>',
		'zalo'      => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>', // Placeholder
		'telegram'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>',
	);

	return isset( $icons[ $icon_name ] ) ? $icons[ $icon_name ] : '';
}

/**
 * Sanitize array recursively
 *
 * @param array $array Array to sanitize.
 * @return array Sanitized array.
 */
function ucb_sanitize_array( $array ) {
	foreach ( $array as $key => &$value ) {
		if ( is_array( $value ) ) {
			$value = ucb_sanitize_array( $value );
		} else {
			if ( 'icon_svg' === $key ) {
				// Allow SVG tags for the icon field
				$value = wp_kses( $value, array(
					'svg'      => array(
						'class'           => true,
						'aria-hidden'     => true,
						'aria-labelledby' => true,
						'role'            => true,
						'viewbox'         => true,
						'xmlns'           => true,
						'width'           => true,
						'height'          => true,
						'fill'            => true,
						'stroke'          => true,
						'stroke-width'    => true,
						'stroke-linecap'  => true,
						'stroke-linejoin' => true,
					),
					'path'     => array(
						'd'    => true,
						'fill' => true,
						'stroke' => true,
					),
					'circle'   => array(
						'cx' => true,
						'cy' => true,
						'r'  => true,
						'fill' => true,
					),
					'line'     => array(
						'x1' => true,
						'y1' => true,
						'x2' => true,
						'y2' => true,
						'stroke' => true,
					),
					'polyline' => array(
						'points' => true,
						'fill' => true,
					),
					'polygon'  => array(
						'points' => true,
						'fill' => true,
					),
					'rect'     => array(
						'x'      => true,
						'y'      => true,
						'width'  => true,
						'height' => true,
						'rx'     => true,
						'ry'     => true,
						'fill'   => true,
					),
				) );
			} else {
				$value = sanitize_text_field( $value );
			}
		}
	}
	return $array;
}
