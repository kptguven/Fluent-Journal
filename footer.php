<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
</main>

<?php get_sidebar(); ?>
</div><!-- .layout -->

<footer class="site-footer">
	<div class="container footer-inner">
		<div class="footer-text">
			<?php
			$text = get_theme_mod( 'fj_footer_text', '' );
			echo $text ? wp_kses_post( $text ) : '&copy; ' . esc_html( wp_date( 'Y' ) ) . ' ' . esc_html( get_bloginfo( 'name' ) );
			?>
		</div>
		<?php
		if ( has_nav_menu( 'footer' ) ) {
			wp_nav_menu( array(
				'theme_location' => 'footer',
				'container'      => 'nav',
				'container_class' => 'footer-nav',
				'container_aria_label' => 'Alt menü',
				'menu_class'     => 'menu',
				'depth'          => 1,
			) );
		}
		?>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
