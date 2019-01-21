import {ready as _ready} from './modules/snow/ready.js';
import {CanvasSnowView} from './modules/snow/canvasSnowView.js';

if(
	document.querySelector
	&& window.requestAnimationFrame
	&& !(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches)
){
	_ready(function(){
		var _view = new CanvasSnowView();
		_view.activate();
	});
}
