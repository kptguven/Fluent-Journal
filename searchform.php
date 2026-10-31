<?php if ( ! defined( 'ABSPATH' ) ) { exit; } $uid = wp_unique_id( 'search-' ); ?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $uid ); ?>">Ara:</label>
	<input type="search" id="<?php echo esc_attr( $uid ); ?>" class="search-field" placeholder="Yazılarda ara…" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	<button type="submit" class="search-submit" aria-label="Ara"><?php fj_icon( 'search' ); ?></button>
</form>
