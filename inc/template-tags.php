<?php
/**
 * Şablonlarda kullanılan yardımcı fonksiyonlar.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Okuma süresi (dakika). */
function fj_reading_minutes( $post_id = null ) {
	$content = get_post_field( 'post_content', $post_id ?: get_the_ID() );
	$words   = preg_match_all( '/\p{L}[\p{L}\p{N}\'’-]*/u', wp_strip_all_tags( strip_shortcodes( $content ) ) );
	return max( 1, (int) ceil( $words / 200 ) );
}

/** Tarih (etiketli). */
function fj_meta_date() {
	printf(
		'<li class="meta-item">%s<time datetime="%s">%s</time></li>',
		fj_icon( 'calendar_ltr', '', false ), // phpcs:ignore
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);
}

function fj_meta_author() {
	printf(
		'<li class="meta-item">%s<a class="meta-link" href="%s">%s</a></li>',
		fj_icon( 'person', '', false ), // phpcs:ignore
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_html( get_the_author() )
	);
}

function fj_meta_comments() {
	if ( ! comments_open() && ! get_comments_number() ) {
		return;
	}
	$n = (int) get_comments_number();
	printf(
		'<li class="meta-item">%s<a class="meta-link" href="%s">%s</a></li>',
		fj_icon( 'comment', '', false ), // phpcs:ignore
		esc_url( get_permalink() . '#comments' ),
		esc_html( $n ? sprintf( '%d yorum', $n ) : 'Yorum yok' )
	);
}

function fj_meta_reading() {
	if ( ! get_theme_mod( 'fj_show_reading_time', true ) ) {
		return;
	}
	printf(
		'<li class="meta-item">%s<span>%s</span></li>',
		fj_icon( 'clock', '', false ), // phpcs:ignore
		esc_html( sprintf( '%d dk okuma', fj_reading_minutes() ) )
	);
}

/** Kategori etiketleri (rozet gibi). */
function fj_category_badges( $limit = 2 ) {
	$cats = get_the_category();
	if ( ! $cats ) {
		return;
	}
	echo '<div class="badges">';
	foreach ( array_slice( $cats, 0, $limit ) as $cat ) {
		printf( '<a class="badge" href="%s">%s</a>', esc_url( get_category_link( $cat ) ), esc_html( $cat->name ) );
	}
	echo '</div>';
}

/** "Yorum yap" butonu — yalnızca yorumlara açıksa. */
function fj_comment_button( $class = 'btn btn--subtle btn--sm' ) {
	if ( ! comments_open() ) {
		return;
	}
	printf(
		'<a class="%s" href="%s">%s<span>Yorum yap</span></a>',
		esc_attr( $class ),
		esc_url( get_permalink() . '#respond' ),
		fj_icon( 'comment_add', '', false ) // phpcs:ignore
	);
}

/** Arşiv başlığındaki "Kategori:" gibi ön ekleri temizle. */
add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );

/** Özet uzunluğu ve sonu. */
add_filter( 'excerpt_length', function () { return 32; } );
add_filter( 'excerpt_more', function () { return '…'; } );

/** Menü yoksa sayfaları listele. */
function fj_menu_fallback() {
	echo '<ul class="menu">';
	wp_list_pages( array( 'title_li' => '', 'depth' => 1, 'number' => 6 ) );
	echo '</ul>';
}

/** Sayfalama. */
function fj_pagination() {
	the_posts_pagination( array(
		'mid_size'           => 1,
		'prev_text'          => fj_icon( 'arrow_left', '', false ) . '<span class="screen-reader-text">Önceki sayfa</span>', // phpcs:ignore
		'next_text'          => '<span class="screen-reader-text">Sonraki sayfa</span>' . fj_icon( 'arrow_right', '', false ), // phpcs:ignore
		'screen_reader_text' => 'Yazı sayfaları',
	) );
}
