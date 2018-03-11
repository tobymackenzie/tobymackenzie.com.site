import * as docNav from './modules/docNav.js';
import * as konamiCode from './modules/konamiCode.js';

//--cache proxy service worker
//	if(navigator.serviceWorker && /^https/.test(window.location.protocol)
//&& /localhost$/.test(window.location.hostname)
//	){
//		navigator.serviceWorker.register('/_proxy-sw.js', {scope: '/'});
//	}
