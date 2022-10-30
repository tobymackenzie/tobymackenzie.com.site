import loadCSS from '../ua/load-css.js';
import loadJS from '../ua/load-js.js';

if(document.querySelector && document.querySelector('code[class^="language-"],code[class*=" language-"]')){
	window.Prism = window.Prism || {};
	Prism.manual = true;
	loadJS('/_assets/scripts/prismjs/components/prism-core.js', {
		onload: function(){
			loadJS('/_assets/scripts/prismjs/plugins/autoloader/prism-autoloader.js', {
				onload: function(){
					Prism.highlightAll();
				},
			});
		},
	});
}
