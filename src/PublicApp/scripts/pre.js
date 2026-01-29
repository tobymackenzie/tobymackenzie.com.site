import loadJS from './ua/load-js.js';
import loadTheme from './lib/@tobymackenzie/theme-switch/src/load.js';

/*--force canonical domain from IP, or http to https
- forcing by loading script ensures that browser can actually load it, unlike with server forced redirect
- putting in pre reduces rendering / loading of http content if redirect happens
*/
var w = window;
if('location' in w && !w.TJMDEV){
	var l = location;
	var origin;
	//--attempt force canonical host if IP access
	//-# rough check if visiting via IP
	if(l.host.match(/^([\d\.]+|[\w:]+:[\w:]+)$/)){
		origin = l.protocol + '//www.tobymackenzie.com';
	}
	//--attempt force https if http
	//-# not on dev
	else if(l.protocol === 'http:' && l.host.slice(-2) !== '.t'){
		origin = 'https://' + l.host
	}
	if(origin){
		if(w.TJMFRCR){
			w.location = origin + l.pathname + l.search + l.hash;
		}else{
			w.TJMFRCR = 1;
			loadJS(origin + '/_assets/scripts/pre.js');
		}
	}
}

/*=====
theme switcher
=====*/
if(w.localStorage){
	loadTheme(
		'/_assets/styles',
		null,
		'link[media="only all"]'
	);
}
