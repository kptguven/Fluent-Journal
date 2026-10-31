/* Özelleştir ekranında renk değişikliklerini sayfa yenilenmeden gösterir. */
(function (api) {
	'use strict';
	var cfg = window.fjColorCfg || [];
	var vals = {};
	var el = document.createElement('style');
	el.id = 'fj-live-colors';
	document.head.appendChild(el);

	function render() {
		var css = { light: '', dark: '' };
		cfg.forEach(function (c) {
			if (vals[c.id]) { css[c.mode] += '--' + c.var + ':' + vals[c.id] + ';'; }
		});
		el.textContent = ':root[data-theme=light]{' + css.light + '}:root[data-theme=dark]{' + css.dark + '}';
	}

	cfg.forEach(function (c) {
		api(c.id, function (setting) {
			vals[c.id] = setting.get();
			setting.bind(function (v) { vals[c.id] = v; render(); });
		});
	});
	render();

	api.preview.bind('fj-mode', function (mode) {
		document.documentElement.setAttribute('data-theme', mode);
	});
})(wp.customize);
