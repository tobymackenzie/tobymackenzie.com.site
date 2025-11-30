import * as docNav from './modules/appNav.js';
import * as konamiCode from './modules/konamiCode.js';
import * as code from './modules/code.js';
import * as extra from './modules/extra.js';
import * as theme from './modules/theme.js';

//--cache proxy service worker
//	if(navigator.serviceWorker && /^https/.test(window.location.protocol)
//&& /localhost$/.test(window.location.hostname)
//	){
//		navigator.serviceWorker.register('/_proxy-sw.js', {scope: '/'});
//	}
