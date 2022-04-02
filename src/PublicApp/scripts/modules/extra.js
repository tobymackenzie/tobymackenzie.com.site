import loadCSS from '../ua/load-css';
import loadJS from '../ua/load-js';

//--load christmas script if december or early january
//-! simple onload.  we may want to have it run repeatedly and enable / disable as necessary
if(window.Date){
	var now = new Date();
	var month = now.getMonth();
	//--load christmas
	if(
		month === 11
		|| month < 2
	){
		loadCSS('/_assets/styles/snow.css');
		loadJS('/_assets/scripts/snow.js');
	}
	if(month === 11){
		loadCSS('/_assets/styles/christmas.css');
	}
	if(month === 3 && now.getDate() === 1){
		loadJS('/_assets/scripts/aprilFools.js');
	}
}
