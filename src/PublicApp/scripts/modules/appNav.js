// global document, window
import {addListener as _addListener} from '../ua/dom.js';
import {baseCut as _cutsMustard} from '../ua/mustardCut.js';
import _dialog from './dialog.js';

var _appEl = document.querySelector && document.querySelector('.a--0');
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
	var _nav = document.querySelector('.a--nav');
	if(_nav){
		_nav.setAttribute('data-js', 1);
		var _navList = _nav.querySelector('.a--navl');
		if(_navList){
			var _bottomNavItem = document.createElement('a--navi');
			_bottomNavItem.innerHTML = '<a class="a--navba a--nava" href="#bottom" title="#Page bottom"><a--navt>Bottom</a--navt></a>';
			_navList.appendChild(_bottomNavItem);

			var _topNavItem = document.createElement('div');
			_topNavItem.className += ' a--navr';
			_topNavItem.innerHTML = '<a class="a--navra a--nava" href="#top" title="#Page top"><a--navt>Top</a--navt></a>';
			_appEl.appendChild(_topNavItem);
		}
	}

	/*
	--dialog nav when clicked
	*/
	if(
		window.fetch
		&& _navList
	){
		var _siteNavActionEl = _navList.querySelector('.a--navsa');
		if(_siteNavActionEl){
			var _siteNavUrl = _siteNavActionEl.getAttribute('href');
			var _siteNavEl;

			//--replace `<a>` with `<button>` since we are no longer navigating
			var _newSiteNavActionEl = document.createElement('button');
			_newSiteNavActionEl.setAttribute('type', 'button');
			_newSiteNavActionEl.classList = _siteNavActionEl.classList;
			_newSiteNavActionEl.title = _siteNavActionEl.title;
			_newSiteNavActionEl.innerHTML = _siteNavActionEl.innerHTML;
			_siteNavActionEl.parentNode.insertBefore(_newSiteNavActionEl, _siteNavActionEl);
			_siteNavActionEl.remove();
			_siteNavActionEl = _newSiteNavActionEl;

			var _openSiteNav = function(){
				_dialog.open(_siteNavEl);
			};

			//--attach and open on click
			_addListener(_siteNavActionEl, 'click', function(_event){
				_event.preventDefault();
				//-! may be easier to keep as link above and hook into history API
				if(
					_event.ctrlKey
					|| _event.metaKey
					|| _event.shiftKey
					|| (_event.button && _event.button == 1)
				){
					window.open(_siteNavUrl);
					return;
				}
				if(_siteNavEl){
					_openSiteNav();
				}else{
					var _defaultAction = function(){
						console.error(arguments[0]);
						window.location.href = _siteNavUrl;
					};
					fetch(_siteNavUrl).then(function(_response){
						if(_response.ok){
							return _response.text();
						}else{
							throw new Error('response not okay');
						}
					}).then(function(_content){
						_siteNavEl = document.createElement('div');
						//-# doctype breaks parsing in XHTML
						_siteNavEl.innerHTML = _content.replace(/<\!doctype[^>]+>/gi, '');
						_siteNavEl = _siteNavEl.querySelector('.a--snav');
						if(_siteNavEl){
							_openSiteNav();
						}else{
							throw new Error('no .a--snav in response: ' + _content);
						}
					}).catch(_defaultAction);
				}
			});
		}
	}
}
