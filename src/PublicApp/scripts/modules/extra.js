import loadCSS from '../ua/load-css.js';
import loadJS from '../ua/load-js.js';

//--load christmas script if december or early january
//-! simple onload.  we may want to have it run repeatedly and enable / disable as necessary
if(window.Date){
	var now = new Date();
	var month = now.getMonth();
	var day = now.getDate();
	//--load christmas
	if(
		month === 11
		|| month < 2
	){
		loadCSS('/_assets/styles/snow.css');
		loadJS('/_assets/scripts/snow.js');
	}
	if(month === 9 && day >= 22){
		loadCSS('/_assets/styles/halloween.css');
		loadJS('/_assets/scripts/halloween.js');
	}
	if(month === 11){
		loadCSS('/_assets/styles/christmas.css');
	}
	if(month === 3 && day === 1){
		loadJS('/_assets/scripts/aprilFools.js');
	}
	//--message for date
	//-# too small to put in separate files
	var messages = {
		'0101': 'Happy New Year 🎉',
		'0106': 'Happy Toby Day',
		'0401': 'loof, lirpA ot emocleW',
		//-! Easter has complicated algorithm: https://stackoverflow.com/a/44480326
		'0422': '🌎 🌳',
		'0504': 'May the 4th be with you',
		'0505': 'May the 5th be with you 🌮',
		'0521': '☕️',
		'0704': 'Happy independence 🎆',
		'1031': 'Happy Halloween ',
		'1105': 'Remember, remember the 5th of November',
		'1225': 'Merry Christmas 🎄',
	};
	var year = now.getFullYear();
	var novDay = new Date(year, 10, 1).getDay();
	messages['11' + ((novDay > 4 ? 33 : 26) - novDay)] = 'Thanks 🦃';
	var dayString = ((month + 1).toString().padStart(2, '0')) + (day.toString().padStart(2, '0'));
	var messageEl;
	if(messages[dayString] && document.querySelector && (messageEl = document.querySelector('.appHeaderMessage'))){
		messageEl.innerHTML = messages[dayString];
	}
}
