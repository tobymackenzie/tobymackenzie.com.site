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
				_ghost.el.setAttributeNS(null, 'font-size', '48px')
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
		}
		,onResize: function(){
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
