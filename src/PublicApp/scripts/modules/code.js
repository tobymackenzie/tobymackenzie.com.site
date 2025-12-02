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
		//--add language class aliases
		var aliases = {
			'asp': 'vb',
			'conf': 'apacheconf',
			'zsh': 'bash',
			//'js': 'javascript',
			//'md': 'markdown',
			//'sh': 'bash',
			//'yml': 'yaml',
		};
		var els = document.querySelectorAll('code[class^="language-"],code[class*=" language-"]');
		for(var i = 0; i < els.length; ++i){
			var el = els[i];
			var match = el.className.match(/language-([\w-]+)/);
			if(aliases[match[1]]){
				el.className = el.className.replace('language-' + match[1], 'language-' + aliases[match[1]]);
			}
		}

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
