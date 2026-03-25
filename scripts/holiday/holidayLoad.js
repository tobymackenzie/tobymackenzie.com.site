import {create as _createClass} from './classes.js';
import {Holiday} from './holiday.js';
import loadCSS from '../ua/load-css.js';
import loadJS from '../ua/load-js.js';

var nill = undefined;
var removeNode = function(node){
	node.parentNode.removeChild(node);
};

var HolidayLoad = _createClass({
	parent: Holiday,
	props: {
		//--selector for element to remove on cleanup
		elSelect: nill,
		//--assets to load
		css: nill,
		_css: nill,
		js: nill,
		_js: nill,

		do: function(){
			var css = this.css || this._css;
			if(typeof css === 'string'){
				this.css = loadCSS(css);
			}
			var js = this.js || this._js;
			if(typeof js === 'string'){
				this.js = loadJS(js);
			}
		},
		undo: function(){
			if(this.css){
				removeNode(this.css);
				this.css = undefined;
			}
			if(this.js){
				removeNode(this.js);
				this.js = undefined;
			}
			if(this.elSelect){
				var el = document.querySelector(this.elSelect);
				if(el){
					removeNode(el);
				}
			}
		},
	},
});
export {HolidayLoad};
export function create(props){
	return _createClass({
		parent: HolidayLoad,
		props: props,
	});
};
export function init(props){
	return create(props)();
};
