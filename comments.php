<?php
if ( post_password_required() ) { return; }
?>
<section id="comments" class="comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$n = get_comments_number();
			echo esc_html( 1 === (int) $n ? '1 yorum' : sprintf( '%d yorum', $n ) );
			?>
		</h2>
		<ol class="comment-list">
			<?php wp_list_comments( array( 'style' => 'ol', 'short_ping' => true, 'avatar_size' => 48 ) ); ?>
		</ol>
		<?php the_comments_navigation( array( 'prev_text' => 'Eski yorumlar', 'next_text' => 'Yeni yorumlar' ) ); ?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments">Bu yazıda yorumlar kapatılmıştır.</p>
	<?php endif; ?>

	<?php comment_form(); ?>
</section>
