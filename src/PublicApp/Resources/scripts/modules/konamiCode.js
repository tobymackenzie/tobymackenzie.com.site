// global window
import {addListener as _addListener} from '../ua/dom.js';
import {baseCut as _cutsMustard} from '../ua/mustardCut.js';

if(_cutsMustard){
	var _current = 0;
	var _keys = [38,38,40,40,37,39,37,39,66,65];
	_addListener(document, 'keyup', function(_event){
		var _key =  _event.which || _event.keyCode;
		if(_key === _keys[_current]){
			if(_current === 9){
				_current = 0;
				if(confirm('You win!  Want to celebrate?')){
					window.location.href = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
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
}
