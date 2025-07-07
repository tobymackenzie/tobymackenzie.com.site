import {create as _createClass} from '../../holiday/classes.js';
import {HTMLSnowView} from './htmlSnowView.js';
var _parProto = HTMLSnowView.prototype;
var HTMLTransformSnowView = _createClass({
	parent: HTMLSnowView
	,props: {
		positionFlakeEl: function(_flake){
			_flake.el.style.transform = 'translate(' + _flake.x + 'px, ' + _flake.y + 'px)';
		}

		//==config
		,className: 'snow snow-html snow-html-transform'
		,name: 'HTML Transform'
	}
});
export {HTMLTransformSnowView};
