import {create as _createClass} from '../../holiday/classes.js';
import {SnowView} from './snowView.js';
var _parProto = SnowView.prototype;
var DOMSnowView = _createClass({
	parent: SnowView
	,props: {
		activate: function(){
			if(!this.el){
				this.el = document.createElement(this.nodeName);
			}
			if(this.className){
				this.el.className = this.className;
			}
			for(var _key in this.elAttr){
				if(this.elAttr.hasOwnProperty(_key)){
					this.el.setAttribute(_key, this.elAttr[_key]);
				}
			}
			this.container.appendChild(this.el);
			_parProto.activate.apply(this, arguments);
			return true;
		}
		,addFlake: function(){
			var _flake = _parProto.addFlake.apply(this, arguments);
			if(!_flake.el){
				this.createFlakeEl(_flake);
				this.el.appendChild(_flake.el);
			}
			return _flake;
		}
		,createFlakeEl: function(_flake){
			this.positionFlakeEl(_flake);
		}
		,removeFlake: function(){
			var _flake = _parProto.removeFlake.apply(this, arguments);
			if(_flake.el){
				this.el.removeChild(_flake.el);
			}
			return _flake;
		}
		,positionFlakeEl: function(_flake){}
		,step: function(){
			_parProto.step.apply(this, arguments);
			for(var _i = 0; _i < this.flakes.length; ++_i){
				var _flake = this.flakes[_i];
				this.positionFlakeEl(_flake);
			}
		}

		//==config
		,className: 'snow snow-dom'
		,elAttr: {}
		,name: 'DOM'
		,nodeName: 'div'
	}
});
export {DOMSnowView};
