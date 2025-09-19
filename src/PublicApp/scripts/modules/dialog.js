import {baseCut as _cutsMustard} from '../ua/mustardCut.js';
import {addListener, removeListener} from '../ua/dom.js';

var dialog = {
	close: function(){}
	,open: function(){}
};

if(_cutsMustard && window.HTMLDialogElement){
	var _inited = false;
	var _isOpen = false;
	var _closeEl;
	var _el;
	var _contentInternalEl;
	dialog.close = function(){
		if(_isOpen){
			_el.close();
			if(_contentInternalEl){
				_el.removeChild(_contentInternalEl);
				_contentInternalEl = undefined;
			}
			_isOpen = false;
		}
	};
	dialog.open = function(_content){
		if(_contentInternalEl){
			_el.removeChild(_contentInternalEl);
		}
		_contentInternalEl = _content;
		if(!_inited){
			_el = document.createElement('dialog');
			_el.closedBy = 'any';
			_el.classList.add('dialogContent');

			_closeEl = document.createElement('button');
			_closeEl.classList.add('closeAct');
			_closeEl.innerHTML = '<b>Close dialog</b>';
			_closeEl.setAttribute('title', 'Close dialog');
			_closeEl.setAttribute('type', 'button');
			addListener(_closeEl, 'click', dialog.close);
			_el.appendChild(_closeEl);

			document.body.appendChild(_el);
			_inited = true;
		}
		_el.insertBefore(_contentInternalEl, _closeEl);
		_el.showModal();

		_isOpen = true;
	};
}
export default dialog
