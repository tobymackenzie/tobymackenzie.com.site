import loadJS from './ua/load-js.js';

/*--force canonical domain from IP, or http to https
- forcing by loading script ensures that browser can actually load it, unlike with server forced redirect
- putting in pre reduces rendering / loading of http content if redirect happens
- do we need secure box on page with this?
*/
var $canonical;
var win = window;
var doc = win.document;
var loc = 'location' in win ? win.location : null;
if(
	loc
	&& win.URL
	&& doc.querySelector
	&& ($canonical = doc.querySelector('link[rel="canonical"]'))
	&& $canonical
	&& $canonical.classList
	&& !doc.querySelector('html').classList.contains('env-dev')
	//--rough check if visiting via IP
	&& (
		loc.host.match(/^([\d\.]+|[\w:]+:[\w:]+)$/)
		|| (loc.protocol === 'http:' && $canonical.href.slice(5) === 'https')
	)
){
	var url = new URL($canonical.href);
	if(win.TJMFRCR){
		win.location = url.origin + loc.pathname + loc.search + loc.hash;
	}else{
		win.TJMFRCR = 1;
		loadJS(url.origin + '/_assets/scripts/pre.js');
	}
}
