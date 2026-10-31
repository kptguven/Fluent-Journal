<?php
/**
 * Özelleştirici (Görünüm > Özelleştir) ayarları ve dinamik CSS.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ---------- Renk yardımcıları ---------- */
function fj_mix( $hex1, $hex2, $ratio ) {
	$a = sscanf( ltrim( $hex1, '#' ), '%02x%02x%02x' );
	$b = sscanf( ltrim( $hex2, '#' ), '%02x%02x%02x' );
	$o = '#';
	for ( $i = 0; $i < 3; $i++ ) {
		$o .= str_pad( dechex( (int) round( $a[ $i ] + ( $b[ $i ] - $a[ $i ] ) * $ratio ) ), 2, '0', STR_PAD_LEFT );
	}
	return $o;
}

function fj_sanitize_mode( $v ) {
	return in_array( $v, array( 'auto', 'light', 'dark' ), true ) ? $v : 'auto';
}
function fj_sanitize_checkbox( $v ) {
	return ( isset( $v ) && true == $v ) ? true : false; // phpcs:ignore
}

/* ---------- Customizer ---------- */
function fj_customize_register( $wp_customize ) {

	/* Yazı tipleri */
	$wp_customize->add_section( 'fj_fonts', array(
		'title'       => 'Yazı Tipleri',
		'priority'    => 30,
		'description' => 'Varsayılan sistem yazı tipleri hızlıdır ve ziyaretçi verisi dışarı göndermez. "(Google)" yazan bir yazı tipi seçerseniz, ziyaretçilerin tarayıcısı Google sunucularından o yazı tipini indirir.',
	) );

	$font_settings = array(
		'fj_font_heading' => array( 'Başlıklar', 'system-sans', false ),
		'fj_font_body'    => array( 'Metinler (yazı içeriği)', 'system-sans', false ),
		'fj_font_link'    => array( 'Bağlantılar', 'inherit', true ),
		'fj_font_button'  => array( 'Butonlar', 'inherit', true ),
	);
	foreach ( $font_settings as $id => $cfg ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $cfg[1],
			'sanitize_callback' => 'fj_sanitize_font',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( $id, array(
			'label'   => $cfg[0],
			'section' => 'fj_fonts',
			'type'    => 'select',
			'choices' => fj_font_choices( $cfg[2] ),
		) );
	}

	/* Renkler ve görünüm */
	$wp_customize->add_section( 'fj_look', array(
		'title'    => 'Renkler ve Açık/Koyu Tema',
		'priority' => 31,
	) );

	$wp_customize->add_setting( 'fj_color_brand', array( 'default' => '#8A6532', 'sanitize_callback' => 'sanitize_hex_color' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'fj_color_brand', array(
		'label'   => 'Ana vurgu rengi (bronz)',
		'section' => 'fj_look',
	) ) );

	$wp_customize->add_setting( 'fj_color_navy', array( 'default' => '#091724', 'sanitize_callback' => 'sanitize_hex_color' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'fj_color_navy', array(
		'label'       => 'Koyu renk (lacivert)',
		'description' => 'Açık temada yazı rengi, koyu temada arka plan rengi olur.',
		'section'     => 'fj_look',
	) ) );

	$wp_customize->add_setting( 'fj_default_mode', array( 'default' => 'auto', 'sanitize_callback' => 'fj_sanitize_mode' ) );
	$wp_customize->add_control( 'fj_default_mode', array(
		'label'       => 'İlk açılışta tema',
		'description' => 'Ziyaretçi menüdeki düğmeyle değiştirirse onun tercihi hatırlanır.',
		'section'     => 'fj_look',
		'type'        => 'select',
		'choices'     => array(
			'auto'  => 'Cihazın ayarına uysun',
			'light' => 'Her zaman açık',
			'dark'  => 'Her zaman koyu',
		),
	) );

	/* Genel */
	$wp_customize->add_section( 'fj_general', array(
		'title'    => 'Blog Ayarları',
		'priority' => 32,
	) );

	$wp_customize->add_setting( 'fj_show_reading_time', array( 'default' => true, 'sanitize_callback' => 'fj_sanitize_checkbox' ) );
	$wp_customize->add_control( 'fj_show_reading_time', array(
		'label'   => 'Yazılarda okuma süresini göster',
		'section' => 'fj_general',
		'type'    => 'checkbox',
	) );

	$wp_customize->add_setting( 'fj_show_author_box', array( 'default' => true, 'sanitize_callback' => 'fj_sanitize_checkbox' ) );
	$wp_customize->add_control( 'fj_show_author_box', array(
		'label'   => 'Yazı sonunda yazar kutusunu göster',
		'section' => 'fj_general',
		'type'    => 'checkbox',
	) );

	$wp_customize->add_setting( 'fj_footer_text', array( 'default' => '', 'sanitize_callback' => 'wp_kses_post' ) );
	$wp_customize->add_control( 'fj_footer_text', array(
		'label'       => 'Alt bilgi metni',
		'description' => 'Boş bırakırsanız "© Yıl Site Adı" yazılır.',
		'section'     => 'fj_general',
		'type'        => 'textarea',
	) );
}
add_action( 'customize_register', 'fj_customize_register' );

/* ---------- Dinamik CSS (renkler + yazı tipleri) ---------- */
function fj_dynamic_css() {
	$brand = sanitize_hex_color( get_theme_mod( 'fj_color_brand', '#8A6532' ) ) ?: '#8A6532';
	$navy  = sanitize_hex_color( get_theme_mod( 'fj_color_navy', '#091724' ) ) ?: '#091724';
	$white = '#ffffff';

	$fonts = '--font-heading:' . fj_font_stack( get_theme_mod( 'fj_font_heading', 'system-sans' ) ) . ';'
		. '--font-body:' . fj_font_stack( get_theme_mod( 'fj_font_body', 'system-sans' ) ) . ';'
		. '--font-link:' . fj_font_stack( get_theme_mod( 'fj_font_link', 'inherit' ) ) . ';'
		. '--font-button:' . fj_font_stack( get_theme_mod( 'fj_font_button', 'inherit' ) ) . ';';

	$light = 'color-scheme:light;'
		. '--bg:' . fj_mix( $navy, $white, 0.96 ) . ';'
		. '--surface:#ffffff;'
		. '--surface-2:' . fj_mix( $navy, $white, 0.93 ) . ';'
		. '--border:' . fj_mix( $navy, $white, 0.86 ) . ';'
		. '--text:' . $navy . ';'
		. '--text-muted:' . fj_mix( $navy, $white, 0.36 ) . ';'
		. '--brand:' . $brand . ';'
		. '--brand-hover:' . fj_mix( $brand, '#000000', 0.12 ) . ';'
		. '--brand-pressed:' . fj_mix( $brand, '#000000', 0.24 ) . ';'
		. '--brand-tint:' . fj_mix( $brand, $white, 0.9 ) . ';'
		. '--on-brand:#ffffff;'
		. '--link:' . $brand . ';'
		. '--link-hover:' . fj_mix( $brand, $navy, 0.35 ) . ';'
		. '--shadow-4:0 0 2px rgba(0,0,0,.12),0 2px 4px rgba(0,0,0,.14);'
		. '--shadow-8:0 0 2px rgba(0,0,0,.12),0 4px 8px rgba(0,0,0,.14);'
		. '--shadow-16:0 0 2px rgba(0,0,0,.12),0 8px 16px rgba(0,0,0,.14);';

	$dark = 'color-scheme:dark;'
		. '--bg:' . $navy . ';'
		. '--surface:' . fj_mix( $navy, $white, 0.05 ) . ';'
		. '--surface-2:' . fj_mix( $navy, $white, 0.1 ) . ';'
		. '--border:' . fj_mix( $navy, $white, 0.17 ) . ';'
		. '--text:' . fj_mix( $navy, $white, 0.93 ) . ';'
		. '--text-muted:' . fj_mix( $navy, $white, 0.66 ) . ';'
		. '--brand:' . $brand . ';'
		. '--brand-hover:' . fj_mix( $brand, $white, 0.14 ) . ';'
		. '--brand-pressed:' . fj_mix( $brand, $white, 0.06 ) . ';'
		. '--brand-tint:' . fj_mix( $brand, $navy, 0.72 ) . ';'
		. '--on-brand:#ffffff;'
		. '--link:' . fj_mix( $brand, $white, 0.4 ) . ';'
		. '--link-hover:' . fj_mix( $brand, $white, 0.6 ) . ';'
		. '--shadow-4:0 0 2px rgba(0,0,0,.24),0 2px 4px rgba(0,0,0,.28);'
		. '--shadow-8:0 0 2px rgba(0,0,0,.24),0 4px 8px rgba(0,0,0,.28);'
		. '--shadow-16:0 0 2px rgba(0,0,0,.24),0 8px 16px rgba(0,0,0,.28);';

	$css  = ':root{' . $fonts . $light . '}';
	$css .= '@media (prefers-color-scheme:dark){:root:not([data-theme]){' . $dark . '}}';
	$css .= ':root[data-theme=dark]{' . $dark . '}';
	$css .= ':root[data-theme=light]{' . $light . '}';
	return $css;
}

function fj_print_head_script() {
	$mode = get_theme_mod( 'fj_default_mode', 'auto' );
	?>
<script>(function(){try{var m=localStorage.getItem('fj-theme')||'<?php echo esc_js( $mode ); ?>';if(m==='auto'){m=matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light'}document.documentElement.setAttribute('data-theme',m)}catch(e){}})();</script>
	<?php
}
add_action( 'wp_head', 'fj_print_head_script', 0 );
