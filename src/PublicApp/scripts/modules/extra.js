import loadCSS from '../ua/load-css.js';
import loadJS from '../ua/load-js.js';

//--load christmas script if december or early january
//-! simple onload.  we may want to have it run repeatedly and enable / disable as necessary
if(window.Date){
	var assetBase = (window.location.host.match(/github\.io|macn\.me$/i) ? '//www.tobymackenzie.com' : '');
	var now = new Date();
	var month = now.getMonth();
	var day = now.getDate();
	//--load christmas
	if(
		month === 11
		|| month < 2
	){
		loadCSS(assetBase + '/_assets/styles/snow.css');
		loadJS(assetBase + '/_assets/scripts/snow.js');
	}
	if(month === 9 && day >= 22){
		loadCSS(assetBase + '/_assets/styles/halloween.css');
		loadJS(assetBase + '/_assets/scripts/halloween.js');
	}
	if(month === 11){
		loadCSS(assetBase + '/_assets/styles/christmas.css');
	}
	if(month === 3 && day === 1){
		loadJS(assetBase + '/_assets/scripts/aprilFools.js');
	}

	//--message for date
	if(document.querySelector){
		var messageEl = document.querySelector('.appHeaderMessage');
		var messageAdded = !messageEl;
		if(messageAdded){
			messageEl = document.createElement('div');
			messageEl.classList.add('appHeaderMessage');
			document.querySelector('.appHeaderContent, .cardBack').appendChild(messageEl);
		}
		//-# too small to put in separate files
		//-! Easter has complicated algorithm: https://stackoverflow.com/a/44480326
		//-! solstices / equinoxes are complicated
		var messages = {
			'0101': 'Happy New Year 🎉',
			'0106': 'Happy Toby Day',
			'0107': '👨‍💻 Code',
			'0202': 'Happy groundhog day',
			'0216': 'Go Cleveland',
			'0314': '🟢 Take it easy as π',
			'0317': '☘️  Top o\' the website to ya ☘️',
			'0330': 'Go Akron',
			'0401': 'loof ,lirpA ot emocleW',
			'0422': '🌎 Be excellent to Earth 🌳',
			'0501': 'May day. May day. 💪',
			'0504': 'May the 4th be with you 🪦🪦🪦🪦',
			'0505': 'May the 5th be with you 🌮',
			'0521': 'Tea time ☕️',
			'0614': '🇨🇿 🏴󠁧󠁢󠁳󠁣󠁴󠁿 🇺🇸',
			'0619': 'Emancipation 💪🏾', // Juneteenth
			'0704': 'Happy independence 🎆',
			'0708': '708y says',
			'0922': 'Happy emancipation',
			'1031': 'Have a spooktacular Halloween',
			'1105': '<a href="https://www.youtube.com/watch?v=RS2HLC0sipA">Remember, remember the 5th of November</a>',
			'1111': 'Thanks armistace',
			'1130': 'Yay, web standards 🏴󠁧󠁢󠁳󠁣󠁴󠁿', // Blue beanie day, St Andrews day
			'1224': 'Merry Christmas',
			'1225': 'Merry Christmas 🎄',
			'1231': 'Goodbye, ' + now.getFullYear() + '. Hello, Hogmanay',
		};
		var year = now.getFullYear();
		var mayDay = new Date(year, 4, 1).getDay();
		messages['05' + (mayDay ? (7 - mayDay) + 8 : 8).toString().padStart(2, '0')] = 'Thanks, Mom';
		messages['05' + ((mayDay === 6 ? 37 : 30) - mayDay)] = 'Happy Memorial Day';
		var juneDay = new Date(year, 5, 1).getDay();
		messages['06' + ((juneDay > 0 ? 22 : 21) - juneDay)] = 'Thanks, Dad';
		var sepDay = new Date(year, 8, 1).getDay();
		messages['09' + ((sepDay > 1 ? 9 : 2) - sepDay).toString().padStart(2, '0')] = '💪';
		var octDay = new Date(year, 9, 1).getDay();
		messages['10' + ((octDay > 1 ? 16 : 9) - octDay).toString().padStart(2, '0')] = 'Happy Columbo\'s Day 🕵️‍♀️';
		var novDay = new Date(year, 10, 1).getDay();
		messages['11' + ((novDay > 4 ? 33 : 26) - novDay)] = 'Thanks 🦃';
		messages['11' + ((novDay > 1 ? 10 : 3) - novDay).toString().padStart(2, '0')] = 'Vote 🗳. It\'s important';
		var dayString = ((month + 1).toString().padStart(2, '0')) + (day.toString().padStart(2, '0'));
		if(messages[dayString]){
			messageEl.innerHTML = messages[dayString];
		}else if(messageAdded){
			messageEl.innerHTML = 'Be excellent to each other';
		}
	}
}
