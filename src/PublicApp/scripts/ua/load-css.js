/*-! based on loadCSS <https://github.com/filamentgroup/loadCSS/blob/master/src/loadCSS.js>: [c]2020 Filament Group, Inc. Licensed MIT */
var _d = window.document;
var _defaultTarget = (_d.head || _d.body);
export default function loadCSS(_src, _props, _insertBefore){
	var _link = _d.createElement('link');
	_link.href = _src;
	_link.rel = 'stylesheet';
	var _media = 'all';
	var _onload;
	//-! we probably want to be able to set attributes instead of props
	if(typeof _props === 'function'){
		_props(_script);
	}else if(typeof _props === 'object' && Object.assign){
		if(_props.media){
			_media = _props.media;
		}
		if(_props.onload){
			_onload = _props.onload;
		}
		Object.assign(_link, _props);
	}
	_link.media = 'only x';
	_link.onload = function(){
		_link.media = _media;
		if(_onload){
			_onload();
		}
	}
	if(_insertBefore){
		_insertBefore.parentNode.insertBefore(_link, _insertBefore);
	}else{
		_defaultTarget.appendChild(_link);
	}
	return _link;
}
