import loadJS from './ua/load-js.js';

/*--force canonical domain from IP, or http to https
- forcing by loading script ensures that browser can actually load it, unlike with server forced redirect
- putting in pre reduces rendering / loading of http content if redirect happens
*/
var w = window;
if('location' in w && !w.TJMDEV){
	var canonicalHost = 'www.tobymackenzie.com'
	var l = location;
	var origin, url;
	//--attempt force canonicalHost if IP access
	//-# rough check if visiting via IP
	if(l.host !== canonicalHost && l.host.match(/^([\d\.]+|[\w:]+:[\w:]+)$/)){
		origin = l.protocol + '//' + canonicalHost;
		url = origin + l.pathname + l.search + l.hash;
	}
	//--attempt force https if http
	//-# not on dev
	else if(l.protocol === 'http:' && l.host.slice(-2) !== '.t'){
		origin = 'https://' + l.host
		url = origin + l.pathname + l.search + l.hash;
	}
	if(url){
		if(w.TJMFRCR){
			w.location = url;
		}else{
			w.TJMFRCR = 1;
			loadJS(origin + '/_assets/scripts/pre.js');
		}
	}
}
