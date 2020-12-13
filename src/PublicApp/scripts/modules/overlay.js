import {baseCut as _cutsMustard} from '../ua/mustardCut.js';
import {addListener, removeListener} from '../ua/dom.js';

var overlay = {
	close: function(){}
	,open: function(){}
};

if(_cutsMustard){
	var _inited = false;
	var _isOpen = false;
	var _activeEl;
	var _blockerEl;
	var _closeEl;
	var _contentEl;
	var _contentInternalEl;
	var _el;
	var _firstFocusableEl;
	var _onOverlayKeyup = function(_event){
		if(
			_event.keyCode === 27
			|| _event.key === 'Escape'
			//-! should ensure we're not in a form field
		){
			overlay.close();
		}
	};
	var _onFirstFocusableKeydown = function(_event){
		if(
			_isOpen
			&& _event.shiftKey
			&& (_event.keyCode === 9 || _event.key === 'Tab')
		){
			_event.preventDefault();
			_closeEl.focus();
		}
	};
	overlay.close = function(){
		if(_isOpen){
			_el.hidden = true;
			if(_activeEl){
				_activeEl.focus();
				_activeEl = null;
			}
			if(_contentInternalEl){
				_contentEl.removeChild(_contentInternalEl);
				_contentInternalEl = undefined;
			}
			removeListener(_el, 'keyup', _onOverlayKeyup);
			if(_firstFocusableEl){
				removeListener(_firstFocusableEl, 'keydown', _onFirstFocusableKeydown);
				_firstFocusableEl = undefined;
			}
			_isOpen = false;
		}
	};
	overlay.open = function(_content){
		if(_contentInternalEl){
			_contentEl.removeChild(_contentInternalEl);
		}
		_contentInternalEl = _content;
		_activeEl = document.activeElement;
		if(!_inited){
			_el = document.createElement('div');
			_el.classList.add('overlay');
			_el.setAttribute('tabindex', '-1');

			_contentEl = document.createElement('div');
			_contentEl.classList.add('overlayContent');
			_el.appendChild(_contentEl);
			addListener(_el, 'blur', function(){
				removeListener(_el, 'keydown', _onFirstFocusableKeydown);
			});

			_closeEl = document.createElement('button');
			_closeEl.classList.add('overlayCloseAct');
			_closeEl.innerHTML = '<b>Close overlay</b>';
			_closeEl.setAttribute('title', 'Close overlay');
			_closeEl.setAttribute('type', 'button');
			addListener(_closeEl, 'click', overlay.close);
			addListener(_closeEl, 'keydown', function(_event){
				if(
					_isOpen
					&& _firstFocusableEl
					&& !_event.shiftKey
					&& (_event.keyCode === 9 || _event.key === 'Tab')
				){
					_event.preventDefault();
					_firstFocusableEl.focus();
				}
			});
			_contentEl.appendChild(_closeEl);

			_blockerEl = document.createElement('div');
			_blockerEl.classList.add('overlayBlocker');
			addListener(_blockerEl, 'click', overlay.close);
			_el.appendChild(_blockerEl);
			document.body.appendChild(_el);
			_inited = true;
		}
		_contentEl.insertBefore(_contentInternalEl, _closeEl);
		_el.hidden = false;
		_el.focus();
		addListener(_el, 'keyup', _onOverlayKeyup);
		addListener(_el, 'keydown', _onFirstFocusableKeydown);

		_firstFocusableEl = _contentInternalEl.querySelector('a,button,input,select,textarea');
		if(_firstFocusableEl){
			addListener(_firstFocusableEl, 'keydown', _onFirstFocusableKeydown);
		}

		_isOpen = true;
	};
}
export default overlay
