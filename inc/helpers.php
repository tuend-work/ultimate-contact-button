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
		'phone'     => '<svg viewBox="0 0 512 512"><circle fill="#2196F3" cx="256" cy="256" r="256"/><path fill="#FAFAFA" d="M384,308.928c-27.616,0-53.952-6.016-78.24-17.888c-3.808-1.824-8.224-2.112-12.256-0.736 c-4.032,1.408-7.328,4.352-9.184,8.16l-11.52,23.84c-34.56-19.84-63.232-48.544-83.104-83.104l23.872-11.52 c3.84-1.856,6.752-5.152,8.16-9.184c1.376-4.032,1.12-8.448-0.736-12.256c-11.904-24.256-17.92-50.592-17.92-78.24 c0-8.832-7.168-16-16-16H128c-8.832,0-16,7.168-16,16c0,149.984,122.016,272,272,272c8.832,0,16-7.168,16-16v-59.072 C400,316.096,392.832,308.928,384,308.928z"/></svg>',
		'messenger' => '<svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><g><path d="m512 256c0 127.78-93.62 233.69-216 252.89v-178.89h59.65l11.35-74h-71v-48.02c0-20.25 9.92-39.98 41.72-39.98h32.28v-63s-29.3-5-57.31-5c-58.47 0-96.69 35.44-96.69 99.6v56.4h-65v74h65v178.89c-122.38-19.2-216-125.11-216-252.89 0-141.38 114.62-256 256-256s256 114.62 256 256z" fill="#1877f2"/><path d="m355.65 330 11.35-74h-71v-48.021c0-20.245 9.918-39.979 41.719-39.979h32.281v-63s-29.296-5-57.305-5c-58.476 0-96.695 35.44-96.695 99.6v56.4h-65v74h65v178.889c13.034 2.045 26.392 3.111 40 3.111s26.966-1.066 40-3.111v-178.889z" fill="#fff"/></g></svg>',
		'mail'      => '<svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><g><g><circle cx="256" cy="256" fill="#2196f3" r="256"/><g fill="#fff"><path d="m255.94 268.64-134.94-67.32v133.76a25.66 25.66 0 0 0 25.59 25.59h218.82a25.66 25.66 0 0 0 25.59-25.59v-132.35z"/><path d="m256.06 243.36 134.94-65.86v-.58a25.66 25.66 0 0 0 -25.59-25.59h-218.82a25.66 25.66 0 0 0 -25.59 24.67z"/></g></g></g></svg>',
		'whatsapp'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>',
		'zalo'      => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48px" height="48px"><path fill="#2962ff" d="M15,36V6.827l-1.211-0.811C8.64,8.083,5,13.112,5,19v10c0,7.732,6.268,14,14,14h10 c4.722,0,8.883-2.348,11.417-5.931V36H15z"/><path fill="#eee" d="M29,5H19c-1.845,0-3.601,0.366-5.214,1.014C10.453,9.25,8,14.528,8,19 c0,6.771,0.936,10.735,3.712,14.607c0.216,0.301,0.357,0.653,0.376,1.022c0.043,0.835-0.129,2.365-1.634,3.742 c-0.162,0.148-0.059,0.419,0.16,0.428c0.942,0.041,2.843-0.014,4.797-0.877c0.557-0.246,1.191-0.203,1.729,0.083 C20.453,39.764,24.333,40,28,40c4.676,0,9.339-1.04,12.417-2.916C42.038,34.799,43,32.014,43,29V19C43,11.268,36.732,5,29,5z"/><path fill="#2962ff" d="M36.75,27C34.683,27,33,25.317,33,23.25s1.683-3.75,3.75-3.75s3.75,1.683,3.75,3.75 S38.817,27,36.75,27z M36.75,21c-1.24,0-2.25,1.01-2.25,2.25s1.01,2.25,2.25,2.25S39,24.49,39,23.25S37.99,21,36.75,21z"/><path fill="#2962ff" d="M31.5,27h-1c-0.276,0-0.5-0.224-0.5-0.5V18h1.5V27z"/><path fill="#2962ff" d="M27,19.75v0.519c-0.629-0.476-1.403-0.769-2.25-0.769c-2.067,0-3.75,1.683-3.75,3.75 S22.683,27,24.75,27c0.847,0,1.621-0.293,2.25-0.769V26.5c0,0.276,0.224,0.5,0.5,0.5h1v-7.25H27z M24.75,25.5 c-1.24,0-2.25-1.01-2.25-2.25S23.51,21,24.75,21S27,22.01,27,23.25S25.99,25.5,24.75,25.5z"/><path fill="#2962ff" d="M21.25,18h-8v1.5h5.321L13,26h0.026c-0.163,0.211-0.276,0.463-0.276,0.75V27h7.5 c0.276,0,0.5-0.224,0.5-0.5v-1h-5.321L21,19h-0.026c0.163-0.211,0.276-0.463,0.276-0.75V18z"/></svg>', // Placeholder
		'telegram'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>',
	);

	return isset( $icons[ $icon_name ] ) ? $icons[ $icon_name ] : '';
}

/**
 * Get allowed SVG tags and attributes for wp_kses.
 * Centralized so the same whitelist is used for BOTH sanitization and output escaping.
 *
 * @return array Allowed tags array for wp_kses.
 */
function ucb_allowed_svg_tags() {
	return array(
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
			'd'            => true,
			'fill'         => true,
			'stroke'       => true,
			'stroke-width' => true,
			'transform'    => true,
		),
		'circle'   => array(
			'cx'           => true,
			'cy'           => true,
			'r'            => true,
			'fill'         => true,
			'stroke'       => true,
			'stroke-width' => true,
		),
		'line'     => array(
			'x1'           => true,
			'y1'           => true,
			'x2'           => true,
			'y2'           => true,
			'stroke'       => true,
			'stroke-width' => true,
		),
		'polyline' => array(
			'points'       => true,
			'fill'         => true,
			'stroke'       => true,
			'stroke-width' => true,
		),
		'polygon'  => array(
			'points'       => true,
			'fill'         => true,
			'stroke'       => true,
			'stroke-width' => true,
		),
		'rect'     => array(
			'x'            => true,
			'y'            => true,
			'width'        => true,
			'height'       => true,
			'rx'           => true,
			'ry'           => true,
			'fill'         => true,
			'stroke'       => true,
			'stroke-width' => true,
			'transform'    => true,
		),
		'g'        => array(
			'fill'      => true,
			'stroke'    => true,
			'transform' => true,
			'id'        => true,
		),
		'defs'     => array(),
	);
}

/**
 * Get allowed button types.
 *
 * @return array List of valid button type slugs.
 */
function ucb_allowed_button_types() {
	return array( 'phone', 'zalo', 'messenger', 'whatsapp', 'telegram', 'mail', 'custom' );
}

/**
 * Sanitize array recursively
 *
 * @param array $array Array to sanitize.
 * @return array Sanitized array.
 */
function ucb_sanitize_array( $array ) {
	if ( ! is_array( $array ) ) {
		return array();
	}

	$sanitized = array();
	foreach ( $array as $key => $value ) {
		if ( is_array( $value ) ) {
			$sanitized[ $key ] = ucb_sanitize_array( $value );
		} else {
			if ( 'icon_svg' === $key ) {
				$sanitized[ $key ] = wp_kses( $value, ucb_allowed_svg_tags() );
			} elseif ( 'type' === $key ) {
				// Validate against allowed types
				$sanitized[ $key ] = in_array( $value, ucb_allowed_button_types(), true ) ? $value : 'custom';
			} elseif ( 'link' === $key ) {
				$sanitized[ $key ] = sanitize_text_field( $value );
			} elseif ( 'icon_url' === $key ) {
				$sanitized[ $key ] = esc_url_raw( $value );
			} else {
				$sanitized[ $key ] = sanitize_text_field( $value );
			}
		}
	}
	return $sanitized;
}
