import switchTheme from '../lib/@tobymackenzie/theme-switch/src/switcher.js';

switchTheme(
	{
		'main': 'Default',
		'': 'Fallback'
	},
	'<a--navi><button type="button" class="a--navsta a--nava" title="Switch theme"><a--navt>Theme</a--navt></button></a--navi>',
	'a--navi:last-child',
	'insertBefore'
);
