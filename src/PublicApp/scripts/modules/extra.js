import loadCSS from '../ua/load-css';
import loadJS from '../ua/load-js';

//--load christmas script if december or early january
//-! simple onload.  we may want to have it run repeatedly and enable / disable as necessary
if(window.Date){
	var now = new Date();
	//--load christmas
	if(
		now.getMonth() === 11
		|| (now.getMonth() === 0 && now.getDate() <= 15)
	){
		loadCSS('/_assets/styles/christmas.css');
		loadJS('/_assets/scripts/christmas.js');
	}
}
