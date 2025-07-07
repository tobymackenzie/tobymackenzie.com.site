import {BaseClass, create as _createClass} from './classes.js';
import onnextday from './onnextday.js';

var nill = undefined;
var Holiday = _createClass({
	init: function(){
		BaseClass.prototype.init.apply(this, arguments);
		this.handle();
	},
	props: {
		//--config
		date: nill,
		do: function(){},
		endDate: nill,
		undo: function(){},

		//--internal
		active: false,
		timeout: nill,

		activate: function(){
			var _this = this;
			if(!_this.active){
				_this.do();
				if(!_this.timeout){
					_this.timeout = onnextday(function(){
						_this.handle();
					});
				}
				_this.active = true;
			}
		},
		deactivate: function(){
			if(this.active){
				this.undo();
				if(this.timeout){
					clearTimeout(this.timeout);
				}
				this.active = false;
			}
		},
		handle: function(date){
			var is = this.is(date);
			if(!this.active && is){
				this.activate(date);
			}else if(this.active && !is){
				this.deactivate(date);
			}
		},
		is: function(now){
			if(this.date){
				now = Holiday.formatDate(now || new Date());
				var date = Holiday.formatDate(this.date);
				if(this.endDate){
					var endDate = Holiday.formatDate(this.endDate);
					if(endDate < date){
						return now >= date || now <= endDate;
					}else{
						return now >= date && now <= endDate;
					}
				}else{
					return date === now;
				}
			}else{
				return false;
			}
		},
	},
	statics: {
		formatDate: function(date){
			if(date instanceof Date){
				var m = date.getMonth() + 1;
				var d = date.getDate();
				if(d < 10){
					d = '0' + d.toString();
				}
				date = m + d.toString();
			}
			return parseInt(date, 10);
		},
	},
});

export {Holiday};
export function create(props){
	return _createClass({
		parent: Holiday,
		props: props,
	});
};
export function init(props){
	return create(props)();
};
