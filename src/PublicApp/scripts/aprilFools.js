function getRandItem(arr){
	return arr[Math.floor(Math.random() * arr.length)]
};
//-@ https://stackoverflow.com/a/17265031
function replaceStringInNode(node, find, replacement){
	if(node.nodeType === 3 && node.textContent){
		if(typeof find === 'string'){
			find = new RegExp(find, 'gi');
		}
		node.textContent = node.textContent.replace(find, function(match){
			var str = '';
			for(var i = 0; i < match.length; ++i){
				var char = match.charAt(i);
				var replaceChar = replacement.charAt(i);
				if(char.match(/[A-Z]/)){
					str += replaceChar.toUpperCase();
				}else if(char.match(/[a-z]/)){
					str += replaceChar.toLowerCase();
				}else{
					str += replaceChar;
				}
			}
			if(match.length < replacement.length){
				str += replacement.substr(match.length);
			}
			return str;
		});
	}else{
		for(var sub in node.childNodes){
			replaceStringInNode(node.childNodes[sub], find, replacement);
		}
	}
}
function replaceStringsInNode(node, arr){
	for(var i = 0; i < arr.length; ++i){
		replaceStringInNode(node, arr[i][0], arr[i][1]);
	}
}

//==main
var doc = document;
if(doc.querySelector){
	var main = function($el){
		if(!$el){
			$el = doc.body;
		}

		//--funny text replacement
		replaceStringsInNode($el, [
			['tobymackenzie.com', 'macn.me'],
			['toby', 'boby'],
			['mackenzie', 'mackerby'],
			['god', 'dog'],
			[/\boh boy\b/gi, 'oh buoy'],
			[/\bi /gi, 'I, Boby, '],
			//--tech
			['blog', 'web-pipe'],
			['internet', 'information superhighway'],
			[/\bweb developer\b/gi, 'webmaster of the universe'],
			[/\bweb[- ]+development\b/gi, 'webmastering'],
			//--archaic
			[/\benjoy\b/gi, 'enjoyst'],
			[/\bhello\b/gi, 'ahoy'],
			[/\bmate\b/gi, 'matey'],
			[/\byou\b/gi, 'thou'],
			[/\byour\b/gi, 'thy'],
			//--brit
			[/\bmy\b/gi, 'me'],
			[/\bmom\b/gi, 'mum'],
			[/\bsomething\b/gi, 'summat'],
			//--canuck
			['about', 'aboot'],
			[/\bout\b/gi, 'oot'],
			//--fic
			[/\bcool\b/gi, 'shiny'],
			['friend', 'droog'],
			//--places
			['Akron', 'Rubber City'],
			['Cleveland', 'Bomb City'],
			['Cuyahoga Falls', 'Caucasian Falls'],
			['Ohio', 'Great River'],
			['Winking Lizard', 'Blinking Wizard'],
			['winking', 'blinking'],
			['lizard', 'wizard'],
			//--scots
			[/\bcannot\b/gi, 'cannae'],
			[/\bcan't\b/gi, 'cannae'],
			[/\bhaven't\b/gi, 'huvnae'],
			[/\blittle\b/gi, 'wee'],
			[/\boy\b/gi, 'lad'],
			[/\bgirl\b/gi, 'lass'],
			['know', 'ken'],
			[/\bsmall\b/gi, 'wee'],
			[/\bno\b/gi, 'nae'],
			[/\bwon't\b/gi, 'winnae'],
			[/\byes\b/gi, 'aye'],
		]);

		//--funny intro / outro
		var $firstP = doc.querySelector('p');
		if($firstP){
			var phrases = [
				'Here ye, here ye!  ',
				'Abraham Lincoln once said:  ',
				'Ahoy matey!  ',
				'All your base are belong to us!  ',
				// 'As you wish.  ',
				'Attention, web surfer!  ',
				'Beltalowda!  ',
				'Bork bork bork!  ',
				'Breaking news.  ',
				'Comrade!  ',
				'Don\'t be ignorant.  ',
				'Don\'t panic.  ',
				'Dear web surfer, ',
				'Dude!  ',
				'Foolish person.  ',
				'Friends!  Romans!  Lend me your eyes!  ',
				'Get your facts straight.  ',
				'Heed!  ',
				'Hello, [insert name here].  ',
				'Hi!  I\'m Boby Mackerby.  You may remember me from such websites as Cogneato.com and How to Mothball Your Battleship.  ',
				'Humanoids, hark!  ',
				'I do declare ',
				'I reckon ',
				'It was the best of times.  It was the blurst of times.  ',
				'Ladies and gentlemen!  ',
				'Listen up.  ',
				'Make haste!  ',
				'Mark me!  ',
				'My fellow humanoids!  ',
				'My, how the turntables.  ',
				'No way!  ',
				'Now who could that be?  ',
				'Righteous!  ',
				'On a chilly night in April, ',
				'Once upon a time ',
				'Oy!  ',
				'Smeg!  ',
				'This is god.  ',
				'Warning!  Warning!  ',
				'What up, ma nerds?  ',
				'You fool!  ',
				'You\'re not going to believe this!  ',
				'Welcome, gentle reader.  ',
			];
			$firstP.childNodes[0].textContent = getRandItem(phrases) + $firstP.childNodes[0].textContent;
		}
		var $main = doc.querySelector('main .m--0:last-of-type');
		if(!$main){
			$main = doc.querySelector('main');
		}
		if($main){
			var phrases = [
				'All hail King Bluetooth!',
				'Always know where your towel is.',
				'And he trailed off into the sunset.',
				'And just like that, as quickly as it had started, it was finished.',
				'And no one was the wiser.',
				'And now we\'re in a dilly of a pickle.',
				'And so it goes.',
				'And that is the word of god.',
				'And that will be our downfall.',
				'And they were never heard from again.',
				'April Fools!',
				'As you can see, birds aren\'t real.',
				'Be excellent to each other, and party on dudes!',
				'Buck up, little camper:  We\'ll beat that slope… together.',
				'But hey, it could be verse.',
				'Fin',
				'For the greater good!',
				'Good day!  I said good day!',
				'Got ya!',
				'Heed this message, and you will be saved.',
				'It\'s thoroughly pizzled.',
				'Me want cookies.',
				'My balls are now in your court.',
				'Now go do that voodoo that you do.',
				'Now go save the world!',
				'Now go, you fool!',
				'Now you try.',
				'Prove me wrong.',
				'Reporting live from macn.me, this is Boby Mackerby, signing off.',
				'Seize the day!',
				'So long, and thanks for all the fish.',
				'So suck it.',
				'That is the wampeter of my karass.',
				'That\'s all folks!',
				'That\'s the news from Lake Wobegon, where the women won\'t find you handsome, but at least they\'ll find you handy.',
				'The end is nigh!',
				'Think about it.',
				'This information is classified.',
				'This message will self destruct.',
				'Thus, the answer to life, the universe, and everything is 42.',
				'To arms!',
				'Use this information wisely.',
				'Vague but exciting.',
				'Who knew?',
				'Ya ken?',
				'You\'ll see.',
				'Your hero in arms, Boby Mackerby',
				'Your turn.',
			];
			var $p = document.createElement('p');
			$p.innerText = getRandItem(phrases);
			var $footer = $main.querySelector('footer');
			var done = false;
			var i = 0;
			while(!done && ++i < 10){
				try{
					$main.insertBefore($p, $footer);
				}catch(e){
					$footer = $footer.parentNode;
				}
			}
		}
	};
	if(doc.readyState !== 'loading'){
		main();
	}else{
		doc.addEventListener('DOMContentLoaded', main);
	}
}
