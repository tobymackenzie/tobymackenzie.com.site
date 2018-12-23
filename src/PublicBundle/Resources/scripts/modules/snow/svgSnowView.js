import {create as _createClass} from './classes.js';
import {DOMSnowView} from './domSnowView.js';
var _parProto = DOMSnowView.prototype;
var _ns = 'http://www.w3.org/2000/svg';
var SVGSnowView = _createClass({
	parent: DOMSnowView
	,props: {
		activate: function(){
			if(!this.el){
				this.el = document.createElementNS(_ns, this.nodeName);
				this.el.classList.add('snow', 'snow-svg');
			}
			this.fixCanvDimensions();
			_parProto.activate.apply(this, arguments);
		}
		,createFlakeEl: function(_flake){
			if(!_flake.el){
				_flake.el = document.createElementNS(_ns, 'circle');
				_flake.el.classList.add('snowFlake');
				_flake.el.r.baseVal.value = _flake.size;
				_parProto.createFlakeEl.apply(this, arguments);
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
		,positionFlakeEl: function(_flake){
			_flake.el.cx.baseVal.value = parseFloat(_flake.x);
			_flake.el.cy.baseVal.value = parseFloat(_flake.y);
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
export {SVGSnowView};
