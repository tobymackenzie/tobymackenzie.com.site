import {BaseClass, create as _createClass} from './classes.js';

var GhostView = _createClass({
	init: function(){
		this.ghosts = [];
		BaseClass.prototype.init.apply(this, arguments);
		if(!this.container){
			this.container = document.querySelector('body');
		}
	},
	props: {
		activate: function(){
			var _self = this;
			_self.determineGhostCount();
			window.addEventListener('resize', function(){
				_self.onResize();
			});
			var _lastDraw = 0;
			var _frameDuration = 1000 / this.frameRate;
			var _go = function(_time){
				if(_time - _lastDraw >= _frameDuration){
					_self.step();
					_lastDraw = _time;
				}
				window.requestAnimationFrame(_go);
			};
			_go();
		},
		addGhost: function(){
			var _ghost = {
				x: 0,
				xSpeed: 1,
				y: 0,
				ySpeed: 1,
			};
			this.ghosts.push(_ghost);
			return _ghost;
		},
		container: undefined,
		el: undefined,
		determineGhostCount: function(){
			var _dim = this.getElDimensions();
			this.ghostCount = Math.round((_dim.width + _dim.height) / 600);
			if(this.ghostCount > this.ghosts.length){
				for(var _i = 0, _end = this.ghostCount - this.ghosts.length; _i < _end; ++_i){
					this.addGhost();
				}
			}else if(this.ghostCount < this.ghosts.length){
				for(var _i = 0, _end = this.ghosts.length - this.ghostCount; _i < _end; ++_i){
					this.removeGhost();
				}
			}
			return this;
		},
		ghostCount: 1,
		ghost: undefined,
		getElDimensions: function(){
			return {
				width: this.el.offsetWidth,
				height: this.el.offsetHeight,
			};
		},
		_moveGhostData: function(_ghost){
			var _dim = this.getElDimensions();
			if(_ghost.x > _dim.width + this.offScreenPadding){
				_ghost.x = -1 * (this.offScreenPadding + this.ghostSize);
			}else if(_ghost.x < -1 * (this.offScreenPadding + this.ghostSize)){
				_ghost.x = _dim.width + this.offScreenPadding;
			}else{
				_ghost.x += _ghost.xSpeed;
			}
			if(_ghost.y > _dim.height + this.offScreenPadding){
				_ghost.y = -1 * (this.offScreenPadding + this.ghostSize);
			}else if(_ghost.y < -1 * (this.offScreenPadding + this.ghostSize)){
				_ghost.y = _dim.height + this.offScreenPadding;
			}else{
				_ghost.y += _ghost.ySpeed;
			}
	},
		onResize: function(){
			this.determineGhostCount();
		},
		removeGhost: function(){
			return this.ghosts.pop();
		},
		step: function(){
			for(var _i = 0; _i < this.ghosts.length; ++_i){
				var _ghost = this.ghosts[_i];
				this.stepGhost(_ghost);
			}
			return this;
		},
		stepGhost: function(_ghost){
			_ghost.xSpeed += Math.round(Math.random() * this.randAmount + .15) - Math.floor(this.randAmount);
			if(_ghost.xSpeed < -1 * this.maxSpeed){
				_ghost.xSpeed = -1 * this.maxSpeed;
			}else if(_ghost.xSpeed > this.maxSpeed){
				_ghost.xSpeed = this.maxSpeed;
			}
			_ghost.ySpeed += Math.round(Math.random() * this.randAmount + .15) - Math.floor(this.randAmount);
			if(_ghost.ySpeed < -1 * this.maxSpeed){
				_ghost.ySpeed = -1 * this.maxSpeed;
			}else if(_ghost.ySpeed > this.maxSpeed){
				_ghost.ySpeed = this.maxSpeed;
			}
			this._moveGhostData(_ghost);
			return this;
		},

		//--config
		frameRate: 12,
		ghostSize: 48,
		maxSpeed: 3,
		name: 'GhostView',
		offScreenPadding: 1,
		randAmount: 1.8,
	}
});
export {GhostView};
