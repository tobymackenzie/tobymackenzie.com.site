<?php
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\QROptions;
require_once(__DIR__ . '/inc.php');
require_once(AUTOLOAD_PATH);
$qr = (new QRCode(new QROptions([
	'addQuietzone'=> false,
	//'outputType'=> QRCode::OUTPUT_MARKUP_SVG,
	//'imageBase64'=> false,
	'scale'=> 2,
	'xmoduleValues'=> [
		1024=> [144,223,144],
		QRMatrix::M_ALIGNMENT=> [209, 255, 209],
		QRMatrix::M_FINDER=> [209, 255, 209],
		QRMatrix::M_FORMAT=> [144,223,144],
		QRMatrix::M_TIMING=> [144,223,144],

	],
])));
?>
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
			<style><!--
			<?=file_get_contents(ENV !== 'dev' && file_exists(STYLES_DIST_PATH) ? STYLES_DIST_PATH : STYLES_PATH)?>
			--></style>
		</head>
	<body>
		<div class="card h-card" itemscope="itemscope" itemtype="http://schema.org/Person">
			<div class="cardFace cardFront">
				<div class="cardLogo"><div class="cardLogoText">&lt;toby&gt;</div></div>
				<div class="cardCol1">
					<div class="cardMedia">
						<img alt="From the background, the shape of a face presents itself. As it becomes clearer, a white, slightly scruffy humanoid male with slightly wavy, dark brown hair turning to gray, stares blankly at you.  The ghostly apparation seems distant, and yet… could it be…" class="cardImage u-photo" itemprop="image" src="/_toby.jpg" width="225" height="300" />
					</div>
				</div>
				<div class="cardCol2">
					<h1 class="cardItem p-name" itemprop="name"><span class="p-given-name" itemprop="givenName">Toby</span> <span class="p-family-name" itemprop="familyName">Mackenzie</span></h1>
					<div class="cardGroup" itemprop="hasOccupation" itemscope="itemscope" itemtype="http://schema.org/Occupation">
						<div class="cardItem p-job-title" itemprop="name"><strong>Webmaster</strong> <small class="cardItemEtc">(LAMP developer)</small></div>
						<small class="cardSkills cardItem" itemprop="skills">HTML CSS JS PHP MySQL Apache Linux</small>
					</div>
					<div class="cardItem"><a class="u-email" href="mailto:public@tobymackenzie.com" itemprop="email">public@tobymackenzie.com</a></div>
					<div class="cardItem"><a class="u-url" href="//www.tobymackenzie.com" itemprop="url" rel="me">tobymackenzie.com</a></div>
					<div class="cardItem" itemprop="address" itemscope="itemscope" itemtype="http://schema.org/PostalAddress">
						<span class="p-locality" itemprop="addressLocality">Akron</span>,
						<span class="p-region" itemprop="addressRegion">Ohio</span>
					</div>
					<img class="qr" src="<?=$qr->render('https://www.tobymackenzie.com')?>" alt="QR code: tobymackenie.com" />
				</div>
			</div>
			<div class="cardFace cardBack">
				<div class="cardBackLogo">&lt;toby/&gt;</div>
				<div class="cardItem">
					<?php if(GH_PAGES){ ?><a href="//macn.me"><?php } ?>
						macn.me
					<?php if(GH_PAGES){ ?></a><?php } ?>
				</div>
				<div class="cardItem"><a href="//www.tobymackenzie.com/blog/">tobymackenzie.com/blog</a></div>
				<div class="cardItem"><a href="https://github.com/tobymackenzie">github.com/tobymackenzie</a></div>
<?php
$vCard = 'BEGIN:VCARD
VERSION:4.0
ADR:;;;Akron;Ohio;;USA
CATEGORIES:CSS,HTML,JavaScript,PHP
FN:Toby Mackenzie
EMAIL:public@tobymackenzie.com
N:Mackenzie;Toby
PHOTO:https://macn.me/_toby.jpg
TITLE:Webmaster
URL:https://www.tobmackenzie.com
URL:https://macn.me
END:VCARD';
?>
				<img class="qr" src="<?=$qr->render($vCard)?>" alt="QR code: vCard" />
				<div class="appHeaderMessage"><a href="//bxln2ho3.com">Be excellent to each other</a></div>
			</div>
		</div>
		<div class="appBG"></div>
<?php
if(file_exists(JS_DIST_PATH)){
?>
		<script><!--
		<?=file_get_contents(JS_DIST_PATH)?>
		--></script>
<?php } ?>
	</body>
</html>
