import switchTheme from '../lib/@tobymackenzie/theme-switch/src/switcher.js';

switchTheme(
	{
		'15': 'Default',
		'stark': 'Stark',
		'hand': 'Hand',
		'console': 'Console',
		'earth': 'Earth',
		'agua': 'Agua',
		'ancient': 'Fallback',
		'': 'None',
		'Third Party': {
			'https://unpkg.com/magick.css': 'Magick CSS',
			'https://unpkg.com/papercss@1.9.2/dist/paper.min.css': 'Paper CSS',
			'https://cdn.simplecss.org/simple.min.css': 'Simple CSS',
			'https://www.w3.org/StyleSheets/Core/Chocolate': 'W3C Chocolate',
			'https://www.w3.org/StyleSheets/Core/Modernist': 'W3C Modernist'
		}
	},
	'<a--navi><button type="button" class="a--navsta a--nava" title="Switch theme"><a--navt>Theme</a--navt></button></a--navi>',
	'a--navi:last-child',
	'insertBefore'
);

//--disable ancient if we're theming
//-# since this is done in js browsers, make sure to check ancient styles, no-js in modern browsers occasionally
if(document.addEventListener){
	var ancientStyles = document.querySelector('link[rel="stylesheet"]');
	ancientStyles.rel = 'x';
}
dev: {
	if(document.querySelector){
		var navsta = document.querySelector('.a--navsta');
		if(navsta){
			navsta.accessKey = 't';
		}
	}
}
