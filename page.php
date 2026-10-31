<?php get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header>
	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="entry-hero"><?php the_post_thumbnail( 'fj-hero', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?></figure>
	<?php endif; ?>
	<div class="entry-content">
		<?php
		the_content();
		wp_link_pages( array( 'before' => '<nav class="page-links" aria-label="Sayfalar">', 'after' => '</nav>' ) );
		?>
	</div>
</article>
<?php
if ( comments_open() || get_comments_number() ) {
	comments_template();
}
endwhile;
get_footer();
