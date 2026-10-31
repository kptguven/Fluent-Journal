<?php
/**
 * Yazı tipi listesi ve yükleme.
 * Varsayılan olarak sistem yazı tipleri kullanılır (hiçbir dış istek yapılmaz).
 * Bir Google Fonts yazı tipi seçilirse yalnızca seçilen yazı tipleri yüklenir.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function fj_font_catalog() {
	$sans  = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
	$serif = 'Georgia, "Iowan Old Style", "Palatino Linotype", Palatino, "Times New Roman", serif';
	$mono  = 'ui-monospace, SFMono-Regular, Menlo, Consolas, monospace';

	// anahtar => [etiket, yedek ailesi, Google adı (null = sistem)]
	$fonts = array(
		'system-sans'  => array( 'Sistem – Sans (Segoe UI tarzı)', $sans, null ),
		'system-serif' => array( 'Sistem – Serif (Georgia tarzı)', $serif, null ),
		'system-mono'  => array( 'Sistem – Mono', $mono, null ),
	);

	$google_sans = array( 'Inter', 'Roboto', 'Open Sans', 'Lato', 'Montserrat', 'Poppins', 'Nunito', 'Source Sans 3', 'Raleway', 'Work Sans', 'DM Sans', 'Noto Sans', 'Fira Sans', 'IBM Plex Sans', 'Rubik', 'Manrope', 'Mulish', 'Karla', 'Quicksand', 'Josefin Sans', 'Archivo', 'Space Grotesk', 'Oswald', 'Ubuntu' );
	$google_serif = array( 'Merriweather', 'Playfair Display', 'Lora', 'PT Serif', 'Libre Baskerville', 'Crimson Pro', 'EB Garamond', 'Cormorant Garamond', 'Noto Serif', 'Source Serif 4', 'Spectral', 'Bitter', 'Roboto Slab', 'IBM Plex Serif' );

	foreach ( $google_sans as $name ) {
		$fonts[ 'g:' . $name ] = array( $name . ' (Google)', '"' . $name . '", ' . $sans, $name );
	}
	foreach ( $google_serif as $name ) {
		$fonts[ 'g:' . $name ] = array( $name . ' (Google, serif)', '"' . $name . '", ' . $serif, $name );
	}
	return $fonts;
}

/** Customizer açılır listesi için seçenekler. */
function fj_font_choices( $with_inherit = false ) {
	$choices = array();
	if ( $with_inherit ) {
		$choices['inherit'] = 'Metin ile aynı';
	}
	foreach ( fj_font_catalog() as $key => $f ) {
		$choices[ $key ] = $f[0];
	}
	return $choices;
}

function fj_sanitize_font( $value ) {
	$catalog = fj_font_catalog();
	return ( 'inherit' === $value || isset( $catalog[ $value ] ) ) ? $value : 'system-sans';
}

/** Seçilen yazı tipi anahtarının CSS font-family değerini verir. */
function fj_font_stack( $key, $fallback_key = 'system-sans' ) {
	$catalog = fj_font_catalog();
	if ( 'inherit' === $key ) {
		$key = get_theme_mod( 'fj_font_body', 'system-sans' );
	}
	if ( ! isset( $catalog[ $key ] ) ) {
		$key = $fallback_key;
	}
	return $catalog[ $key ][1];
}

/** Seçili Google yazı tiplerinin tek bir URL'si (yoksa boş). */
function fj_google_fonts_url() {
	$catalog = fj_font_catalog();
	$keys    = array(
		get_theme_mod( 'fj_font_heading', 'system-sans' ),
		get_theme_mod( 'fj_font_body', 'system-sans' ),
		get_theme_mod( 'fj_font_link', 'inherit' ),
		get_theme_mod( 'fj_font_button', 'inherit' ),
	);
	$families = array();
	foreach ( $keys as $key ) {
		if ( isset( $catalog[ $key ] ) && $catalog[ $key ][2] ) {
			$families[ $catalog[ $key ][2] ] = true;
		}
	}
	if ( ! $families ) {
		return '';
	}
	$parts = array();
	foreach ( array_keys( $families ) as $name ) {
		$parts[] = 'family=' . str_replace( '%20', '+', rawurlencode( $name ) ) . ':wght@400;700';
	}
	return 'https://fonts.googleapis.com/css2?' . implode( '&', $parts ) . '&display=swap';
}

/** Google yazı tipi kullanılıyorsa bağlantıyı önceden kur. */
function fj_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type && fj_google_fonts_url() ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'fj_resource_hints', 10, 2 );
