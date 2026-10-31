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

/** #abc → #aabbcc */
function fj_hex( $c ) {
	$c = ltrim( (string) $c, '#' );
	if ( 3 === strlen( $c ) ) {
		$c = $c[0] . $c[0] . $c[1] . $c[1] . $c[2] . $c[2];
	}
	return '#' . strtolower( $c );
}

/** Özelleştirilebilir renkler: CSS değişkeni => Türkçe ad */
function fj_color_fields() {
	return array(
		'bg'          => 'Sayfa arka planı',
		'surface'     => 'Kutu / kart arka planı',
		'surface-2'   => 'Hafif zemin (üzerine gelince, etiketler)',
		'border'      => 'Kenarlık çizgileri',
		'text'        => 'Yazı rengi',
		'text-muted'  => 'Soluk yazı (tarih, yazar vb.)',
		'brand'       => 'Buton rengi',
		'brand-hover' => 'Buton üzerine gelince',
		'on-brand'    => 'Buton yazı rengi',
		'link'        => 'Bağlantı rengi',
		'link-hover'  => 'Bağlantı üzerine gelince',
		'brand-tint'  => 'Vurgulu rozet arka planı ("En yeni" vb.)',
	);
}

function fj_color_setting_id( $mode, $var ) {
	return 'fj_c_' . $mode . '_' . str_replace( '-', '_', $var );
}

/** Temel renklerden (ana vurgu + koyu renk) otomatik hesaplanan değerler. */
function fj_palette_defaults( $mode ) {
	$brand = fj_hex( sanitize_hex_color( get_theme_mod( 'fj_color_brand', '#8A6532' ) ) ?: '#8A6532' );
	$navy  = fj_hex( sanitize_hex_color( get_theme_mod( 'fj_color_navy', '#091724' ) ) ?: '#091724' );
	$white = '#ffffff';

	if ( 'dark' === $mode ) {
		return array(
			'bg'          => $navy,
			'surface'     => fj_mix( $navy, $white, 0.05 ),
			'surface-2'   => fj_mix( $navy, $white, 0.1 ),
			'border'      => fj_mix( $navy, $white, 0.17 ),
			'text'        => fj_mix( $navy, $white, 0.93 ),
			'text-muted'  => fj_mix( $navy, $white, 0.66 ),
			'brand'       => $brand,
			'brand-hover' => fj_mix( $brand, $white, 0.14 ),
			'on-brand'    => '#ffffff',
			'link'        => fj_mix( $brand, $white, 0.4 ),
			'link-hover'  => fj_mix( $brand, $white, 0.6 ),
			'brand-tint'  => fj_mix( $brand, $navy, 0.72 ),
		);
	}
	return array(
		'bg'          => fj_mix( $navy, $white, 0.96 ),
		'surface'     => '#ffffff',
		'surface-2'   => fj_mix( $navy, $white, 0.93 ),
		'border'      => fj_mix( $navy, $white, 0.86 ),
		'text'        => $navy,
		'text-muted'  => fj_mix( $navy, $white, 0.36 ),
		'brand'       => $brand,
		'brand-hover' => fj_mix( $brand, '#000000', 0.12 ),
		'on-brand'    => '#ffffff',
		'link'        => $brand,
		'link-hover'  => fj_mix( $brand, $navy, 0.35 ),
		'brand-tint'  => fj_mix( $brand, $white, 0.9 ),
	);
}

/** Otomatik değerler + kullanıcının tek tek seçtiği renkler. */
function fj_palette( $mode ) {
	$p = fj_palette_defaults( $mode );
	foreach ( array_keys( fj_color_fields() ) as $var ) {
		$v = sanitize_hex_color( get_theme_mod( fj_color_setting_id( $mode, $var ), '' ) );
		if ( $v ) {
			$p[ $var ] = fj_hex( $v );
		}
	}
	$p['brand-pressed'] = ( 'dark' === $mode ) ? fj_mix( $p['brand'], '#ffffff', 0.06 ) : fj_mix( $p['brand'], '#000000', 0.24 );
	return $p;
}

function fj_palette_css( $mode ) {
	$css = 'color-scheme:' . $mode . ';';
	foreach ( fj_palette( $mode ) as $k => $v ) {
		$css .= '--' . $k . ':' . $v . ';';
	}
	$a = ( 'dark' === $mode ) ? array( '.24', '.28' ) : array( '.12', '.14' );
	$css .= '--shadow-4:0 0 2px rgba(0,0,0,' . $a[0] . '),0 2px 4px rgba(0,0,0,' . $a[1] . ');'
		. '--shadow-8:0 0 2px rgba(0,0,0,' . $a[0] . '),0 4px 8px rgba(0,0,0,' . $a[1] . ');'
		. '--shadow-16:0 0 2px rgba(0,0,0,' . $a[0] . '),0 8px 16px rgba(0,0,0,' . $a[1] . ');';
	return $css;
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

	/* Renkler paneli: temel renkler + açık tema + koyu tema */
	$wp_customize->add_panel( 'fj_colors', array(
		'title'       => 'Renkler ve Açık/Koyu Tema',
		'priority'    => 31,
		'description' => 'Önce "Temel Renkler"den iki ana rengi seçin; tema geri kalanını otomatik hesaplar. Sonra isterseniz Açık ve Koyu Tema bölümlerinden her rengi tek tek değiştirin.',
	) );

	$wp_customize->add_section( 'fj_look', array(
		'title'    => 'Temel Renkler ve Tema Modu',
		'panel'    => 'fj_colors',
		'priority' => 1,
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

	/* Açık ve koyu tema: tek tek renkler */
	$modes = array(
		'light' => array( 'Açık Tema Renkleri', 2 ),
		'dark'  => array( 'Koyu Tema Renkleri', 3 ),
	);
	foreach ( $modes as $mode => $info ) {
		$section = 'fj_colors_' . $mode;
		$wp_customize->add_section( $section, array(
			'title'       => $info[0],
			'panel'       => 'fj_colors',
			'priority'    => $info[1],
			'description' => 'Bir rengi değiştirmezseniz otomatik değeri kullanılır. Otomatik değere dönmek için rengin yanındaki "Temizle" düğmesine basın. Bu bölümü açınca önizleme otomatik olarak ' . ( 'dark' === $mode ? 'koyu' : 'açık' ) . ' temaya geçer.',
		) );
		$defaults = fj_palette_defaults( $mode );
		foreach ( fj_color_fields() as $var => $label ) {
			$id = fj_color_setting_id( $mode, $var );
			$wp_customize->add_setting( $id, array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, array(
				'label'       => $label,
				'description' => 'Otomatik: ' . strtoupper( $defaults[ $var ] ),
				'section'     => $section,
			) ) );
		}
	}

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
	$fonts = '--font-heading:' . fj_font_stack( get_theme_mod( 'fj_font_heading', 'system-sans' ) ) . ';'
		. '--font-body:' . fj_font_stack( get_theme_mod( 'fj_font_body', 'system-sans' ) ) . ';'
		. '--font-link:' . fj_font_stack( get_theme_mod( 'fj_font_link', 'inherit' ) ) . ';'
		. '--font-button:' . fj_font_stack( get_theme_mod( 'fj_font_button', 'inherit' ) ) . ';';

	$light = fj_palette_css( 'light' );
	$dark  = fj_palette_css( 'dark' );

	$css  = ':root{' . $fonts . $light . '}';
	$css .= '@media (prefers-color-scheme:dark){:root:not([data-theme]){' . $dark . '}}';
	$css .= ':root[data-theme=dark]{' . $dark . '}';
	$css .= ':root[data-theme=light]{' . $light . '}';
	return $css;
}

/* ---------- Canlı önizleme (Özelleştir ekranı) ---------- */
function fj_customize_preview_js() {
	$cfg = array();
	foreach ( array( 'light', 'dark' ) as $mode ) {
		foreach ( array_keys( fj_color_fields() ) as $var ) {
			$cfg[] = array( 'id' => fj_color_setting_id( $mode, $var ), 'mode' => $mode, 'var' => $var );
		}
	}
	wp_enqueue_script( 'fj-customizer-preview', get_template_directory_uri() . '/assets/js/customizer-preview.js', array( 'customize-preview' ), FJ_VERSION, true );
	wp_localize_script( 'fj-customizer-preview', 'fjColorCfg', $cfg );
}
add_action( 'customize_preview_init', 'fj_customize_preview_js' );

/** Koyu/açık renk bölümü açılınca önizlemeyi o temaya geçir. */
function fj_customize_controls_js() {
	wp_add_inline_script( 'customize-controls', "
(function(api){
	api.bind('ready',function(){
		['light','dark'].forEach(function(m){
			api.section('fj_colors_'+m,function(section){
				section.expanded.bind(function(open){ if(open){ api.previewer.send('fj-mode',m); } });
			});
		});
	});
})(wp.customize);" );
}
add_action( 'customize_controls_enqueue_scripts', 'fj_customize_controls_js' );

function fj_print_head_script() {
	$mode = get_theme_mod( 'fj_default_mode', 'auto' );
	?>
<script>(function(){try{var m=localStorage.getItem('fj-theme')||'<?php echo esc_js( $mode ); ?>';if(m==='auto'){m=matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light'}document.documentElement.setAttribute('data-theme',m)}catch(e){}})();</script>
	<?php
}
add_action( 'wp_head', 'fj_print_head_script', 0 );
