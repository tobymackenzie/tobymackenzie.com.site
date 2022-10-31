import loadCSS from '../ua/load-css.js';
import loadJS from '../ua/load-js.js';

if(document.querySelector){
	//--fix old code classes
	//-! should do in db, need to cruft query to manage this
	var oldStyleEls = document.querySelectorAll('code[class]:not([class^="language-"])') || [];
	for(var i = 0; i < oldStyleEls.length; ++i){
		oldStyleEls[i].className = 'language-' + oldStyleEls[i].className + ' mod';
	}

	//--load prism syntax highlighting
	if(document.querySelector('code[class^="language-"],code[class*=" language-"]')){
		//--load prism
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
}
