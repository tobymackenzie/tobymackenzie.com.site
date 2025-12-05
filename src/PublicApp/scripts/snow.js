// import {ready as _ready} from './modules/snow/ready.js';
import {ready as _ready} from './@tobymackenzie/ready.js';
// import {CanvasSnowView as SnowView} from './modules/snow/canvasSnowView.js';
// import {HTMLPosSnowView as SnowView} from './modules/snow/htmlPosSnowView.js';
import {HTMLTransformSnowView as SnowView} from './modules/snow/htmlTransformSnowView.js';
// import {SVGSnowView as SnowView} from './modules/snow/svgSnowView.js';

if(
	document.querySelector
	&& window.requestAnimationFrame
	&& !(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches)
){
	_ready(function(){
		var _view = new SnowView();
		_view.activate();
	});
}
