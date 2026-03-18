<?php
use Symfony\Component\Yaml\Yaml;
require_once(__DIR__ . '/inc.php');
require_once(__DIR__ . '/qr.php');
$data = Yaml::parse(file_get_contents(DATA_PATH . '/card.yml'));
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title><?=$data['title']?></title>
<!--
<?=file_get_contents(DATA_PATH . '/ascii-sig.txt');?>
-->
			<meta content="initial-scale=1,width=device-width" name="viewport" />
			<link rel="shortcut icon" href="<?=$data['url']?>/favicon.ico" />
			<style><!--
			<?=file_get_contents(ENV !== 'dev' && file_exists(STYLES_DIST_PATH) ? STYLES_DIST_PATH : STYLES_PATH)?>
			--></style>
		</head>
	<body>
		<div class="card h-card" itemscope="itemscope" itemtype="http://schema.org/Person">
			<div class="cardFace cardFront">
				<div class="cardLogo"><div class="cardLogoText"><?=$data['logo']?></div></div>
				<div class="cardCol1">
					<div class="cardMedia">
						<img alt="<?=$data['imageAlt']?>" class="cardImage u-photo" itemprop="image" src="/_toby.jpg" width="<?=$data['imageWidth']?>" height="<?=$data['imageHeight']?>" />
					</div>
				</div>
				<div class="cardCol2">
					<h1 class="cardItem p-name" itemprop="name"><span class="p-given-name" itemprop="givenName"><?=$data['givenName']?></span> <span class="p-family-name" itemprop="familyName"><?=$data['familyName']?></span></h1>
					<div class="cardGroup" itemprop="hasOccupation" itemscope="itemscope" itemtype="http://schema.org/Occupation">
						<div class="cardItem p-job-title" itemprop="name"><strong><?=$data['job']['title']?></strong> <small class="cardItemEtc"><?=$data['job']['titleEtc']?></small></div>
						<small class="cardSkills cardItem" itemprop="skills"><?=$data['job']['skills']?></small>
					</div>
					<div class="cardItem"><a class="u-email" href="mailto:<?=$data['email']?>" itemprop="email"><?=$data['email']?></a></div>
					<div class="cardItem"><a class="u-url" href="<?=$data['url']?>" itemprop="url" rel="me"><?=$data['urlLabel']?></a></div>
					<div class="cardItem" itemprop="address" itemscope="itemscope" itemtype="http://schema.org/PostalAddress">
						<span class="p-locality" itemprop="addressLocality"><?=$data['locality']?></span>,
						<span class="p-region" itemprop="addressRegion"><?=$data['region']?></span>
					</div>
<?php if(!empty($qr)){ ?>
					<img class="qr" src="<?=$qr->render($data['url'])?>" alt="QR code: <?=$data['urlLabel']?>" />
<?php } ?>
				</div>
			</div>
			<div class="cardFace cardBack">
				<div class="cardBackLogo"><?=$data['logo2']?></div>
				<div class="cardItem">
					<?php if(GH_PAGES){ ?><a href="<?=$data['shortUrl']?>"><?php } ?>
						<?=$data['shortUrlLabel']?>
					<?php if(GH_PAGES){ ?></a><?php } ?>
				</div>
				<div class="cardItem"><a href="<?=$data['blogUrl']?>"><?=$data['blogUrlLabel']?></a></div>
				<div class="cardItem"><a href="<?=$data['githubUrl']?>"><?=$data['githubUrlLabel']?></a></div>
<?php if(!empty($qr) && !empty($vCard)){ ?>
				<img class="qr" src="<?=$qr->render($vCard)?>" alt="QR code: vCard" />
<?php } ?>
				<div class="a--message"><a href="<?=$data['messageUrl']?>"><?=$data['message']?></a></div>
			</div>
		</div>
		<a--bg></a--bg>
<?php
if(file_exists(JS_DIST_PATH)){
?>
		<script><!--
		<?=file_get_contents(JS_DIST_PATH)?>
		--></script>
<?php } ?>
	</body>
</html>
