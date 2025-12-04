import {BaseClass, create as _createClass} from '../../holiday/classes.js';

var SnowView = _createClass({
	init: function(){
		this.flakes = [];
		BaseClass.prototype.init.apply(this, arguments);
		if(!this.container){
			this.container = document.querySelector('body');
		}
	}
	,props: {
		activate: function(){
			var _self = this;
			_self.determineFlakeCount();
			window.addEventListener('resize', function(){
				_self.onResize();
			});
			// var _runs = 0;
			var _lastDraw = 0;
			var _frameDuration = 1000 / this.frameRate;
			var _go = function(_time){
				if(_time - _lastDraw >= _frameDuration){
					// if(++_runs > 200){
					// 	return;
					// }
					_self.step();
					_lastDraw = _time;
				}
				window.requestAnimationFrame(_go);
			};
			_go();
		}
		,addFlake: function(){
			var _dim = this.getElDimensions();
			var _flake = {
				size: Math.ceil(Math.random() * this.maxFlakeSize)
				,x: Math.floor(Math.random() * _dim.width)
				,y: -1 * Math.floor(Math.random() * _dim.height)
			};
			this.flakes.push(_flake);
			return _flake;
		}
		,container: undefined
		,el: undefined
		,determineFlakeCount: function(){
			var _dim = this.getElDimensions();
			//-# random divisor between 5 and 20
			this.flakeCount = Math.ceil((_dim.width + _dim.height) / (5 + (15 * Math.random())));

			if(this.flakeCount > this.flakes.length){
				for(var _i = 0, _end = this.flakeCount - this.flakes.length; _i < _end; ++_i){
					this.addFlake();
				}
			}else if(this.flakeCount < this.flakes.length){
				for(var _i = 0, _end = this.flakes.length - this.flakeCount; _i < _end; ++_i){
					this.removeFlake();
				}
			}
			return this;
		}
		,flakeCount: 1
		,flakes: undefined
		,getElDimensions: function(){
			return {
				width: this.el.offsetWidth
				,height: this.el.offsetHeight
			};
		}
		,onResize: function(){
			this.determineFlakeCount();
		}
		,removeFlake: function(){
			return this.flakes.pop();
		}
		,step: function(){
			for(var _i = 0; _i < this.flakes.length; ++_i){
				var _flake = this.flakes[_i];
				this.stepFlake(_flake);
			}
			return this;
		}
		,stepFlake: function(_flake){
			var _dim = this.getElDimensions();
			var _xMove = Math.floor(Math.random() * (5 * 2) - 5 + 0.5);
			var _yMove = Math.floor(Math.random() * (10 * 2) - 10) + 10;
			_flake.x += _xMove;
			_flake.y += _yMove;
			if(_flake.x < 0 || _flake.x > _dim.width){
				_flake.x += _xMove * -2;
			}
			if(_flake.y > _dim.height){
				_flake.y = 0;
			}
			return this;
		}

		//--config
		,frameRate: 12
		,maxFlakeSize: 2
		,name: 'SnowView'
	}
});
export {SnowView};
