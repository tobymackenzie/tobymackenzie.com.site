import {create as _createClass} from '../../holiday/classes.js';
import {HTMLSnowView} from './htmlSnowView.js';
var _parProto = HTMLSnowView.prototype;
var HTMLPosSnowView = _createClass({
	parent: HTMLSnowView
	,props: {
		positionFlakeEl: function(_flake){
			_flake.el.style.left = _flake.x + 'px';
			_flake.el.style.top = _flake.y + 'px';
		}

		//==config
		,className: 'snow snow-html snow-html-pos'
		,name: 'HTML Pos'
	}
});
export {HTMLPosSnowView};
