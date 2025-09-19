import {init as initHoliday} from '../holiday/holiday.js';
import loadCSS from '../ua/load-css.js';
import loadJS from '../ua/load-js.js';
import onday from '../holiday/onday.js';

//--load christmas script if december or early january
//-! simple onload.  we may want to have it run repeatedly and enable / disable as necessary
if(window.Date && document.querySelector){
	var assetBase = (window.location.host.match(/github\.io|macn\.me$/i) ? '//www.tobymackenzie.com' : '');
	var messageEl = document.querySelector('.appHeaderMessage');
	var messageAdded = !messageEl;
	if(messageAdded){
		messageEl = document.createElement('div');
		messageEl.classList.add('appHeaderMessage');
		document.querySelector('.appHeaderContent, .cardBack').appendChild(messageEl);
	}

	var removeNode = function(node){
		node.parentNode.removeChild(node);
	};

	//--april fools
	initHoliday({
		date: '0401',
		do: function(){
			this.js = loadJS(assetBase + '/_assets/scripts/aprilFools.js');
		},
		undo: function(){
			if(this.js){
				removeNode(this.js);
				this.js = undefined;
				//-! ideally would undo text content changes
			}
		},
	});

	//--snow
	initHoliday({
		date: '1201',
		endDate: '0201',
		do: function(){
			this.css = loadCSS(assetBase + '/_assets/styles/snow.css');
			this.js = loadJS(assetBase + '/_assets/scripts/snow.js');
		},
		undo: function(){
			if(this.css){
				removeNode(this.css);
				this.css = undefined;
			}
			if(this.js){
				removeNode(this.js);
				this.js = undefined;
				var el = document.querySelector('.snow');
				if(el){
					removeNode(el);
				}
			}
		},
	});

	//--christmas
	initHoliday({
		date: '1201',
		endDate: '1231',
		do: function(){
			this.css = loadCSS(assetBase + '/_assets/styles/christmas.css');
		},
		undo: function(){
			if(this.css){
				removeNode(this.css);
				this.css = undefined;
			}
		},
	});

	onday(function(){
		var now = new Date();
		var month = now.getMonth();
		var day = now.getDate();

		//--message for date
		//-# too small to put in separate files
		//-! Easter has complicated algorithm: https://stackoverflow.com/a/44480326
		//-! solstices / equinoxes are complicated
		var messages = {
			'0101': 'Happy New Year 🎉',
			'0106': 'Happy Toby Day',
			'0107': '👨‍💻 Code for good',
			'0202': 'Happy groundhog day',
			'0216': 'Go Cleveland',
			'0229': 'Happy Leap Day',
			'0314': '🟢 Take it easy as π',
			'0317': '☘️  Top o\' the website to ya ☘️',
			'0330': 'Go Akron',
			'0401': 'loof ,lirpA ot emocleW',
			'0422': '🌎 Be excellent to Earth 🌳',
			'0501': 'May day. May day. 💪',
			'0504': 'May the 4th be with you 🪦🪦🪦🪦',
			'0505': 'Mayo the cinco be with you 🌮',
			'0521': 'Tea time ☕️',
			'0614': '🇨🇿 🏴󠁧󠁢󠁳󠁣󠁴󠁿 🇺🇸',
			'0619': 'Emancipate 💪🏾', // Juneteenth
			'0704': 'Happy independence 🎆',
			'0708': '708y says',
			'0922': 'Emancipate', // Ohio Emancipation Day
			'1031': 'Have a spooktacular Hallowe\'en',
			'1101': 'Hallow there, all',
			'1105': '<a href="https://www.youtube.com/watch?v=RS2HLC0sipA">Remember, remember the 5th of November</a>',
			'1111': 'Remember armistice, seek peace',
			'1130': 'Yay, web standards 🏴󠁧󠁢󠁳󠁣󠁴󠁿', // Blue beanie day, St Andrews day
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
			messageEl.innerHTML = '<a href="//bxln2ho3.com">Be excellent to each other</a>';
		}
	});
}
