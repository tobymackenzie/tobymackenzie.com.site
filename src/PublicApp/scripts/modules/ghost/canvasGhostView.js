import {create as _createClass} from './classes.js';
import {GhostView} from './ghostView.js';
var _parProto = GhostView.prototype;
var CanvasGhostView = _createClass({
	parent: GhostView,
	init: function(){
		_parProto.init.apply(this, arguments);
		if(typeof this.darkMode === 'undefined'){
			this.darkMode = (window.matchMedia && window.matchMedia('prefers-color-scheme: dark'));
		}
	},
	props: {
		activate: function(){
			if(!this.el){
				this.el = document.createElement('canvas');
			}
			if(this.el.getContext){
				this.offscreenCanvasEl = document.createElement('canvas');
				this.offscreenContext = this.offscreenCanvasEl.getContext('2d');
				this.canvContext = this.el.getContext('2d');
				this.el.className = 'ghostView ghost-canv';
				this.container.appendChild(this.el);
				this.fixCanvDimensions();
				_parProto.activate.apply(this, arguments);
				return true;
			}else{
				return false;
			}
		},
		canvContext: undefined,
		clearRect: function(){
			this.offscreenContext.clearRect(0, 0, this.el.width, this.el.height)
		},
		darkMode: undefined,
		image: undefined,
		onResize: function(){
			_parProto.onResize.apply(this, arguments);
			this.fixCanvDimensions();
		},
		step: function(){
			_parProto.step.apply(this, arguments);
			this.clearRect();
			if(this.darkMode){
				this.offscreenContext.globalAlpha = 0.65;
			}
			this.offscreenContext.font = this.ghostSize + 'px serif';
			for(var _i = 0; _i < this.ghosts.length; ++_i){
				var _ghost = this.ghosts[_i];
				this.offscreenContext.fillText('👻', _ghost.x, _ghost.y);
			}
			this.canvContext.putImageData(this.offscreenContext.getImageData(0, 0, this.el.width, this.el.height), 0, 0);
		},
		fixCanvDimensions: function(){
			this.el.width = this.offscreenCanvasEl.width = Math.max(window.innerWidth, document.documentElement.clientWidth);
			this.el.height = this.offscreenCanvasEl.height = Math.max(window.innerHeight, document.documentElement.clientHeight);
		},
		offscreenCanvasEl: undefined,
		offscreenContext: undefined,

		//==config
		name: 'Canvas',
	}
});
export {CanvasGhostView};
