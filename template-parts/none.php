<section class="notice">
	<h2 class="page-title">Henüz bir şey yok</h2>
	<?php if ( is_search() ) : ?>
		<p>Aramanızla eşleşen bir yazı bulunamadı. Farklı kelimelerle tekrar deneyin.</p>
		<?php get_search_form(); ?>
	<?php else : ?>
		<p>Burada henüz yazı yok.</p>
	<?php endif; ?>
</section>
