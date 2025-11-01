import * as extra from '../PublicApp/scripts/modules/extra.js';
import * as konamiCode from '../PublicApp/scripts/modules/konamiCode.js';

(function(){
	if(document.querySelector && window.CSS && CSS.supports && CSS.supports('backface-visibility: visible')){
		var $card = document.querySelector('.card');
		var $cardFaces = $card.querySelectorAll('.cardFace');
		for(var i = 0; i < $cardFaces.length; ++i){
			var $button = document.createElement('button');
			$button.innerHTML = '<span>flip</span>';
			$button.classList.add('cardFlip');
			$button.addEventListener('click', function(){
				if($card.dataset.show === 'back'){
					$card.dataset.show = 'front';
				}else{
					$card.dataset.show = 'back';
				}
			});
			$cardFaces[i].appendChild($button);
		}
	}
})();

//--hide darken of me image from neobrowser, possibly other old Chromium, otherwise crashes on load (aw snap)
(function(){
	var matches;
	if(
		document.querySelector && window.CSS && CSS.supports && CSS.supports('mix-blend-mode: darken')
		&& !(
			(matches = navigator.userAgent.match(/Chrome\/([^ \.]+)/))
			&& matches
			&& parseInt(matches[1], 10) <= 85
		)
	){
		document.querySelector('html').classList.add('supports-darken')
	}
})();
