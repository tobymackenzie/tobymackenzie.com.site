// global window
import {addListener as _addListener} from '../ua/dom.js';
import {baseCut as _cutsMustard} from '../ua/mustardCut.js';
import loadJS from '../ua/load-js.js';

if(_cutsMustard){
	var _current = 0;
	var _keys = [38,38,40,40,37,39,37,39,66,65];
	var _timeout;
	var _reset = function(){
		_current = 0;
	};
	_addListener(document, 'keyup', function(_event){
		var _key =  _event.which || _event.keyCode;
		clearTimeout(_timeout);
		if(_key === _keys[_current]){
			if(_current === 9){
				_reset();
				loadJS('/_assets/scripts/aprilFools.js');
				alert('Be excellent to each other… and party on, dudes');
			}else{
				_timeout = setTimeout(_reset, 1200);
				++_current;
			}
		}else if(!(_current <= 2 && _key === 38)){
			_reset();
		}
	});
}
