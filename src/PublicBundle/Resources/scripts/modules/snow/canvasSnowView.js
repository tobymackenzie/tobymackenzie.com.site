import {create as _createClass} from './classes.js';
import {SnowView} from './snowView.js';
var _parProto = SnowView.prototype;
var CanvasSnowView = _createClass({
	parent: SnowView
	,init: function(){
		_parProto.init.apply(this, arguments);
	}
	,props: {
		activate: function(){
			if(!this.el){
				this.el = document.createElement('canvas');
			}
			if(this.el.getContext){
				this.offscreenCanvasEl = document.createElement('canvas');
				this.offscreenContext = this.offscreenCanvasEl.getContext('2d');
				this.canvContext = this.el.getContext('2d');
				this.el.className = 'snow snow-canv';
				this.container.appendChild(this.el);

				this.fixCanvDimensions();
				_parProto.activate.apply(this, arguments);
				return true;
			}else{
				return false;
			}
		}
		,canvContext: undefined
		,clearRect: function(){
			this.offscreenContext.clearRect(0, 0, this.el.width, this.el.height)
		}
		,onResize: function(){
			_parProto.onResize.apply(this, arguments);
			this.fixCanvDimensions();
		}
		,step: function(){
			_parProto.step.apply(this, arguments);
			this.clearRect();
			this.offscreenContext.fillStyle = 'white';
			this.offscreenContext.beginPath();
			for(var _i = 0; _i < this.flakes.length; ++_i){
				var _flake = this.flakes[_i];
				this.offscreenContext.moveTo(_flake.x, _flake.y);
				this.offscreenContext.arc(_flake.x, _flake.y, _flake.size, 0, Math.PI * 2, true);
			}
			this.offscreenContext.fill();
			this.canvContext.putImageData(this.offscreenContext.getImageData(0, 0, this.el.width, this.el.height), 0, 0);
		}
		,fixCanvDimensions: function(){
			this.el.width = this.offscreenCanvasEl.width = Math.max(window.innerWidth, document.documentElement.clientWidth);
			this.el.height = this.offscreenCanvasEl.height = Math.max(window.innerHeight, document.documentElement.clientHeight);
		}
		,offscreenCanvasEl: undefined
		,offscreenContext: undefined

		//==config
		,name: 'Canvas'
	}
});
export {CanvasSnowView};
