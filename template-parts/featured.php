<article id="post-<?php the_ID(); ?>" <?php post_class( 'featured' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
	<a class="featured__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php the_post_thumbnail( 'fj-hero', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(min-width: 1024px) 760px, 100vw' ) ); ?>
	</a>
	<?php endif; ?>
	<div class="featured__body">
		<div class="badges">
			<span class="badge badge--brand"><?php echo is_sticky() ? 'Sabit yazı' : 'En yeni'; ?></span>
		</div>
		<h2 class="featured__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<div class="featured__excerpt"><?php the_excerpt(); ?></div>
		<div class="featured__foot">
			<ul class="post-meta post-meta--inline">
				<?php fj_meta_date(); fj_meta_author(); fj_meta_comments(); ?>
			</ul>
			<a class="btn btn--primary" href="<?php the_permalink(); ?>"><span>Devamını oku</span><?php fj_icon( 'arrow_right' ); ?></a>
		</div>
	</div>
</article>
