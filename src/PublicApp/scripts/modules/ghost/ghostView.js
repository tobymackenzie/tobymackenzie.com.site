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
			this.ghostCount = (_dim.width + _dim.height) > 1000 ? 2 : 1;
			// this.ghostCount = 1;

			if(this.ghostCount > this.ghosts.length){
				for(var _i = 0, _end = this.ghostCount - this.ghosts.length; _i < _end; ++_i){
					this.addGhost();
				}
			}else if(this.ghostCount > this.ghosts.length){
				for(var _i = 0, _end = this.ghostCount - this.ghosts.length; _i < _end; ++_i){
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
			var _dim = this.getElDimensions();
			_ghost.xSpeed += Math.round(Math.random() * this.randAmount + .15) - Math.floor(this.randAmount);
			if(_ghost.xSpeed < this.minSpeed){
				_ghost.xSpeed = this.minSpeed;
			}else if(_ghost.xSpeed > this.maxSpeed){
				_ghost.xSpeed = this.maxSpeed;
			}
			if(_ghost.x > _dim.width + this.offScreenPadding){
				_ghost.x = -1 * this.offScreenPadding;
			}else if(_ghost.x < -1 * this.offScreenPadding){
				_ghost.x = _dim.width + this.offScreenPadding;
			}else{
				_ghost.x += _ghost.xSpeed;
			}
			_ghost.ySpeed += Math.round(Math.random() * this.randAmount + .15) - Math.floor(this.randAmount);
			if(_ghost.ySpeed < this.minSpeed){
				_ghost.ySpeed = this.minSpeed;
			}else if(_ghost.ySpeed > this.maxSpeed){
				_ghost.ySpeed = this.maxSpeed;
			}
			if(_ghost.y > _dim.height + this.offScreenPadding){
				_ghost.y = -1 * this.offScreenPadding;
			}else if(_ghost.y < -1 * this.offScreenPadding){
				_ghost.y = _dim.height + this.offScreenPadding;
			}else{
				_ghost.y += _ghost.ySpeed;
			}
			// console.log(_ghost);
			return this;
		},

		//--config
		frameRate: 12,
		minSpeed: -3,
		maxSpeed: 3,
		name: 'GhostView',
		offScreenPadding: 10,
		randAmount: 1.8,
	}
});
export {GhostView};