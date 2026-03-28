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
			var childNode = node.childNodes[sub];
			if((childNode.nodeName || '').toLowerCase() === 'code'){
				continue;
			}
			replaceStringInNode(childNode, find, replacement);
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
			['scientist', 'sciencetist'],
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
			//--we want first real `<p>`
			if($firstP.firstChild.nodeName.toLowerCase() === 'm--img'){
				var $p2 = doc.querySelector('p:nth-of-type(2)');
				if($p2){
					$firstP = $p2;
				}
			}
			var phrases = [
				'Here ye, here ye!',
				'Abraham Lincoln once said:',
				'Ahoy matey!',
				'All your base are belong to us!',
				'And now for something completely different.',
				// 'As you wish.',
				'Attention, web surfer!',
				'Beltalowda!',
				'Bork bork bork!',
				'Breaking news.',
				'Comrade!',
				'Don\'t be ignorant.',
				'Don\'t panic.',
				'Dear web surfer,',
				'Dude!',
				'Foolish person.',
				'Friends!  Romans!  Lend me your eyes!',
				'Get your facts straight.',
				'Heed!',
				'Hi!  I\'m Boby Mackerby.  You may remember me from such websites as Cogneato.com and How to Mothball Your Battleship.',
				'Hola muchachos.  Buenos dias.  ¿Còmo te llamas?  Me nombre es Boby.  Mucho gusto.  El queso està viejo y podrido.  ¿Dònde està el sanitario?',
				'Humanoids, hark!',
				'I am completely operational, and all my circuits are functioning perfectly.',
				'I do declare',
				'I implore you',
				'I reckon',
				'It was a day just like any other.  The sun was shining and the flowers were blooming.  Clouds were moving in from the West and there was a faint whiff of ozone, showing signs of April showers.  I couldn\'t help but feeling pleased, yet hopeful.  All of a sudden',
				'It was the best of times.  It was the blurst of times.',
				'Ladies and gentlemen!',
				'Listen up.',
				'Make haste!',
				'Mark me!',
				'My dearest web surfer,',
				'My fellow humanoids!',
				'My, how the turntables.',
				'No way!',
				'Now who could that be?',
				'Righteous!',
				'On a chilly night in April,',
				'Once upon a time',
				'Oy!',
				'Smeg!',
				'This is god.',
				'This is the darkest timeline.',
				'This just in:',
				'Understand this:',
				'Warning!  Warning!',
				'What up, ma nerds?',
				'You fool!',
				'You\'re not going to believe this!',
				'Welcome, gentle reader.',
			];
			var phrase = getRandItem(phrases);
			switch(phrase.slice(-1)){
				case ':':
				case '.':
				case '?':
				case '!':
					phrase += '  ';
				break;
				default:
					phrase += ' ';
				break;
			}
			$firstP.childNodes[0].textContent = phrase + $firstP.childNodes[0].textContent;
		}
		var $main = doc.querySelector('main .m--0:last-of-type');
		if(!$main){
			$main = doc.querySelector('main');
		}
		if($main){
// Potato, potato.
// It was a simpler time.
// And with his dying breath
// Viva LA revolution

			var phrases = [
				'All hail King Bluetooth!',
				'Always know where your towel is.',
				'And he trailed off into the sunset.',
				'And no one was the wiser.',
				'And now we\'re in a dilly of a pickle.',
				'And now, if you\'ll excuse me, I need to poop.',
				'And so it goes.',
				'And so the sun went down on another day, and our heroes went back to their ordinary lives.  Until they ride again.  May we all be glad that they are out there, protecting us from these heinous villains.',
				'And that is the word of god.',
				'And that will be our downfall.',
				'And they were never heard from again.',
				'April Fools!',
				'April showers bring Mayflowers.  Mayflowers bring Pilgrims.',
				'As you can see, birds aren\'t real.',
				'Be excellent to each other, and party on dudes!',
				'Buck up, little camper:  We\'ll beat that slope… together.',
				'But hey, it could be verse.',
				'Can you believe it?',
				'Can you dig it?',
				'Could it be more obvious?',
				'Cowabunga!',
				'Excellent!',
				'For the greater good.',
				'Good day!  I said good day!',
				'Good Dog.',
				'Got ya!',
				'Happy trails to you, until we meet again.',
				'Heed this message, and you will be saved.',
				'I could\'ve done it too if it weren\'t for those meddling kids.',
				'I would think you, of all people, would understand this.',
				'It is the will of the people.',
				'It\'s thoroughly pizzled.',
				'Me want cookies.',
				'May we ride again.',
				'My balls are now in your court.',
				'Folks, this unhappy news may shake you to the bone with fear of death, but my proprietary serum can give you a fighting chance.  Guaranteed to make you find what once was lost, see what once you were blinded from.  It slices.  It dices.  It comes for great prices.  Just form an orderly line and for 42 rupees, you too can have a chance at salvation.',
				'Now go do that voodoo that you do.',
				'Now go, go, for the good of the city.',
				'Now go save the world!',
				'Now go, you fool!',
				'Now I\'m not going to lie:  We are all in deep trouble, and if my preceding statements have not convinced you thusly, you are lost to me and to Dog.  But for those who can still be saved, heed my words, and join with us in this great battle.',
				'Now some would quibble about a few points here and there.  Certainly the Satanists.  But I think, all in all, most would agree that this is the way to salvation.',
				'Now you try.',
				'Prove me wrong.',
				'Radical!',
				'Repent.  Make haste before you are enveloped by the darkness of Satan.',
				'Reporting live from macn.me, this is Boby Mackerby, signing off.',
				'Seize the day!',
				'Shade and sweet water.',
				'Shine on, you crazy diamond.',
				'So long, and thanks for all the fish.',
				'So suck it.',
				'So, you know, I never got the sneakers.',
				'Surfs up, dude.',
				'Thank you for your attention to this matter.',
				'That\'s all folks!',
				'That\'s the news from Lake Wobegon, where the women won\'t find you handsome, but at least they\'ll find you handy.',
				'The end is nigh!',
				'Think about it.',
				'This information is classified.',
				'This message will self destruct.',
				'Thus, the answer to life, the universe, and everything is 42.',
				'To arms!',
				'Together, we can achieve true greatness',
				'Until next time.',
				'Use this information wisely.',
				'Vague but exciting.',
				'Who knew?',
				'Who now is willing to join me in this most righteous quest?',
				'Ya ken?',
				'You\'ll see.',
				'Your hero in arms, Boby Mackerby',
				'Your turn.',
			];
			var $p = document.createElement('p');
			$p.innerText = getRandItem(phrases);
			var $foot = $main.querySelector('.jp-relatedposts, footer, .relNav');
			try{
				$foot.parentNode.insertBefore($p, $foot);
			}catch(e){
				$main.appendChild($p);
			}
		}
	};
	if(doc.readyState !== 'loading'){
		main();
	}else{
		doc.addEventListener('DOMContentLoaded', main);
	}
}
