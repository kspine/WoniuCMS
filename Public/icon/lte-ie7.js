/* Use this script if you need to support IE 7 and IE 6. */

window.onload = function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'icomoon\'">' + entity + '</span>' + html;
	}
	var icons = {
			'icon-quill' : '&#xe001;',
			'icon-thumbs-up' : '&#xe000;',
			'icon-thumbs-up-2' : '&#xe002;',
			'icon-cloud' : '&#xe003;',
			'icon-arrow-up-right' : '&#xe004;',
			'icon-arrow-right' : '&#xe005;',
			'icon-feed' : '&#xe006;',
			'icon-html5' : '&#xe007;',
			'icon-css3' : '&#xe008;',
			'icon-chrome' : '&#xe009;',
			'icon-firefox' : '&#xe00a;',
			'icon-IE' : '&#xe00b;',
			'icon-opera' : '&#xe00c;',
			'icon-safari' : '&#xe00d;',
			'icon-users' : '&#xe00e;',
			'icon-bubbles' : '&#xe00f;'
		},
		els = document.getElementsByTagName('*'),
		i, attr, html, c, el;
	for (i = 0; i < els.length; i += 1) {
		el = els[i];
		attr = el.getAttribute('data-icon');
		if (attr) {
			addIcon(el, attr);
		}
		c = el.className;
		c = c.match(/icon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
};