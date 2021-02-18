/*-! based on loadCSS <https://github.com/filamentgroup/loadCSS/blob/master/src/loadCSS.js>: [c]2020 Filament Group, Inc. Licensed MIT */
var _d = window.document;
var _defaultTarget = (_d.head || _d.body);
export default function loadCSS(_src, _props, _insertBefore){
	var _link = _d.createElement('link');
	_link.href = _src;
	_link.rel = 'stylesheet';
	//-! we probably want to be able to set attributes instead of props
	if(typeof _props === 'function'){
		_props(_script);
	}else if(typeof _props === 'object' && Object.assign){
		Object.assign(_link, _props);
	}
	if(_insertBefore){
		_insertBefore.parentNode.insertBefore(_link, _insertBefore);
	}else{
		_defaultTarget.appendChild(_link);
	}
	return _link;
}
