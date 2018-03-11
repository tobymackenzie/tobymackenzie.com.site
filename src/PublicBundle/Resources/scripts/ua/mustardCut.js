// global document, window
/*--mustard cut
- block pre `addEventListener` browsers
- block opera mini because 'bottom/top' fails in operamini and konami code is unusable
*/
export var baseCut = (
	document.addEventListener
	&& !(window.operamini && ({}).toString.call(window.operamini) === '[object OperaMini]')
);
