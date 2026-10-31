<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
<meta name="theme-color" content="<?php $fj_dark = fj_palette( 'dark' ); echo esc_attr( $fj_dark['bg'] ); ?>" media="(prefers-color-scheme: dark)">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#content">İçeriğe geç</a>

<header class="site-header">
	<div class="container header-inner">
		<div class="site-branding">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<?php $tag = is_front_page() && is_home() ? 'h1' : 'p'; ?>
				<<?php echo $tag; ?> class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></<?php echo $tag; ?>>
			<?php endif; ?>
			<?php if ( get_bloginfo( 'description' ) ) : ?>
				<p class="site-tagline"><?php bloginfo( 'description' ); ?></p>
			<?php endif; ?>
		</div>

		<nav id="site-nav" class="site-nav" aria-label="Ana menü">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'menu',
				'depth'          => 2,
				'fallback_cb'    => 'fj_menu_fallback',
			) );
			?>
		</nav>

		<div class="header-actions">
			<button type="button" class="icon-btn" id="search-toggle" aria-expanded="false" aria-controls="header-search" aria-label="Aramayı aç">
				<?php fj_icon( 'search' ); ?>
			</button>
			<button type="button" class="icon-btn" id="theme-toggle" aria-label="Açık / koyu temayı değiştir">
				<?php fj_icon( 'weather_moon', 'icon-moon' ); ?>
				<?php fj_icon( 'weather_sunny', 'icon-sun' ); ?>
			</button>
			<button type="button" class="icon-btn nav-toggle" id="nav-toggle" aria-expanded="false" aria-controls="site-nav" aria-label="Menüyü aç">
				<?php fj_icon( 'navigation', 'icon-open' ); ?>
				<?php fj_icon( 'dismiss', 'icon-close' ); ?>
			</button>
		</div>
	</div>

	<div class="header-search" id="header-search" hidden>
		<div class="container"><?php get_search_form(); ?></div>
	</div>
</header>

<div class="container layout">
<main id="content" class="content" tabindex="-1">
