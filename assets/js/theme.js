/* Fluent Journal – küçük, bağımsız (jQuery yok) */
(function () {
	'use strict';
	var root = document.documentElement;
	var $ = function (id) { return document.getElementById(id); };

	/* Açık / koyu tema düğmesi */
	var themeBtn = $('theme-toggle');
	if (themeBtn) {
		themeBtn.addEventListener('click', function () {
			var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
			root.setAttribute('data-theme', next);
			try { localStorage.setItem('fj-theme', next); } catch (e) {}
		});
	}

	/* Mobil menü */
	var navBtn = $('nav-toggle'), nav = $('site-nav');
	if (navBtn && nav) {
		navBtn.addEventListener('click', function () {
			var open = navBtn.getAttribute('aria-expanded') === 'true';
			navBtn.setAttribute('aria-expanded', String(!open));
			navBtn.setAttribute('aria-label', open ? 'Menüyü aç' : 'Menüyü kapat');
			nav.classList.toggle('is-open', !open);
		});
	}

	/* Arama paneli */
	var searchBtn = $('search-toggle'), panel = $('header-search');
	if (searchBtn && panel) {
		searchBtn.addEventListener('click', function () {
			var open = searchBtn.getAttribute('aria-expanded') === 'true';
			searchBtn.setAttribute('aria-expanded', String(!open));
			panel.hidden = open;
			if (!open) { var f = panel.querySelector('input'); if (f) f.focus(); }
		});
	}

	/* Esc ile menü/arama kapat */
	document.addEventListener('keydown', function (e) {
		if (e.key !== 'Escape') return;
		if (searchBtn && panel && !panel.hidden) { panel.hidden = true; searchBtn.setAttribute('aria-expanded', 'false'); searchBtn.focus(); }
		if (navBtn && nav && nav.classList.contains('is-open')) { nav.classList.remove('is-open'); navBtn.setAttribute('aria-expanded', 'false'); navBtn.focus(); }
	});
})();
