import {create as _createClass} from './classes.js';
import {DOMGhostView} from './domGhostView.js';
var _parProto = DOMGhostView.prototype;
var _ns = 'http://www.w3.org/2000/svg';
var SVGGhostView = _createClass({
	parent: DOMGhostView
	,props: {
		activate: function(){
			if(!this.el){
				this.el = document.createElementNS(_ns, this.nodeName);
				this.el.classList.add('ghostView', 'ghost-svg');
			}
			this.fixCanvDimensions();
			_parProto.activate.apply(this, arguments);
		}
		,createGhostEl: function(_ghost){
			if(!_ghost.el){
				_ghost.el = document.createElementNS(_ns, 'text');
				_ghost.el.classList.add('snowGhost');
				_ghost.el.setAttributeNS(null, 'font-size', this.ghostSize + 'px')
				_ghost.el.appendChild(document.createTextNode('👻'));
				_parProto.createGhostEl.apply(this, arguments);
			}
		}
		,fixCanvDimensions: function(){
			var _dim = this.getElDimensions();
			this.el.setAttribute('viewbox', '0 0 ' + _dim.width + ' ' + _dim.height);
		}
		,getElDimensions: function(){
			return this.el.getBoundingClientRect();
		},
		_moveGhostData: function(_ghost){
			//-# overide for SVG bottom baseline coordinates
			var _dim = this.getElDimensions();
			if(_ghost.x > _dim.width + this.offScreenPadding){
				_ghost.x = -1 * (this.offScreenPadding + this.ghostSize);
			}else if(_ghost.x < -1 * (this.offScreenPadding + this.ghostSize)){
				_ghost.x = _dim.width + this.offScreenPadding;
			}else{
				_ghost.x += _ghost.xSpeed;
			}
			if(_ghost.y > _dim.height + this.offScreenPadding + this.ghostSize){
				_ghost.y = -1 * (this.offScreenPadding + this.ghostSize);
			}else if(_ghost.y < -1 * (this.offScreenPadding + this.ghostSize)){
				_ghost.y = _dim.height + this.offScreenPadding + this.ghostSize;
			}else{
				_ghost.y += _ghost.ySpeed;
			}
		},
		onResize: function(){
			_parProto.onResize.apply(this, arguments);
			this.fixCanvDimensions();
		}
		,positionGhostEl: function(_ghost){
			_ghost.el.setAttributeNS(null, 'x', parseFloat(_ghost.x));
			_ghost.el.setAttributeNS(null, 'y', parseFloat(_ghost.y));
			// _ghost.el.x.baseVal.value = parseFloat(_ghost.x);
			// _ghost.el.y.baseVal.value = parseFloat(_ghost.y);
		}

		//==config
		,className: null
		,elAttr: {
			'aria-hidden': 'aria-hidden'
			,viewbox: '0 0 100 100'
			,xmlns: _ns
		}
		,name: 'SVG'
		,nodeName: 'svg'
	}
});
export {SVGGhostView};
