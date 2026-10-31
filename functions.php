<?php
/**
 * Fluent Journal – tema ayarları.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'FJ_VERSION', '1.0.0' );

require_once get_template_directory() . '/inc/icons.php';
require_once get_template_directory() . '/inc/fonts.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/seo.php';

function fj_setup() {
	load_theme_textdomain( 'fluent-journal', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array( 'height' => 48, 'width' => 200, 'flex-width' => true, 'flex-height' => true ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_editor_style( 'assets/css/editor.css' );

	add_image_size( 'fj-card', 640, 440, true );
	add_image_size( 'fj-hero', 1280, 560, true );

	register_nav_menus( array(
		'primary' => 'Ana menü (üst)',
		'footer'  => 'Alt menü',
	) );
}
add_action( 'after_setup_theme', 'fj_setup' );

function fj_content_width() {
	$GLOBALS['content_width'] = 720;
}
add_action( 'after_setup_theme', 'fj_content_width', 0 );

function fj_widgets_init() {
	register_sidebar( array(
		'name'          => 'Yan çubuk',
		'id'            => 'sidebar-1',
		'description'   => 'Boş bırakırsanız arama, son yazılar ve kategoriler otomatik gösterilir.',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'fj_widgets_init' );

function fj_scripts() {
	wp_enqueue_style( 'fluent-journal', get_stylesheet_uri(), array(), FJ_VERSION );
	wp_add_inline_style( 'fluent-journal', fj_dynamic_css() );

	$fonts_url = fj_google_fonts_url();
	if ( $fonts_url ) {
		wp_enqueue_style( 'fj-google-fonts', $fonts_url, array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters
	}

	wp_enqueue_script( 'fluent-journal', get_template_directory_uri() . '/assets/js/theme.js', array(), FJ_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'fj_scripts' );

/** Editör (Gutenberg) için de aynı renk/yazı tipi değişkenleri. */
function fj_editor_vars() {
	wp_add_inline_style( 'wp-block-library', fj_dynamic_css() );
}
add_action( 'enqueue_block_editor_assets', 'fj_editor_vars' );

/** Çok sık kullanılmayan varsayılan "wp-embed" betiğini kaldır. */
function fj_dequeue_embed() {
	wp_dequeue_script( 'wp-embed' );
}
add_action( 'wp_footer', 'fj_dequeue_embed' );

/** Yorum formu: Fluent stili butonlar ve Türkçe metinler. */
function fj_comment_form_defaults( $d ) {
	$d['title_reply']         = 'Yorum yap';
	$d['title_reply_to']      = '%s kişisine yanıt ver';
	$d['cancel_reply_link']   = 'Yanıtı iptal et';
	$d['label_submit']        = 'Yorumu gönder';
	$d['class_submit']        = 'btn btn--primary';
	$d['submit_button']       = '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>';
	$d['comment_notes_before'] = '<p class="comment-notes">E-posta adresiniz yayınlanmayacak. Zorunlu alanlar * ile işaretlidir.</p>';
	return $d;
}
add_filter( 'comment_form_defaults', 'fj_comment_form_defaults' );
