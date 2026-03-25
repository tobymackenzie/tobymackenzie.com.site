/*-@ based on loadJS: [c]2014 @scottjehl, Filament Group, Inc. Licensed MIT */
var _d = window.document;
var _defaultTarget = (_d.head || _d.body);
export default function loadJS(_src, _props, _target, _attr){
	var _script = _d.createElement('script');
	_script.src = _src;
	_script.async = true;
	//--in dev, we want to load as module
	dev: {
		_script.type = 'module';
	}
	//-! we probably want to be able to set attributes instead of props
	// if(typeof _props === 'function'){
		// _props(_script);
	// }else
	if(typeof _props === 'object' && Object.assign){
		Object.assign(_script, _props);
	}
	// if(typeof _attr === 'object'){
		// for(var i in _attr){
			// if(_attr.hasOwnProperty(i)){
				// _script.setAttribute(i, _attr[i]);
			// }
		// }
	// }
	if(!_target){
		_target = _defaultTarget;
	}
	_target.appendChild(_script);
	return _script;
}
