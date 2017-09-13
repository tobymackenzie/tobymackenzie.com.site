// global document, Math, _paq, window
(function(_w, _d, _math){
	'use strict';

	if(!(_w.operamini && ({}).toString.call(_w.operamini) === '[object OperaMini]')){ //--disable here because 'bottom/top' fails in operamini and konami code is unusable
		//==lib
		//--element.addEventListener
		var _addListener = (function(){
			var _nativeAddListener = _d.addEventListener || (_d.attachEvent && function(_name, _cb){ this.attachEvent('on' + _name, _cb); }) || function(_name, _cb){
				this['on' + _name] = _cb;
			};
			return function(_elm, _name, _cb, _capt){
				return _nativeAddListener.call(_elm, _name, _cb, _capt || false/*-# ff6- */);
			};
		})();
		//--event.preventDefault
		var _preventDefault = function(_event){
			if(_event.preventDefault){
				_event.preventDefault();
			}else{
				_event.returnValue = false;
			}
		};

		//==ui
		//--docNav
		(function(){
			var _docMain = _d.getElementById('docMain');
			if(_docMain && _w.scrollTo && _docMain.setAttribute){
				var _de = (_d.compatMode === 'CSS1Compat' ? _d.documentElement : _d.body);
				//-@ http://stackoverflow.com/a/18284182/1139122
				var _wHeight = (typeof _w.innerHeight !== 'undefined'
					? function(){
						return _w.innerHeight;
					}
					: function(){
						return _de.clientHeight;
					}
				);
				var _getXPos = (typeof _w.pageXOffset !== 'undefined'
					? function(){
						return _w.pageXOffset;
					}
					: function(){
						return _de.scrollLeft;
					}
				);
				var _getYPos = (typeof _w.pageYOffset !== 'undefined'
					? function(){
						return _w.pageYOffset;
					}
					: function(){
						return _de.scrollTop;
					}
				);
				var _getElYPos = function(_el){
					//-@ http://stackoverflow.com/a/26230989/1139122
					var _vpTop = _el.getBoundingClientRect().top;
					var _docTop = _getYPos();
					var _clientTop = _d.clientTop || _d.body.clientTop || 0;
					return _math.round(_vpTop + _docTop - _clientTop);
				};
				var _reqAnimationFrame = _w.requestAnimationFrame || function(_cb){ setTimeout(_cb, 16); };
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
							_w.scrollTo(_x, _current);
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
						link: _d.getElementById('main-link')
						,focus: _docMain
					}
					,{
						link: _d.getElementById('bottom-link')
						,focus: _d.getElementById('bottom')
						,target: function(){
							return _de.scrollHeight - _wHeight();
						}
					}
					,{
						link: _d.getElementById('top-link')
						,focus: _d.getElementById('top')
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
						_preventDefault(_event);
					});
				};
				for(var _i = 0, _length = _docNavItems.length; _i < _length; ++_i){
					_attachListeners(_docNavItems[_i]);
				}
			}
		})();

		//==easter eggs
		//--konami code
		(function(){
			var _current = 0;
			var _keys = [38,38,40,40,37,39,37,39,66,65];
			_addListener(_d, 'keyup', function(_event){
				var _key =  _event.which || _event.keyCode;
				if(_key === _keys[_current]){
					if(_current === 9){
						_current = 0;
						if(confirm('You win!  Want to celebrate?')){
							_w.location.href = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
						}
					}else{
						++_current;
					}
				}else{
					if(!(_current <= 2 && _key === 38)){
						_current = 0;
					}
				}
			});
		}());
	}

	//--cache proxy service worker
//	if(navigator.serviceWorker && /^https/.test(_w.location.protocol)
//&& /localhost$/.test(_w.location.hostname)
//	){
//		navigator.serviceWorker.register('/_proxy-sw.js', {scope: '/'});
//	}
}(window, document, Math));
