import {mergeIn} from './mergeIn.js';

function createConstructor(_parent){
	var _constructor = function(){
		if(this instanceof _constructor){
			if(typeof this.init === 'function'){
				this.init.apply(this, arguments);
			}else{
				_parent.apply(this, arguments);
			}
		}else{
			var _this = createPrototype(_constructor);
			if(_this.init && _this.init.apply){
				_this.init.apply(_this, arguments)
			}
			return _this;
		}
	};
	return _constructor;
};
function createPrototype(_class){
	//--create noop function and attach prototype so that we won't run constructor of class
	var _tmp = function(){};
	_tmp.prototype = _class.prototype;
	var _proto = new _tmp();
	return _proto;
};
function BaseClass(){};
BaseClass.prototype.init = function(_opts){
	if(typeof _opts === 'object'){
		mergeIn(this, _opts);
	}
};
export {BaseClass};
export function create(_opts){
	if(!_opts){
		_opts = {};
	}
	var _parent;
	switch(typeof _opts.parent){
		case 'function':
		case 'object':
			_parent = _opts.parent;
		break;
		case 'string':
			_parent = window[_parent];
		break;
		default:
			_parent = BaseClass;
		break;
	}

	//--create constructor and prototype
	var _class = createConstructor(_parent);
	//-!! should these be in create constructor?
	var _proto = createPrototype(_parent);
	//--set prototype on class
	_class.prototype = _proto;
	//--fix constructor
	_proto.constructor = _class;

	//--set properties
	var _props = _opts.props || _opts.properties;
	if(_props){
		mergeIn(_proto, _props);
	}
	if(typeof _opts.init === 'function'){
		_proto.init = _opts.init;
	}

	//--set statics
	mergeIn(_class, _parent);
	if(typeof _opts.statics === 'object'){
		mergeIn(_class, _opts.statics);
	}
	return _class;
};
