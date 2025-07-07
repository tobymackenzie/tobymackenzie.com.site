import {create as _createClass} from '../../holiday/classes.js';
import {GhostView} from './ghostView.js';
var _parProto = GhostView.prototype;
var DOMGhostView = _createClass({
	parent: GhostView
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
		,addGhost: function(){
			var _ghost = _parProto.addGhost.apply(this, arguments);
			if(!_ghost.el){
				this.createGhostEl(_ghost);
				this.el.appendChild(_ghost.el);
				this.addGhostEvents(_ghost);
			}
			return _ghost;
		}
		,addGhostEvents: function(_ghost){
			_ghost.el.addEventListener('click', function(){
				_ghost.el.dataset.state = 'boo';
				setTimeout(function(){
					alert('Boo');
					_ghost.el.dataset.state = undefined;
				}, 1100);
			});
		}
		,createGhostEl: function(_ghost){
			this.positionGhostEl(_ghost);
		}
		,removeGhost: function(){
			var _ghost = _parProto.removeGhost.apply(this, arguments);
			if(_ghost.el){
				var _self = this;
				var _gone = function(){
					_self.el.removeChild(_ghost.el);
				};
				if(_ghost.el.animate){
					var anim = _ghost.el.animate({opacity: 0}, {duration: 1000, iterations: 1});
					anim.addEventListener('finish', _gone);
				}else{
					_gone();
				}
			}
			return _ghost;
		}
		,positionGhostEl: function(_ghost){}
		,step: function(){
			_parProto.step.apply(this, arguments);
			for(var _i = 0; _i < this.ghosts.length; ++_i){
				var _ghost = this.ghosts[_i];
				this.positionGhostEl(_ghost);
			}
		}

		//==config
		,className: 'ghostView ghost-dom'
		,elAttr: {}
		,name: 'DOM'
		,nodeName: 'div'
	}
});
export {DOMGhostView};
