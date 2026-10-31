<?php get_header(); ?>
<header class="page-header">
	<h1 class="page-title">“<?php echo esc_html( get_search_query() ); ?>” için sonuçlar</h1>
</header>
<?php if ( have_posts() ) : ?>
	<div class="post-list">
		<?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/list-item' ); endwhile; ?>
	</div>
	<?php fj_pagination(); ?>
<?php else : ?>
	<?php get_template_part( 'template-parts/none' ); ?>
<?php endif; ?>
<?php get_footer(); ?>
