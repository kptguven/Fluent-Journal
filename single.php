<?php get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
	<header class="entry-header">
		<?php fj_category_badges( 3 ); ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<ul class="post-meta post-meta--inline">
			<?php fj_meta_date(); fj_meta_author(); fj_meta_reading(); fj_meta_comments(); ?>
		</ul>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="entry-hero">
			<?php the_post_thumbnail( 'fj-hero', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
			<?php if ( get_the_post_thumbnail_caption() ) : ?><figcaption><?php the_post_thumbnail_caption(); ?></figcaption><?php endif; ?>
		</figure>
	<?php endif; ?>

	<div class="entry-content">
		<?php
		the_content();
		wp_link_pages( array( 'before' => '<nav class="page-links" aria-label="Yazı sayfaları">', 'after' => '</nav>' ) );
		?>
	</div>

	<?php if ( has_tag() ) : ?>
	<footer class="entry-footer">
		<?php fj_icon( 'tag' ); ?>
		<?php the_tags( '<ul class="tag-list"><li>', '</li><li>', '</li></ul>' ); ?>
	</footer>
	<?php endif; ?>
</article>

<?php if ( get_theme_mod( 'fj_show_author_box', true ) && get_the_author_meta( 'description' ) ) : ?>
<aside class="author-box" aria-label="Yazar hakkında">
	<?php echo get_avatar( get_the_author_meta( 'ID' ), 72, '', '', array( 'class' => 'author-box__avatar' ) ); ?>
	<div>
		<p class="author-box__name"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author(); ?></a></p>
		<p class="author-box__bio"><?php echo esc_html( get_the_author_meta( 'description' ) ); ?></p>
	</div>
</aside>
<?php endif; ?>

<?php
the_post_navigation( array(
	'prev_text' => '<span class="nav-label">' . fj_icon( 'arrow_left', '', false ) . ' Önceki yazı</span><span class="nav-title">%title</span>', // phpcs:ignore
	'next_text' => '<span class="nav-label">Sonraki yazı ' . fj_icon( 'arrow_right', '', false ) . '</span><span class="nav-title">%title</span>', // phpcs:ignore
) );

if ( comments_open() || get_comments_number() ) {
	comments_template();
}
endwhile;
get_footer();
