export function mergeIn(){
	var _target = arguments[0];
	for(var _i = 1; _i < arguments.length; ++_i){
		if(typeof arguments[_i] === 'object'){
			for(var _objKey in arguments[_i]){
				if(arguments[_i].hasOwnProperty(_objKey)){
					_target[_objKey] = arguments[_i][_objKey];
				}
			}
		}
	}
	return _target;
};
