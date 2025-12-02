import switchTheme from '../lib/@tobymackenzie/theme-switch/src/switcher.js';

switchTheme(
	{
		'15': 'Default',
		'stark': 'Stark',
		'ancient': 'Fallback'
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
