<?php get_header(); ?>
<section class="notice">
	<h1 class="page-title">Sayfa bulunamadı</h1>
	<p>Aradığınız sayfa taşınmış ya da silinmiş olabilir. Aşağıdan arama yapabilir veya ana sayfaya dönebilirsiniz.</p>
	<?php get_search_form(); ?>
	<p><a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php fj_icon( 'home' ); ?><span>Ana sayfaya dön</span></a></p>
</section>
<?php get_footer(); ?>
