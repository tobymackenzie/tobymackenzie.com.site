import {ready as _ready} from './modules/snow/ready.js';
// import {CanvasGhostView as GhostView} from './modules/ghost/canvasGhostView.js';
import {SVGGhostView as GhostView} from './modules/ghost/svgGhostView.js';

if(
	document.querySelector
	&& window.requestAnimationFrame
	&& !(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches)
){
	_ready(function(){
		var _view = new GhostView();
		_view.activate();
	});
}
