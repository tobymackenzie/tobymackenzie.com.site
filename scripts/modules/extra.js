import {init as initHoliday} from '../holiday/holidayLoad.js';
import getNow from '../holiday/getNow.js';
import onday from '../holiday/onday.js';

if(window.Date && document.querySelector){
	var assetBase = (window.location.host.match(/github\.io|macn\.me$/i) ? '//www.tobymackenzie.com' : '') + '/_assets';
	var messageEl = document.querySelector('.a--message');
	var messageAdded = !messageEl;
	if(messageAdded){
		messageEl = document.createElement('div');
		messageEl.classList.add('a--message');
		document.querySelector('.a--head, .cardBack').appendChild(messageEl);
	}

	//--april fools
	initHoliday({
		date: '0401',
		js: assetBase + '/scripts/aprilFools.js',
	});

	//--css naked day
	if(window.TJM_THEMELOAD){
		initHoliday({
			date: '0409',
			do: function(){
				TJM_THEMELOAD('');
			},
			undo: function(){
				TJM_THEMELOAD(localStorage.getItem('tjm-theme') || '15');
			},
		});
	}

	//--halloween
	initHoliday({
		date: '1022',
		endDate: '1101',
		elSelect: '.ghostView',
		css: assetBase + '/styles/halloween.css',
		js: assetBase + '/scripts/halloween.js',
	});

	//--snow
	initHoliday({
		date: '1201',
		endDate: '0201',
		elSelect: '.snow',
		css: assetBase + '/styles/snow.css',
		js: assetBase + '/scripts/snow.js',
	});

	//--christmas
	initHoliday({
		date: '1201',
		endDate: '1231',
		css: assetBase + '/styles/christmas.css',
	});

	onday(function(){
		var now = getNow();
		var month = now.getMonth();
		var day = now.getDate();

		//--message for date
		//-! Easter has complicated algorithm: https://stackoverflow.com/a/44480326
		//-! solstices / equinoxes are complicated
		var messages = {
			'0101': 'Happy New Year 🎉',
			'0106': 'Toby M A C, yeah, you know me',
			'0107': '👨‍💻 Code for good',
			'0202': 'Happy groundhog day',
			'0216': 'Go Cleveland',
			'0229': 'Leap for joy',
			'0314': '🟢 Take it easy as π',
			'0317': '☘️  Top o\' the website to ya ☘️',
			'0330': 'Go Akron',
			'0401': 'loof ,lirpA ot emocleW',
			'0409': '<a href="https://css-naked-day.org/">Lay it bare with HTML</a>',
			'0422': '🌎 Love Mother Earth 🌳',
			'0501': '<a href="https://youtu.be/tYJxU0TKr_Y">May day. May day.</a> 💪',
			'0504': 'May the 4th be with you <a href="https://www.kent.edu/">🪦🪦🪦🪦</a>',
			'0505': 'Mayo the cinco be with you 🌮',
			'0521': 'Tea time ☕️',
			'0525': '<a href="/42">Don\'t Panic</a>', // towel day
			'0614': '🇨🇿 🏴󠁧󠁢󠁳󠁣󠁴󠁿 🇺🇸', // flag day
			'0619': 'Emancipate 💪🏾', // Juneteenth
			'0704': 'Happy independence 🎆',
			'0708': '708y says',
			'0922': 'Emancipate', // Ohio Emancipation Day
			'1031': 'Have a spooktacular Hallowe\'en',
			'1101': 'Hallow there, all',
			'1105': '<a href="https://www.youtube.com/watch?v=RS2HLC0sipA">Remember, remember the 5th of November</a>',
			'1111': 'Remember armistice, seek peace',
			'1130': '<a href="https://www.w3.org/standards/">Yay, web standards</a> 🏴󠁧󠁢󠁳󠁣󠁴󠁿', // Blue beanie day, St Andrews day
			'1224': 'Merry Christmas',
			'1225': 'Merry Christmas 🎄',
			'1231': 'Goodbye, ' + now.getFullYear() + '.  For auld lang syne.',
		};
		var year = now.getFullYear();
		var mayDay = new Date(year, 4, 1).getDay();
		messages['05' + (mayDay ? (7 - mayDay) + 8 : 8).toString().padStart(2, '0')] = 'Thanks, Mom';
		messages['05' + ((mayDay === 6 ? 37 : 30) - mayDay)] = 'Remember the fallen';
		var juneDay = new Date(year, 5, 1).getDay();
		messages['06' + ((juneDay > 0 ? 22 : 21) - juneDay)] = 'Thanks, Dad';
		var sepDay = new Date(year, 8, 1).getDay();
		messages['09' + ((sepDay > 1 ? 9 : 2) - sepDay).toString().padStart(2, '0')] = '💪';
		var octDay = new Date(year, 9, 1).getDay();
		messages['10' + ((octDay > 1 ? 16 : 9) - octDay).toString().padStart(2, '0')] = 'Happy Columbo\'s Day 🕵️‍';
		var novDay = new Date(year, 10, 1).getDay();
		messages['11' + ((novDay > 4 ? 33 : 26) - novDay)] = 'Thanks 🦃';
		messages['11' + ((novDay > 1 ? 10 : 3) - novDay).toString().padStart(2, '0')] = 'Vote 🗳. It\'s important';
		var dayString = ((month + 1).toString().padStart(2, '0')) + (day.toString().padStart(2, '0'));
		if(messages[dayString]){
			messageEl.innerHTML = messages[dayString];
			//--make sure we update for all future runs
			messageAdded = true;
		}else if(messageAdded){
			messageEl.innerHTML = 'Be excellent to each other';
		}else{
			//--random other message
			//-! add more?
			var rand = Math.floor(Math.random() * 100);
			if(rand < 10){
				messageEl.innerHTML = messages['0106'];
			}
		}
		//--wrap all with link to bxln site unless they have HTML
		if(messageEl.children.length === 0){
			messageEl.innerHTML = '<a href="//bxln2ho3.com">' + messageEl.innerHTML + '</a>';
		}
	});
}
