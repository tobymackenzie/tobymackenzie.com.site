import {create as _createClass} from '../../holiday/classes.js';
import {DOMSnowView} from './domSnowView.js';
var _parProto = DOMSnowView.prototype;
var HTMLSnowView = _createClass({
	parent: DOMSnowView
	,props: {
		createFlakeEl: function(_flake){
			if(!_flake.el){
				_flake.el = document.createElement('i');
				_flake.el.className = 'snowFlake';
				var tmp = Math.ceil(_flake.size + Math.random() * _flake.size);
				_flake.el.style.height = tmp + 'px';
				_flake.el.style.width = tmp + 'px';
				_parProto.createFlakeEl.apply(this, arguments);
			}
		}

		//==config
		,className: 'snow snow-html'
		,name: 'HTML'
	}
});
export {HTMLSnowView};
