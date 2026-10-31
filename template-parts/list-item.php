<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-item' ); ?>>
	<div class="post-item__side">
		<?php if ( has_post_thumbnail() ) : ?>
		<a class="post-item__thumb" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php the_post_thumbnail( 'fj-card', array( 'sizes' => '(min-width: 640px) 240px, 100vw' ) ); ?>
		</a>
		<?php endif; ?>
		<ul class="post-meta">
			<?php fj_meta_date(); fj_meta_author(); fj_meta_comments(); ?>
		</ul>
		<?php fj_comment_button(); ?>
	</div>
	<div class="post-item__body">
		<h2 class="post-item__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<div class="post-item__excerpt"><?php the_excerpt(); ?></div>
	</div>
</article>
