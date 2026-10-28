<?php
/**
 * Plugin Name: Koosha UI Fix
 * Description: Fix shop product cards, overlays, button gaps, article cards, Yekan Bakh font, clickable phone/domain.
 * Version: 1.1.2
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'KOOSHA_UI_FIX_VER', '1.1.2' );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'koosha-ui-fix',
		plugins_url( 'assets/ui-fix.css', __FILE__ ),
		array(),
		KOOSHA_UI_FIX_VER
	);
}, 999 );

/** Override WooCommerce content-product template from this plugin. */
add_filter( 'wc_get_template_part', function ( $template, $slug, $name ) {
	if ( 'content' === $slug && 'product' === $name ) {
		$custom = plugin_dir_path( __FILE__ ) . 'woocommerce/content-product.php';
		if ( file_exists( $custom ) ) {
			return $custom;
		}
	}
	return $template;
}, 99, 3 );

/**
 * Make domain + phone clickable. Display domain without https://.
 */
add_filter( 'the_content', 'koosha_ui_fix_linkify', 20 );
add_filter( 'widget_text', 'koosha_ui_fix_linkify', 20 );

function koosha_ui_fix_linkify( $content ) {
	if ( is_admin() || empty( $content ) ) {
		return $content;
	}
	return koosha_ui_fix_linkify_html( $content );
}

function koosha_ui_fix_linkify_html( $html ) {
	// Skip already-linked URLs (attribute or inside <a>).
	$html = preg_replace_callback(
		'/(?<!href=["\'])(?<!src=["\'])(?<!content=["\'])https?:\/\/(www\.)?kooshatajhiz\.com\/?(?![^<]*>)/u',
		static function () {
			return '<a class="koosha-inline-link" href="https://www.kooshatajhiz.com/">www.kooshatajhiz.com</a>';
		},
		$html
	);

	// Plain www. without scheme.
	$html = preg_replace_callback(
		'/(?<!href=["\'])(?<!\/\/)(?<!>)www\.kooshatajhiz\.com\/?(?![^<]*>)/u',
		static function () {
			return '<a class="koosha-inline-link" href="https://www.kooshatajhiz.com/">www.kooshatajhiz.com</a>';
		},
		$html
	);

	$html = preg_replace_callback(
		'/(?<!href=["\']tel:)(?<![\d۰-۹])(0?9191499042|09191499042|۰۹۱۹۱۴۹۹۰۴۲)(?![\d۰-۹])/u',
		static function ( $m ) {
			return '<a class="koosha-tel-link koosha-inline-link" href="tel:+989191499042">' . esc_html( $m[1] ) . '</a>';
		},
		$html
	);

	return $html;
}

/** Linkify theme template HTML (about / spotlight) via output buffer. */
add_action( 'template_redirect', function () {
	if ( is_admin() ) {
		return;
	}
	ob_start( static function ( $html ) {
		if ( ! is_string( $html ) || $html === '' ) {
			return $html;
		}
		if ( false === strpos( $html, 'kooshatajhiz' ) && false === strpos( $html, '09191499042' ) && false === strpos( $html, '۰۹۱۹۱۴۹۹۰۴۲' ) ) {
			return $html;
		}
		return koosha_ui_fix_linkify_html( $html );
	} );
} );
