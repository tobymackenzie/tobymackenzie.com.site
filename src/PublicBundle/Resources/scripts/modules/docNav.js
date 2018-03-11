// global document, window
import {addListener as _addListener} from '../ua/dom.js';
import {baseCut as _cutsMustard} from '../ua/mustardCut.js';

var _docMain = document.getElementById('docMain');
var _math = window.Math;
if(_cutsMustard && _docMain && _math && window.scrollTo && _docMain.setAttribute){
	var _de = (document.compatMode === 'CSS1Compat' ? document.documentElement : document.body);
	//-@ http://stackoverflow.com/a/18284182/1139122
	var _wHeight = (typeof window.innerHeight !== 'undefined'
		? function(){
			return window.innerHeight;
		}
		: function(){
			return _de.clientHeight;
		}
	);
	var _getXPos = (typeof window.pageXOffset !== 'undefined'
		? function(){
			return window.pageXOffset;
		}
		: function(){
			return _de.scrollLeft;
		}
	);
	var _getYPos = (typeof window.pageYOffset !== 'undefined'
		? function(){
			return window.pageYOffset;
		}
		: function(){
			return _de.scrollTop;
		}
	);
	var _getElYPos = function(_el){
		//-@ http://stackoverflow.com/a/26230989/1139122
		var _vpTop = _el.getBoundingClientRect().top;
		var _docTop = _getYPos();
		var _clientTop = document.clientTop || document.body.clientTop || 0;
		return _math.round(_vpTop + _docTop - _clientTop);
	};
	var _reqAnimationFrame = window.requestAnimationFrame || function(_cb){ setTimeout(_cb, 16); };
	var _scrollTo = function(_pos, _cb){
		var _current = _getYPos();
		var _x = _getXPos();
		if(_current !== _pos){
			var _up = _current > _pos;
			var _dist = (_up ? _current - _pos : _pos - _current); //-- total
			_dist = _math.ceil(_dist / 10); //-- step: distance total / 150ms total / 16ms step, rounded to 10 because FF math seemed to result in noticable overshooting.  Regardless of browser, it often ends up overshooting, especially when animating over longer distances
			if(_up){
				_dist *= -1;
			}
			var _compare = (_up
				? function(_a, _b){
					return _a > _b;
				}
				: function(_a, _b){
					return _a < _b;
				}
			);
			var _cbDo = function(){
				var _new = _current + _dist;
				if(_compare(_current, _pos)){
					_current = _new;
				}else{
					_current = _pos;
				}
				window.scrollTo(_x, _current);
				if(_current !== _pos){
					_cbAnimationFrame();
				}else{
					if(_cb){
						_cb();
					}
				}
			};
			//-@ http://creativejs.com/resources/requestanimationframe/
			var _cbAnimationFrame = function(){
				_reqAnimationFrame(_cbDo);
			};
			_cbAnimationFrame();
		}else{
			_cb();
		}
	};
	var _docNavItems = [
		{
			link: document.getElementById('main-link')
			,focus: _docMain
		}
		,{
			link: document.getElementById('bottom-link')
			,focus: document.getElementById('bottom')
			,target: function(){
				return _de.scrollHeight - _wHeight();
			}
		}
		,{
			link: document.getElementById('top-link')
			,focus: document.getElementById('top')
			,target: function(){
				return 0;
			}
		}
	];
	var _attachListeners = function(_opts){
		_addListener(_opts.focus, 'blur', function(){
			_opts.focus.removeAttribute('tabindex');
		});
		_addListener(_opts.link, 'click', function(_event){
			var _pos = (_opts.target ? _opts.target() : _getElYPos(_opts.focus));
			_scrollTo(_pos, function(){
				_opts.focus.setAttribute('tabindex', -1);
				_opts.focus.focus();
			});
			_event.preventDefault();
		});
	};
	for(var _i = 0, _length = _docNavItems.length; _i < _length; ++_i){
		_attachListeners(_docNavItems[_i]);
	}
}
