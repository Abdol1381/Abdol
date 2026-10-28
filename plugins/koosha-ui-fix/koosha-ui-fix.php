<?php
/**
 * Plugin Name: Koosha UI Fix
 * Description: Fix shop product cards, overlays, button gaps, article cards, Yekan Bakh font, clickable phone/domain.
 * Version: 1.2.3
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'KOOSHA_UI_FIX_VER', '1.2.3' );

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

/**
 * Linkify visible HTML text only.
 * Never touch <script> (JSON-LD), <style>, <noscript>, <textarea>, or HTML attributes.
 */
function koosha_ui_fix_linkify_html( $html ) {
	if ( ! is_string( $html ) || $html === '' ) {
		return $html;
	}

	$protected = array();
	// Protect scripts/styles AND existing anchors so we never nest <a> or break JSON-LD.
	$html = preg_replace_callback(
		'/<(script|style|noscript|textarea|code|pre|a)\b[^>]*>.*?<\/\1>/is',
		static function ( $m ) use ( &$protected ) {
			$key               = '<!--KOOSHA_PROTECT_' . count( $protected ) . '-->';
			$protected[ $key ] = $m[0];
			return $key;
		},
		$html
	);

	// Protect tag attributes so href/src/content URLs are not rewritten.
	$html = preg_replace_callback(
		'/<[a-zA-Z][^>]*>/s',
		static function ( $m ) use ( &$protected ) {
			$key               = '<!--KOOSHA_PROTECT_' . count( $protected ) . '-->';
			$protected[ $key ] = $m[0];
			return $key;
		},
		$html
	);

	$html = preg_replace(
		'/https?:\/\/(www\.)?kooshatajhiz\.com\/?/u',
		'<a class="koosha-inline-link" href="https://www.kooshatajhiz.com/">www.kooshatajhiz.com</a>',
		$html
	);

	$html = preg_replace(
		'/(?<![\w.\/])www\.kooshatajhiz\.com\/?/u',
		'<a class="koosha-inline-link" href="https://www.kooshatajhiz.com/">www.kooshatajhiz.com</a>',
		$html
	);

	$html = preg_replace_callback(
		'/(?<![\d۰-۹])(0?9191499042|09191499042|۰۹۱۹۱۴۹۹۰۴۲)(?![\d۰-۹])/u',
		static function ( $m ) {
			return '<a class="koosha-tel-link koosha-inline-link" href="tel:+989191499042">' . esc_html( $m[1] ) . '</a>';
		},
		$html
	);

	if ( $protected ) {
		$html = strtr( $html, $protected );
	}

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
