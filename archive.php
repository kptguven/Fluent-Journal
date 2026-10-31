<?php get_header(); ?>
<header class="page-header">
	<h1 class="page-title"><?php the_archive_title(); ?></h1>
	<?php the_archive_description( '<div class="page-desc">', '</div>' ); ?>
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
