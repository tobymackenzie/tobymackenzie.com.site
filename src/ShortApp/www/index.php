<?php
$date = new DateTime();
$isChristmastime = ($date->format('m') == 12) || ($date->format('md') <= 215);
?><!doctype html>
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
			<style><!--
				*{
					box-sizing: border-box;
					word-wrap: break-word;
					word-wrap: anywhere;
				}
				a{
					color: inherit;
					text-decoration-color: rgba(209, 255, 209, 0.5);
					transition: color 0.2s;
				}
				a:focus, a:hover{
					color: #90df90;
				}
				body, html{
					background: rgba(78,120,78,0.95);
					margin: 0;
					padding: 0;
				}
				body{
					min-height: 100%;
					/* min-height: 100vh; */
					opacity: 0.95;
					text-align: center;
					height: 100%;
				}
				.card{
					background: #353;
					border: 3px solid #002c00;
					box-shadow: inset 0 0 1em 0.5em #242;
					color: rgb(209, 255, 209);
					overflow: hidden;
					padding: 1em;
					position: relative;
					min-height: 100%;
					/* min-height: 100vh; */
				}
				.card:hover{
					overflow: visible;
				}
				.cardCol1{
					text-align: center;
					margin-bottom: 1.5em;
				}
				* + .cardGroup, * + .cardItem{
					margin-top: 0.5em;
				}
				.cardImage{
					display: block;
					filter: contrast(1.5) saturate(0);
					/*mask-image: radial-gradient(ellipse at center, rgba(0,0,0,0) 20%, rgba(0,0,0,0.8) 100%);*/
					max-height: 100%;
					max-width: 100%;
					mix-blend-mode: soft-light;
					object-fit: cover;
					opacity: 0.3;
					transition: opacity 0.4s;
				}
				.card:hover .cardImage{
					opacity: 0.8;
				}
				.cardItem strong{
					font-size: 1.4em;
				}
				.cardItemEtc{
					display: inline-block;
				}
				.cardMedia:after, .cardMedia:before{
					content: '';
					position: absolute;
				}
				.cardMedia:after{
					background: url('/_toby.jpg') center center no-repeat;
					background-size: cover;
					height: 100%;
					left: 0;
					opacity: 0;
					top: 0;
					transition: opacity 0.5s;
					width: 100%;
				}
				.cardMedia:hover:after{
					opacity: 0.6;
				}
				.cardMedia:before{
					bottom: -1px;
					box-shadow: 0 0 8px 8px #353 inset;
					left: -1px;
					right: -1px;
					top: -1px;
					z-index: 1;
				}
				/*--hide logo from old browsers that don't rotate text properly */
				.cardLogo{
					display: none;
				}
				@supports (writing-mode: vertical-lr){
					.cardLogo{
						bottom: 0;
						display: block;
						left: -0.5em;
						position: absolute;
						top: 0;
						z-index: 2;
					}
					.cardLogoText{
						color: #333;
						filter: blur(2px);
						font-size: 2em;
						line-height: 1;
						opacity: 0.2;
						pointer-events: none;
						position: absolute;
						text-align: center;
						top: 50%;
						transform: translateY(-50%);
						transform-origin: center top;
						transition: filter 0.4s, opacity 0.4s;
						white-space: nowrap;
						writing-mode: vertical-lr;
					}
				}
				.card:hover .cardLogoText{
					filter: blur(0);
					opacity: 0.6;
				}
				.cardMedia{
					display: inline-block;
					position: relative;
				}
				h1{
					border-bottom: 2px solid;
					color: #90df90;
					font-size: 2em;
					margin: 0 0 18px;
					padding: 0 0 10px;
					line-height: 1.1;
				}
				h1 span{
					/*--needed for first letter */
					display: inline-block;
				}
				h1 span:first-letter{
					color: #66ce66;
				}
				html{
					background-image: none, url("//www.tobymackenzie.com/_assets/grunge-pattern.png");
					font-family: meslo, "Meslo LG S", menlo, "Menlo Regular", cousine, Consolas, "Courier New", courier, monospace;
					height: 100%;
				}
				@media (min-width: 20em){
					.card{
						display: flex;
						justify-content: center;
						flex-direction: column;
					}
					.cardLogoText{
						font-size: 3em;
					}
					.cardMedia{
						display: inline-flex;
						min-height: 8em;
						height: 50vh;
					}
				}
				@media (min-height: 500px), (min-width: 34em){
					.cardMedia{
						height: auto;
					}
				}
				@media (min-height: 500px){
					.cardLogoText{
						font-size: 4em;
					}
				}
				@media (min-width: 34em){
					.card{
						align-items: center;
						flex-direction: row;
					}
					.cardImage{
						min-height: 10em;
						max-height: calc(100vh - 2.8em);
					}
					.cardCol1, .cardCol2{
						display: inline-block;
						text-align: left;
						vertical-align: middle;
					}
					.cardCol1{
						margin: 0 1em 0 0;
					}
				}
				@media (min-width: 34em) and (min-height: 24em){
					body{
						align-items: center;
						display: flex;
						justify-content: center;
						height: auto;
						min-height: 100%;
						padding: 0.5em; /*--ensure rotation doesn't cause scroll bars */
					}
					.card{
						align-items: flex-end;
						padding: 1.5em;
						transform: rotate(2deg);
						transition: transform 0.2s;
					}
					.card:hover, .card:focus-within{
						transform: rotate(0deg);
					}
					.cardCol1{
						align-self: stretch;
						padding-left: 1.5em;
						position: relative;
						width: auto;
					}
					.cardCol2{
						padding: 8px 0; /*--alignment for fuzzy image inset */
					}
					.cardLogo{
						left: -2.6em;
					}
					.cardLogoText{
						font-size: 6.2em;
					}
					h1{
						font-size: 3em;
						width: 5.8em;
					}
					@supports (object-fit: cover){
						.cardMedia{
							height: 100%;
						}
					}
				}
			--></style>
<?php
if($isChristmastime){
?>
			<link rel="stylesheet" href="//www.tobymackenzie.com/_assets/styles/christmas.css" />
			<script src="https://www.tobymackenzie.com/_assets/scripts/christmas.js?a" async="async"></script>
<?php
}
?>
		</head>
	<body>
		<div class="card" itemscope="itemscope" itemtype="http://schema.org/Person">
			<div class="cardCol1">
			<div class="cardLogo"><div class="cardLogoText">&lt;toby&gt;</div></div>
				<div class="cardMedia">
					<img alt="From the background, the shape of a face presents itself. As it becomes clearer, a white, slightly scruffy humanoid male with slightly wavy, dark brown hair turning to gray, stares blankly at you.  The ghostly apparation seems distant, and yet… could it be…" class="cardImage" itemprop="image" src="/_toby.jpg" />
				</div>
			</div>
			<div class="cardCol2">
				<h1 class="cardItem" itemprop="name"><span itemprop="givenName">Toby</span> <span itemprop="familyName">Mackenzie</span></h1>
				<div class="cardGroup" itemprop="hasOccupation" itemscope="itemscope" itemtype="http://schema.org/Occupation">
					<div class="cardItem" itemprop="name"><strong>Web developer</strong> <small class="cardItemEtc">(LAMP stack)</small></div>
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
<?php
if($isChristmastime){
?>
<style>
.appBG{ z-index: -1; }
</style>
<div class="appBG"></div>
<?php
}

$jsFile = __DIR__ . '/../../../dist/public/_assets/scripts/short.js';
if(file_exists($jsFile)){
?>
		<script><!--
		<?=file_get_contents($jsFile)?>
		--></script>
<?php } ?>
	</body>
</html>
