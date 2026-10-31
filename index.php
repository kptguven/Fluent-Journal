<?php
/**
 * Ana sayfa / yazı listesi: ilk yazı büyük (öne çıkan), diğerleri liste.
 * Yapışkan (sticky) yazı varsa WordPress onu otomatik en üste koyar → o öne çıkar.
 */
get_header();
?>
<?php if ( have_posts() ) : ?>
	<div class="post-list">
	<?php
	while ( have_posts() ) :
		the_post();
		global $wp_query;
		if ( is_home() && ! is_paged() && 0 === $wp_query->current_post ) {
			get_template_part( 'template-parts/featured' );
		} else {
			get_template_part( 'template-parts/list-item' );
		}
	endwhile;
	?>
	</div>
	<?php fj_pagination(); ?>
<?php else : ?>
	<?php get_template_part( 'template-parts/none' ); ?>
<?php endif; ?>
<?php get_footer(); ?>
