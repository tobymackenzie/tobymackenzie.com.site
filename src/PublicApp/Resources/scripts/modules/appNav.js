// global document, window
import {addListener as _addListener} from '../ua/dom.js';
import {baseCut as _cutsMustard} from '../ua/mustardCut.js';

var _appEl = document.querySelector && document.querySelector('.app');
if(
	_cutsMustard
	&& document.createElement
	&& _appEl
	&& _appEl.innerHTML
){
	/*
	--insert top / bottom nav actions
	adding here to reduce markup on page load
	*/
	var _topNavItem = document.createElement('div');
	_topNavItem.classList.add('appRestartNav');
	_topNavItem.innerHTML = '<a class="appRestartAction appNavAction" href="#top" id="top-link"><span class="appNavItemText"><span class="appNavItemHash">#</span>Page top</span></a>';
	_appEl.appendChild(_topNavItem);
	var _navList = document.querySelector('.appNavList');
	if(_navList){
		var _bottomNavItem = document.createElement('li');
		_bottomNavItem.classList.add('appNavItem');
		_bottomNavItem.innerHTML = '<a class="appBottomAction appNavAction" href="#bottom" id="bottom-link"><span class="appNavItemText"><span class="appNavItemHash">#</span>Page bottom</span></a>';
		_navList.appendChild(_bottomNavItem);
	}
}
