<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<aside class="sidebar" aria-label="Yan çubuk">
	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	<?php else :
		$args = array(
			'before_widget' => '<section class="widget">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		);
		the_widget( 'WP_Widget_Search', array( 'title' => 'Ara' ), $args );
		the_widget( 'WP_Widget_Recent_Posts', array( 'title' => 'Son yazılar', 'number' => 5 ), $args );
		the_widget( 'WP_Widget_Categories', array( 'title' => 'Kategoriler', 'count' => 1 ), $args );
	endif; ?>
</aside>
