<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>&lt;toby&gt; Mackenzie</title>
<!--
._______.  _____  ._____   __     _.
|__   __| /  _  \ |   _  \ \ \  / /
   | |   |  | |  ||  |_| /   \ v /
   | |   |  | |  ||   _  \    | |
   | |   |  |_|  ||  |_|  \   | |
   |_|    \_____/ |______/    |_|
__     __    _      ______ ._.    __._______.._    ._. _______.._______.._______.
| \   / |  /   \   /  __  \| |   / /| ._____|| \   | ||_____  ||__   __|| ._____|
|  \ /  | /  ◊  \ | /    \|| | / /  | |_____.|  \  | |     / /    | |   | |_____.
| |\˘/| ||  / \  || |      |   <    | ._____|| |\ \| |   / /      | |   | ._____|
| | ˘ | || |   | || \___/¯|| | \ \  | |_____.| |  \  | / /____ .__| |__.| |_____.
|_|   |_||_|   |_| \_____/ |_|   \_\|_______||_|   \_||_______||_______||_______|
-->
			<meta content="initial-scale=1,width=device-width" name="viewport" />
			<link rel="shortcut icon" href="//www.tobymackenzie.com/favicon.ico" />
<?php
$cssFile = __DIR__ . '/../styles.css';
if(file_exists($cssFile)){
?>
			<style><!--
			<?=file_get_contents($cssFile)?>
			--></style>
<?php } ?>
		</head>
	<body>
		<div class="card" itemscope="itemscope" itemtype="http://schema.org/Person">
			<div class="cardFace cardFront">
				<div class="cardLogo"><div class="cardLogoText">&lt;toby&gt;</div></div>
				<div class="cardCol1">
					<div class="cardMedia">
						<img alt="From the background, the shape of a face presents itself. As it becomes clearer, a white, slightly scruffy humanoid male with slightly wavy, dark brown hair turning to gray, stares blankly at you.  The ghostly apparation seems distant, and yet… could it be…" class="cardImage" itemprop="image" src="/_toby.jpg" />
					</div>
				</div>
				<div class="cardCol2">
					<h1 class="cardItem" itemprop="name"><span itemprop="givenName">Toby</span> <span itemprop="familyName">Mackenzie</span></h1>
					<div class="cardGroup" itemprop="hasOccupation" itemscope="itemscope" itemtype="http://schema.org/Occupation">
						<div class="cardItem" itemprop="name"><strong>Webmaster</strong> <small class="cardItemEtc">(LAMP developer)</small></div>
						<small class="cardSkills cardItem" itemprop="skills">HTML CSS JS PHP MySQL Apache Linux</small>
					</div>
					<div class="cardItem"><a href="mailto:public@tobymackenzie.com" itemprop="email">public@tobymackenzie.com</a></div>
					<div class="cardItem"><a href="//www.tobymackenzie.com" itemprop="url">tobymackenzie.com</a></div>
					<div class="cardItem" itemprop="address" itemscope="itemscope" itemtype="http://schema.org/PostalAddress">
						<span itemprop="addressLocality">Akron</span>,
						<span itemprop="addressRegion">Ohio</span>
					</div>
				</div>
			</div>
			<div class="cardFace cardBack">
				<div class="cardBackLogo">&lt;toby/&gt;</div>
				<div>macn.me</div>
			</div>
		</div>
		<div class="appBG"></div>
<?php

$jsFile = __DIR__ . '/../../../dist/public/_assets/scripts/short.js';
if(file_exists($jsFile)){
?>
		<script><!--
		<?=file_get_contents($jsFile)?>
		(function(){
			//-!! forcing disable in safari because it's not working currently: back disappearing
			if(document.querySelector && window.CSS && CSS.supports && CSS.supports('backface-visibility: visible') && !(/^((?!chrome|android).)*safari/i.test(navigator.userAgent))){
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
		--></script>
<?php } ?>
	</body>
</html>
