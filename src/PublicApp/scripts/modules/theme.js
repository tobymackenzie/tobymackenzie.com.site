import switchTheme from '../lib/@tobymackenzie/theme-switch/src/switcher.js';

switchTheme(
	{
		'15': 'Default',
		'stark': 'Stark',
		'hand': 'Hand',
		'ancient': 'Fallback',
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

//--disable ancient if we're themeing
//-# not for default, since we want to ensure no-js browsers work fine
if(window.localStorage && localStorage.getItem('tjm-theme')){
	var ancientStyles = document.querySelector('link[rel="stylesheet"]');
	ancientStyles.rel = 'x';
}
